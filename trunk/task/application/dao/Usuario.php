<?php

class Application_Dao_Usuario
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
     * 
     * @return Application_Model_DbTable_Usuario
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Usuario');
        }
        return $this->_dbTable;
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Usuario($row);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    /**
     * Consigue un usuario por id.
     * 
     * @param Integer $id El id del usuario.
     * 
     * @return Application_Model_Usuario
     */
    public function getById($id)
    {
        $where = $this->getDbTable()->select()->where('id = ?', $id);
        
        $resultSet = $this->getDbTable()->find($id)->current();
        
        if ($resultSet != null) {
            return new Application_Model_Usuario($resultSet);
        }
        
        return null;
    }
    
    /**
     * Consigue por name
     * 
     * @param String $name
     * 
     * @return Application_Model_Usuario
     */
    public function getByName($name)
    {
    	$where = $this->getDbTable()
    		->select()
    		->where('name = ?', $name);
    	
    	$resultSet = $this->getDbTable()->fetchRow($where);
    	
    	if ($resultSet != null) {
    		return new Application_Model_Usuario($resultSet);
    	}
    	
    	return null;
    }
    
    /**
     * Resistra un usuario
     * 
     * @param String $email
     * @param String $user_name
     * @param String $password
     * @param Boolean $is_admin Default false.
     * 
     * @return Application_Model_Usuario
     */
    public function registrar($email, $user_name, $password, $is_admin = false)
    {
    	$validator = new Zend_Validate_EmailAddress();
    	
    	if (!$validator->isValid($email)) {
    		throw new Zend_Validate_Exception('Ingrese un mail correcto');
    	}
    	
    	$validator = new Zend_Validate_StringLength();
    	$validator->setMin(6);
    	
    	if (!$validator->isValid($password)) {
    		throw new Zend_Validate_Exception('Ingrese una contraseña de más de 6 caracteres');
    	}
    	
    	$data = array(
    		'email' => $email,
    		'user_name' => $user_name,
    		'password' => $password,
    		'register_date' => 'NOW()',
    		'is_admin' => $is_admin
		);
    	
    	$id = $this->getDbTable()->insert($data);
    	$data['id'] = $id;
    	
    	return new Application_Model_Usuario($data);
    }
    
    /**
     * Search by Name
     * @param String $name
     * @param Array  $discard
     * 
     * @return Array
     */
    public function searchByName($name, $discard = array())
    {
    	$where = array(
    		'name ilike ?' => '%' . $name . '%'
    	);
    	if (!empty($discard)) {
    		$where['name not in (?)'] = $discard;
    	}
    	
    	$resultSet = $this->getDbTable()->fetchAll($where);
    	$entries = array();
    	
    	foreach ($resultSet as $row) {
    		$entry = new Application_Model_Usuario($row);
    		$entries[] = $entry;
    	}
    	return $entries;
    }
}
?>