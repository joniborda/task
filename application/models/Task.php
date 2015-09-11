<?php 

class Application_Model_Task
{
    protected $_id;
    protected $_title;
    protected $_projects_id;
    protected $_status_id;
    protected $_start;
    protected $_end;
    protected $_descripcion;
    protected $_types_id;
    protected $_time;
    protected $_last_modified;
    protected $_created;
    protected $_user_id;
    protected $_sort;
    protected $_parent_id;
    protected $_deadline;
    
    public function __construct($data = null)
    {
        if ($data != null) {
            
            if (is_array($data)) {
                $this->_id = $data['id'];
                $this->_title = (isset($data['title']) ? $data['title'] : null);
                $this->_projects_id = (isset($data['projects_id']) ? $data['projects_id'] : null);
                $this->_status_id = (isset($data['status_id']) ? $data['status_id'] : null);
                $this->_start = (isset($data['start']) ? $data['start'] : null);
                $this->_end = (isset($data['end']) ? $data['end'] : null);
                $this->_descripcion = (isset($data['descripcion']) ? $data['descripcion'] : null);
                $this->_types_id = (isset($data['types_id']) ? $data['types_id'] : null);
                $this->_time = (isset($data['time']) ? $data['time'] : null);
                $this->_last_modified = (isset($data['last_modified']) ? $data['last_modified'] : null);
                $this->_created = (isset($data['created']) ? $data['created'] : null);
                $this->_user_id = (isset($data['user_id']) ? $data['user_id'] : null);
                $this->_sort = (isset($data['sort']) ? $data['sort'] : null);
                $this->_parent_id = (isset($data['parent_id']) ? $data['parent_id'] : null);
                $this->_deadline = (isset($data['deadline']) ? $data['deadline'] : null);
            } else if ($data instanceof Zend_Db_Table_Row) {
                $this->_id = $data->id;
                $this->_title = $data->title;
                $this->_projects_id = $data->projects_id;
                $this->_status_id = $data->status_id;
                $this->_start = $data->start;
                $this->_end = $data->end;
                $this->_descripcion = $data->descripcion;
                $this->_types_id = $data->types_id;
                $this->_time = $data->time;
                $this->_last_modified = $data->last_modified;
                $this->_created = $data->created;
                $this->_user_id = $data->user_id;
                $this->_sort = $data->sort;
                $this->_parent_id = $data->parent_id;
                $this->_deadline = $data->deadline;
            }
        }
    }
 
    public function __set($name, $value)
    {
    	$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
    	$method = 'set' . $filter->filter($name);
    	
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid guestbook property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
    	$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
        $method = 'get' . $filter->filter($name);
        
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid property ' . $method);
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

    public function getStart()
    {
    	return $this->_start;
    }
    
    public function setStart($start)
    {
    	$this->_start = $start;
    	return $this;
    }

	public function getEnd()
    {
    	return $this->_end;
    }

    public function getEndFormat()
    {
        try {
            $date = new DateTime($this->_end);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function setEnd($end)
    {
    	$this->_end = $end;
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
    	try {
    	
    		$date = new DateTime($this->_last_modified);
    		return $date->format('c');
    	} catch (Exception $e) {
    		return null;
    	}
    	
    }
    
    public function setLastModified($last_modified)
    {
    	$this->_last_modified = $last_modified;
    	return $this;
    }

    public function getCreated()
    {
    	try {
    		if ($this->_created) {
        		$date = new DateTime($this->_created);
    	    	return $date->format('c');
            }
    	} catch (Exception $e) {
    		return null;
    	}
    	
    }
    
    public function setCreated($created)
    {
    	$this->_created = $created;
    	return $this;
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
    
    public function getUserId()
    {
    	return $this->_user_id;
    }
    
    public function setUserId($user_id)
    {
    	$this->_user_id = $user_id;
    	return $this;
    }
    
    public function getImages() {
    	return Application_Service_Locator::getTaskService()->getImages($this);
    }

    public function getSort()
    {
        if (!$this->_sort) {
            return $this->_sort = Application_Service_Locator::getTaskService()->getSort($this);
        }
        return $this->_sort;
    }
    
    public function setSort($sort)
    {
        $this->_sort = $sort;
        return $this;
    }

    public function getParentId()
    {
        return $this->_parent_id;
    }
    
    public function setParentId($parent_id)
    {
        $this->_parent_id = $parent_id;
        return $this;
    }

    public function getDeadline()
    {
        return $this->_deadline;
    }
    
    public function setDeadline($deadline)
    {
        $this->_deadline = $deadline;
        return $this;
    }

    public function getDeadlineFormat()
    {
        try {
            if ($this->_deadline) {   
                $date = new DateTime($this->_deadline);
                return $date->format('Y-m-d');
            }
        } catch (Exception $e) {
            return null;
        }
    }
}
?>