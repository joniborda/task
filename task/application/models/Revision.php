<?php 

class Application_Model_Revision
{
    protected $_id;
    protected $_user_name;
    protected $_date;
    protected $_message;
    protected $_created;
    
    public function __construct($data = null)
    {
        if ($data != null) {
            
            if (is_array($data)) {
                $this->_id = $data['id'];
                $this->_user_name = (isset($data['user_name']) ? $data['user_name'] : null);
                $this->_date = (isset($data['date']) ? $data['date'] : null);
                $this->_message = (isset($data['message']) ? $data['message'] : null);
                $this->_created = (isset($data['created']) ? $data['created'] : null);
            } else if ($data instanceof Zend_Db_Table_Row) {
                $this->_id = $data->id;
                $this->_user_name = $data->user_name;
                $this->_date = $data->date;
                $this->_message = $data->message;
                $this->_created = $data->created;
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
    
    public function getUserName()
    {
    	return $this->_id;
    }
    
    public function setUserName($user_name)
    {
    	$this->_user_name = $user_name;
    	return $this;
    }
    public function getDate()
    {
    	return $this->_date;
    }
    
    public function setDate($date)
    {
    	$this->_date = $date;
    	return $this;
    }
    public function getMessage()
    {
    	return $this->_message;
    }
    
    public function setMessage($message)
    {
    	$this->_message = $message;
    	return $this;
    }
    public function getCreated()
    {
    	return $this->_created;
    }
    
    public function setCreated($created)
    {
    	$this->_created = $created;
    	return $this;
    }
    
    public function __toString()
    {
    	return $this->_message;
    }

    
}
?>