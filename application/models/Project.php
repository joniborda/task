<?php 

class Application_Model_Project
{
    protected $_id;
    protected $_name;
    protected $_site_url;
    protected $_description;
    protected $_date_start;
    protected $_date_end;
    protected $_active;
 
    public function __construct($data = null)
    {
        if ($data != null) {
            
            if (is_array($data)) {
                $this->_id = $data['id'];
                $this->_name = $data['name'];
                @$this->_site_url = $data['site_url'];
                @$this->_description = $data['description'];
                @$this->_date_start = $data['date_start'];
                @$this->_date_end = $data['date_end'];
                @$this->_active = $data['active'];
            } else if ($data instanceof Zend_Db_Table_Row) {
                $this->_id = $data->id;
                $this->_name = $data->name;
                $this->_site_url = $data->site_url;
                $this->_description = $data->description;
                $this->_date_start = $data->date_start;
                $this->_date_end = $data->date_end;
                $this->_active = $data->active;
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
      
    public function getCountOpenned() {
    	return Application_Service_Locator::getTaskService()->getCountOpenned($this->_id);
    }
    public function __toString()
    {
    	return $this->_name;
    }

    public function getSiteUrl()
    {
        return $this->_site_url;
    }
    
    public function setSiteUrl($SiteUrl)
    {
        $this->_site_url = $SiteUrl;
        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }
    
    public function setDescription($Description)
    {
        $this->_description = $Description;
        return $this;
    }
    
    public function getDateStart()
    {
        return $this->_date_start;
    }
    
    public function setDateStart($date_start)
    {
        $this->_date_start = $date_start;
        return $this;
    }
    public function getDateEnd()
    {
        return $this->_date_end;
    }
    
    public function setDateEnd($date_end)
    {
        $this->_date_end = $date_end;
        return $this;
    }

    public function getActive()
    {
        return $this->_active;
    }
    
    public function setActive($active)
    {
        $this->_active = $active;
        return $this;
    }
}
?>