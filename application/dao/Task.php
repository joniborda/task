<?php

class Application_Dao_Task
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable(array('db' => 'localhost'));
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    /**
     * @return Application_Model_DbTable_Task
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Task');
        }
        return $this->_dbTable;
    }

    /**
     * Consigue todos los Tasks
     *
     * @return array
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll(null,array('id asc'),null,null);
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Task($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del Task.
     * 
     * @return Application_Model_Task
     */
    public function getById($id)
    {
        $resultSet = $this->getDbTable()->find((int)$id)->current();
        
        if ($resultSet != null) {
            return new Application_Model_Task($resultSet);
        }
        
        return null;
    }
    
    /**
     * Crea un Task
     * 
     * @param String $title
     * @param String $project_id
     * @param Integer $user_id
     * 
     * @return Application_Model_Task
     */
    public function crear($title, $project_id, $user_id)
    {
    	$data = array(
    		'title' => $title,
    		'projects_id' => $project_id,
    		'user_id' => $user_id,
    		'status_id' => 1 // Status default
    	);
    	
    	$data['id'] = $this->getDbTable()->insert($data);

        return new Application_Model_Task($data);
    }
    
    /**
     * Consigue todos los Tasks segun el filtro
     *
     * @return array
     */
    public function getAllByFilters($project_id = null, $status_id = null, $user_id = null, $title = null,
        $parent_id = null)
    {
        $where = array();
        $binds = array();

        if (isset($project_id)) {
            $where['projects_id = ?'] = (int)$project_id;
        }
        
        if (isset($status_id)) {
            $where['status_id = ?'] = (int)$status_id;
        }
        
        if ($parent_id !== null) {
            if ($parent_id !== false) {
                $where['parent_id = ?'] = (int)$parent_id;
            }
        }

    	if (isset($title)) {
    		$where['title ilike :title_like OR search_title @@ plainto_tsquery(:title)'] = array();
    		$binds['title_like'] = '%'.$title.'%';
    		$binds['title'] = $title;
    	}
    	
    	$select = $this->getDbTable()->select();
    	if (isset($user_id)) {
    		$where['ut.user_id = ?'] = (int)$user_id;
    		$select = $this->getDbTable()
    			->select()
    			->setIntegrityCheck(false)
    			->from(array('tasks'=>'tasks'), 'tasks.*')
    			->join(array('ut'=>'users_task'), 'ut.task_id = tasks.id', '');
    	}
    	
    	foreach ($where as $key => $value) {
    		$select = $select->where($key, $value);
    	}
    	
        if ($parent_id === false) {
            $select = $select->where('parent_id is null');
        }
    	$select->bind($binds)->order('tasks.sort asc')->order('tasks.id asc');
    	
    	$resultSet = $this->getDbTable()->fetchAll($select);
    	$entries = array();

    	foreach ($resultSet as $row) {
    		$entry = new Application_Model_Task($row);
    		$entries[] = $entry;
    	}
    	return $entries;
    }
    
    /**
     * Set status
     * 
     * @param Integer $id
     * @param Array   $data
     * 
     * @return Integer The number of rows updated
     */
    public function updateById($id, $data)
    {
    	if (is_array($data)) {
    		
    		$data['last_modified'] = 'NOW()';
    		
    		unset($data['user_id']);
    		
    		return $this->getDbTable()->update(
    			$data, 
    			array(
    				'id = ?' => $id
    			)
    		);
    	}
    }
    
    /**
     * Delete by id
     *
     * @param Integer $id
     *
     * @return Boolean
     */
    public function deleteById($id)
    {
    	if ($id != null) {
    
    		if ($this->getDbTable()->delete(array('id = ?' => $id))) {
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     * Delete by id
     *
     * @param Integer $id
     *
     * @return Boolean
     */
    public function deleteByProjectId($id)
    {
    	if ($id != null) {
    
    		if ($this->getDbTable()->delete(array('projects_id = ?' => $id))) {
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     * Consigue la cantidad de tareas abiertas
     *
     * @return Integer
     */
    public function getCountOpenned($project_id = null)
    {
    	$where = array();
    
    	if (isset($project_id)) {
    		$where['projects_id = ?'] = (int)$project_id;
    	}
    	 
   		$where['status_id = ?'] = 1;
    	 
    	$select = $this->getDbTable()->select()->from($this->getDbTable(),'count(*)');
    	 
    	foreach ($where as $key => $value) {
    		$select = $select->where($key, $value);
    	}
    	 
    	$resultSet = $this->getDbTable()->fetchRow($select);
    	
    	if ($resultSet) {
    		return $resultSet->count;
    	}
		return 0;
    }
    
    /**
     * Get Last Created by User
     *
     * @param string $user_id
     *
     * @return Date
     */
    public function getLastByUser($user_id = null) {
    	$where = array ();
    	if ($user_id) {
    		$where = array (
    			'user_id = ?' => $user_id
    		);
    	}
    
    	$select = $this->getDbTable ()->select ()->from ( $this->getDbTable (), 'max(created)' );
    
    	foreach ( $where as $key => $value ) {
    		$select = $select->where ( $key, $value );
    	}
    
    	if ($data = $this->getDbTable ()->fetchRow ( $select )) {
    			
    		if (isset($data->max)) {
    			return $data->max;
    		}
    	}
    }

    public function getSortById($id)
    {
        $select = $this->getDbTable()->select()->from($this->getDbTable(),'sort');
        $select = $select->where('id = ?', $id);

        $resultSet = $this->getDbTable ()->fetchRow ( $select );
        if (isset($resultSet->sort)) {
            return $resultSet->sort;
        }
        
        return null;
    }
    
}
?>