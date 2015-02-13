<?php
class Application_Model_Usuario {
	protected $_id;
	protected $_name;
	protected $_password;
	protected $_profile_id;
	public function __construct($data = null) {
		if ($data != null) {
			
			if (is_array ( $data )) {
				$this->_id = $data ['id'];
				$this->_name = $data ['name'];
				$this->_password = $data ['password'];
				$this->_profile_id = $data ['profile_id'];
			} else if ($data instanceof Zend_Db_Table_Row) {
				$this->_id = $data->id;
				$this->_name = $data->name;
				$this->_password = $data->password;
				$this->_profile_id = $data->profile_id;
			}
		}
	}
	public function __set($name, $value) {
		$method = 'set' . $name;
		if (('mapper' == $name) || ! method_exists ( $this, $method )) {
			throw new Exception ( 'Invalid guestbook property' );
		}
		$this->$method ( $value );
	}
	public function __get($name) {
		$method = 'get' . $name;
		if (('mapper' == $name) || ! method_exists ( $this, $method )) {
			throw new Exception ( 'Invalid guestbook property' );
		}
		return $this->$method ();
	}
	public function getId() {
		return $this->_id;
	}
	public function getName() {
		return $this->_name;
	}
	public function getPassword() {
		return $this->_password;
	}
	public function getProfileId() {
		return $this->_profile_id;
	}
	public function setId($id) {
		$this->_id = $id;
	}
	public function setName($name) {
		$this->_name = $name;
	}
	public function setPassword($password) {
		$this->_password = $password;
	}
	public function setProfileId($profile_id) {
		$this->_profile_id = $profile_id;
	}
	
	/**
	 * Get Last Modified
	 *
	 * @return Date
	 */
	public function getLastModified() {
		return Application_Service_Locator::getChangeTaskService ()->getLastModifiedByUser ( $this->_id );
	}
}
?>