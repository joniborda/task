<?php 

class Application_Model_Profile
{
    protected $_id;
    protected $_name;
    protected $_description;
 
    public function __construct($data = null)
    {
        if ($data != null) {
            
            if (is_array($data)) {
                $this->_id = $data['id'];
                $this->_name = $data['name'];
                $this->_description = $data['description'];
            } else if ($data instanceof Zend_Db_Table_Row) {
                $this->_id = $data->id;
                $this->_name = $data->name;
                $this->_description = $data->description;
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
            throw new Exception('Invalid guestbook property');
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
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setName($name)
    {
    	$this->_name = $name;
    	return $this;
    }
    
    public function getDescription()
    {
    	return $this->_description;
    }
    
    public function setDescription($description)
    {
    	$this->_description = $description;
    	return $this;
    }
      
    public function __toString()
    {
    	return $this->_description;
    }
}
?>