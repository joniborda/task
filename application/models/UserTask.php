<?php 

class Application_Model_UserTask
{
    protected $_id;
    protected $_user_id;
    protected $_task_id;
        
    public function __construct($data = null)
    {
        if ($data != null) {
            
            if (is_array($data)) {
                $this->_id = $data['id'];
                $this->_user_id = (isset($data['user_id']) ? $data['user_id'] : null);
                $this->_task_id = (isset($data['task_id']) ? $data['task_id'] : null);
            } else if ($data instanceof Zend_Db_Table_Row) {
                $this->_id = $data->id;
                $this->_user_id = $data->user_id;
                $this->_task_id = $data->task_id;
            }
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid UserTask property');
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
    
    public function getUserId()
    {
        return $this->_user_id;
    }
    
    public function setUserId($user_id)
    {
    	$this->_user_id = $user_id;
    	return $this;
    }

    public function getTaskId()
    {
    	return $this->_task_id;
    }
    
    public function setTaskId($task_id)
    {
    	$this->_task_id = $task_id;
    	return $this;
    }
    
    public function __toString()
    {
    	return $this->_id;
    }
    
    /**
     * Get user
     * 
     * @return Application_Model_Usuario
     */
    public function getUser()
    {
    	return Application_Service_Locator::getUsuarioService()->getById($this->_user_id);
    }
}
?>