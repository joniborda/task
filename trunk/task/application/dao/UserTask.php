<?php

class Application_Dao_UserTask
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
     * @return Application_Model_DbTable_UserTask
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_UserTask');
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
        $resultSet = $this->getDbTable()->fetchAll(null,null,null,null);
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_UserTask($row);
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
        $where = $this->getDbTable()->select()->where('id = ?', $id);
        
        $resultSet = $this->getDbTable()->find($id)->current();
        
        if ($resultSet != null) {
            return new Application_Model_UserTask($resultSet);
        }
        
        return null;
    }
    
    /**
     * Crea un Task
     * 
     * @param Integer $task_id
     * @param Integer $user_id
     * 
     * @return Application_Model_UserTask
     */
    public function crear($task_id, $user_id)
    {
    	$data = array(
    		'task_id' => $task_id,
    		'user_id' => $user_id
    	);
    	
    	$data['id'] = $this->getDbTable()->insert($data);
    	
    	return new Application_Model_UserTask($data);
    }
    
    /**
     * Consigue todos los Tasks
     *
     * @param Integer $task_id
     * 
     * @return array
     */
    public function getAllByTaskId($task_id = null)
    {
    	$resultSet = $this->getDbTable()->fetchAll(array('task_id = ?'=> $task_id), 'id asc');
    	$entries = array();

    	foreach ($resultSet as $row) {
    		$entry = new Application_Model_UserTask($row);
    		$entries[] = $entry;
    	}
    	return $entries;
    }
}
?>