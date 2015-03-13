<?php

class Application_Dao_Revision
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
     * @return Application_Model_DbTable_Revision
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Revision');
        }
        return $this->_dbTable;
    }

    /**
     * Consigue todos los Revisions
     *
     * @return array
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll(null,null,null,null);
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Revision($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del Revision.
     * 
     * @return Application_Model_Revision
     */
    public function getById($id)
    {
        $where = $this->getDbTable()->select()->where('id = ?', $id);
        
        $resultSet = $this->getDbTable()->find($id)->current();
        
        if ($resultSet != null) {
            return new Application_Model_Revision($resultSet);
        }
        
        return null;
    }
    
    /**
     * Crea un Revision
     * 
     * @param unknown $revision
     * @param unknown $author
     * @param unknown $message
     * @param unknown $date
     * 
     * @return Application_Model_Revision
     */
    public function crear($revision, $author, $message, $date)
    {
    	$data = array(
    		'id' => $revision,
    		'user_name' => $author,
    		'message' => $message,
    		'date' => $date
    	);
    	
    	$this->getDbTable()->insert($data);
    	
    	return new Application_Model_Revision($data);
    }
    
    /**
     * Set revision
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
     * Get last insert id
     * 
     * @return Application_Model_Revision
     */
    public function getLastId() {
    	$select  = $this->getDbTable()->select()->from('revision','id')->order('id desc')->limit(1);
    	
    	$resultSets = $this->getDbTable()->fetchAll($select);
    	foreach ($resultSets as $row) {
    		return $row->id;
    	}
    }
}
?>