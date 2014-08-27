<?php

class Application_Dao_Status
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
     * @return Application_Model_DbTable_Status
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Status');
        }
        return $this->_dbTable;
    }

    /**
     * Consigue todos los Statuss
     *
     * @return array
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll(null,null,null,null);
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Status($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del Status.
     * 
     * @return Application_Model_Status
     */
    public function getById($id)
    {
        $where = $this->getDbTable()->select()->where('id = ?', $id);
        
        $resultSet = $this->getDbTable()->find($id)->current();
        
        if ($resultSet != null) {
            return new Application_Model_Status($resultSet);
        }
        
        return null;
    }
    
    /**
     * Crea un Status
     * 
     * @param String $descripcion
     * 
     * @return Application_Model_Status
     */
    public function crear($descripcion)
    {
    	$data = array(
    		'descripcion' => $descripcion,
    		'projects_id' => $project_id
    	);
    	
    	$data['id'] = $this->getDbTable()->insert($data);
    	
    	return new Application_Model_Status($data);
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
    		
    		return $this->getDbTable()->update(
    			$data, 
    			array('id = ?' => $id)
    		);
    	}
    }

    /**
     * Get by Description
     * 
     * @param String $descripcion
     * 
     * @return Application_Model_Status
     */
    public function getByDescripcion($descripcion)
    {
    	$resultSet = $this->getDbTable()->fetchRow(array('descripcion = ?' => $descripcion));
    	
    	if ($resultSet != null) {
    		return new Application_Model_Status($resultSet);
    	}
    	
    	return null;
    }
}
?>