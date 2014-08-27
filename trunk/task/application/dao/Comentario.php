<?php

class Application_Dao_Comentario
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
     * @return Application_Model_DbTable_Comentario
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Comentario');
        }
        return $this->_dbTable;
    }

    /**
     * Consigue todos los comentarios
     *
     * @return array
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll(null,null,1,null);
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Comentario($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del comentario.
     * 
     * @return Application_Model_Comentario
     */
    public function getById($id)
    {
        $where = $this->getDbTable()->select()->where('id = ?', $id);
        
        $resultSet = $this->getDbTable()->find($id)->current();
        
        if ($resultSet != null) {
            return new Application_Model_Comentario($resultSet);
        }
        
        return null;
    }
    
    /**
     * Consigue todos los comentarios de un usuario
     *
     * @return array
     */
    public function getAllByUserId($user_id)
    {
    	$where = array(
    		'user_id = ?' => $user_id
		);
    	
    	$order = 'created desc';
    	
    	$resultSet = $this->getDbTable()->fetchAll($where, $order);
    	$entries = array();
    	foreach ($resultSet as $row) {
    		$entry = new Application_Model_Comentario($row);
    		$entries[] = $entry;
    	}
    	return $entries;
    }
    
    /**
     * Crea un comentario
     * 
     * @param Integer $id_usuario
     * @param String $comentario
     * @param String $fecha
     * 
     * @return Application_Model_Comentario
     */
    public function crear($id_usuario, $comentario, $fecha)
    {
    	$data = array(
    		'user_id' => $id_usuario,
    		'comment' => $comentario,
    		'created' => $fecha
    	);
    	
    	$data['id'] = $this->getDbTable()->insert($data);
    	
    	return new Application_Model_Comentario($data);
    }
}
?>