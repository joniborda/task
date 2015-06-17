<?php 

class Application_Model_Respuesta
{
    protected $_id;
    protected $_user_id;
    protected $_comment_id;
    protected $_comment; 
    protected $_created; 
 
    public function __construct($data = null)
    {
        if ($data != null) {
            
            if (is_array($data)) {
                $this->_id = $data['id'];
                $this->_user_id = $data['user_id'];
                $this->_comment_id = $data['comment_id'];
                $this->_comment = $data['comment'];
                $this->_created = $data['created'];
            } else if ($data instanceof Zend_Db_Table_Row) {
                $this->_id = $data->id;
                $this->_user_id = $data->user_id;
                $this->_comment_id = $data->comment_id;
                $this->_comment = $data->comment;
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
    
    public function getUserId()
    {
        return $this->_user_id;
    }
    
    public function setUserId($user_id)
    {
    	$this->_user_id = $user_id;
    	return $this;
    }
    
    public function getComment()
    {
        return $this->_comment;
    }
    
    public function setComment($comment)
    {
    	$this->_comment = $comment;
    	return $this;
    }
    
    public function getCommentId()
    {
    	return $this->_comment_id;
    }
    
    public function setCommentId($comment_id)
    {
    	$this->_comment_id = $comment_id;
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
}
?>