<?php

class Application_Dao_Profile
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
     * @return Application_Model_DbTable_Profile
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Profile');
        }
        return $this->_dbTable;
    }

    /**
     * Consigue todos los Profiles
     *
     * @return array
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll(null,'id asc',null,null);
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Profile($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del Profile.
     * 
     * @return Application_Model_Profile
     */
    public function getById($id)
    {
        $resultSet = $this->getDbTable()->find($id);
        if (0 == count($resultSet)) {
        	return;
        }
        $row = $resultSet->current();
        if ($row != null) {
            return new Application_Model_Profile($row);
        }
        
        return null;
    }
    
    /**
     * Crea un Profile
     * 
     * @param String $name
     * 
     * @return Application_Model_Profile
     */
    public function crear($name)
    {
    	$data = array(
    		'name' => $name
    	);
    	
    	$data['id'] = $this->getDbTable()->insert($data);
    	
    	return new Application_Model_Profile($data);
    }
    
    /**
     * Update a Profile
     *
     * @param Integer $id
     * @param String $name
     *
     * @return void
     */
    public function update($id, $name)
    {
    	$data = array(
    			'name' => $name
    	);
    	$this->getDbTable()->update($data, array('id = ?'=> (int)$id));
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
}
?>