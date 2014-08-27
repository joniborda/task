<?php 

class Application_Model_Status
{
    protected $_id;
    protected $_descripcion;
    
    public function __construct($data = null)
    {
        if ($data != null) {
            
            if (is_array($data)) {
                $this->_id = $data['id'];
                $this->_descripcion = (isset($data['descripcion']) ? $data['descripcion'] : null);
            } else if ($data instanceof Zend_Db_Table_Row) {
                $this->_id = $data->id;
                $this->_descripcion = $data->descripcion;
            }
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid guestbook property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid property');
        }
        return $this->$method();
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function setId($id)
    {
    	$this->_id = $id;
    	return $this;
    }
    
    public function __toString()
    {
    	return $this->_descripcion;
    }

    public function getDescripcion()
    {
    	return $this->_descripcion;
    }
    
    public function setDescripcion($descripcion)
    {
    	$this->_descripcion = $descripcion;
    	return $this;
    }
}
?>