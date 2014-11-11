<?php 

class Application_Model_Task
{
    protected $_id;
    protected $_title;
    protected $_projects_id;
    protected $_status_id;
    protected $_priorities_id;
    protected $_start;
    protected $_end;
    protected $_realized;
    protected $_descripcion;
    protected $_types_id;
    protected $_time;
    protected $_last_modified;
    
    
    public function __construct($data = null)
    {
        if ($data != null) {
            
            if (is_array($data)) {
                $this->_id = $data['id'];
                $this->_title = (isset($data['title']) ? $data['title'] : null);
                $this->_projects_id = (isset($data['projects_id']) ? $data['projects_id'] : null);
                $this->_status_id = (isset($data['status_id']) ? $data['status_id'] : null);
                $this->_priorities_id = (isset($data['priorities_id']) ? $data['priorities_id'] : null);
                $this->_start = (isset($data['start']) ? $data['start'] : null);
                $this->_end = (isset($data['end']) ? $data['end'] : null);
                $this->_realized = (isset($data['realized']) ? $data['realized'] : null);
                $this->_descripcion = (isset($data['descripcion']) ? $data['descripcion'] : null);
                $this->_types_id = (isset($data['types_id']) ? $data['types_id'] : null);
                $this->_time = (isset($data['time']) ? $data['time'] : null);
                $this->_last_modified = (isset($data['last_modified']) ? $data['last_modified'] : null);
            } else if ($data instanceof Zend_Db_Table_Row) {
                $this->_id = $data->id;
                $this->_title = $data->title;
                $this->_projects_id = $data->projects_id;
                $this->_status_id = $data->status_id;
                $this->_priorities_id = $data->priorities_id;
                $this->_start = $data->start;
                $this->_end = $data->end;
                $this->_realized = $data->realized;
                $this->_descripcion = $data->descripcion;
                $this->_types_id = $data->types_id;
                $this->_time = $data->time;
                $this->_last_modified = $data->last_modified;
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
    
    public function getTitle()
    {
        return $this->_title;
    }
    
    public function setTitle($title)
    {
    	$this->_title = $title;
    	return $this;
    }

    public function getTime()
    {
    	return $this->_time;
    }
    
    public function setTime($time)
    {
    	$this->_time = $time;
    	return $this;
    }
    
    public function getStatusId()
    {
    	return $this->_status_id;
    }
    
    public function setStatusId($status_id)
    {
    	$this->_status_id = $status_id;
    	return $this;
    }
    
    public function __toString()
    {
    	return $this->_title;
    }
    
    /**
     * @var Application_Model_Usuario[]
     */
    public function getUsers()
    {
    	$users_task = Application_Service_Locator::getUserTaskService()->getAllByTaskId($this->_id);
    	
    	$ret = array();
    	if (!empty($users_task)) {
	    	foreach ($users_task as $user_task) {
	    		$ret[] = $user_task->getUser()->getName();
	    	}
    	}
    	return $ret;
    }

    /**
     * Get status
     * @return Application_Model_Status
     */
    public function getStatus()
    {
    	if ($this->_status_id) {
	    	return Application_Service_Locator::getStatusService()
	    		->getById($this->_status_id);
    	}
    }

    public function getLastModified()
    {
    	return $this->_last_modified;
    }
    
    public function setLastModified($last_modified)
    {
    	$this->_last_modified = $last_modified;
    	return $this;
    }
    
}
?>