<?php

class Application_Dao_Respuesta
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
     * @return Application_Model_DbTable_Respuesta
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Respuesta');
        }
        return $this->_dbTable;
    }

    /**
     * Consigue todas las respuestas
     *
     * @return array
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Respuesta($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del comentario.
     * 
     * @return Application_Model_Respuesta
     */
    public function getById($id)
    {
        $where = $this->getDbTable()->select()->where('id = ?', $id);
        
        $resultSet = $this->getDbTable()->find($id)->current();
        
        if ($resultSet != null) {
            return new Application_Model_Respuesta($resultSet);
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
    	
    	$resultSet = $this->getDbTable()->fetchAll($where);
    	$entries = array();
    	foreach ($resultSet as $row) {
    		$entry = new Application_Model_Respuesta($row);
    		$entries[] = $entry;
    	}
    	return $entries;
    }
    
    /**
     * Crea un comentario
     * 
     * @param Integer $id_usuario
     * @param Integer $id_comentario
     * @param String $comentario
     * @param String $fecha
     * 
     * @return Application_Model_Respuesta
     */
    public function crear($id_usuario, $id_comentario, $comentario, $fecha)
    {
    	$data = array(
    		'user_id' => $id_usuario,
    		'comment_id' => $id_comentario,
    		'comment' => $comentario,
    		'created' => $fecha
    	);
    	
    	$data['id'] = $this->getDbTable()->insert($data);
    	
    	return new Application_Model_Respuesta($data);
    }
    
    /**
     * Consigue todas las respuesta de un comentario
     * @param Integer $comentario_id
     *
     * @return array
     */
    public function getAllByCommentId($comentario_id)
    {
    	$where = array(
    			'comment_id = ?' => $comentario_id
    	);
    	 
    	$resultSet = $this->getDbTable()->fetchAll($where, 'created desc');
    	$entries = array();
    	foreach ($resultSet as $row) {
    		$entry = new Application_Model_Respuesta($row);
    		$entries[] = $entry;
    	}
    	return $entries;
    }
}
?>