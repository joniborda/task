<?php
class Application_Model_Usuario {
	protected $_id;
	protected $_name;
	protected $_password;
	protected $_profile_id;
	protected $_mail;
	protected $_fb_key;

	public function __construct($data = null) {
		if ($data != null) {

			if (is_array($data)) {
				$this->_id = $data['id'];
				$this->_name = $data['name'];
				if (isset($data['password'])) {
					$this->_password = $data['password'];
				}
				if (isset($data['profile_id'])) {
					$this->_profile_id = $data['profile_id'];
				}
				if (isset($data['mail'])) {
					$this->_mail = $data['mail'];
				}
				if (isset($data['fb_key'])) {
					$this->_fb_key = $data['fb_key'];
				}
			} else if ($data instanceof Zend_Db_Table_Row) {
				$this->_id = $data->id;
				$this->_name = $data->name;
				$this->_password = $data->password;
				$this->_profile_id = $data->profile_id;
				$this->_mail = $data->mail;
				$this->_fb_key = $data->fb_key;
			}
		}
	}
	public function __set($name, $value) {
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid guestbook property');
		}
		$this->$method($value);
	}
	public function __get($name) {
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid guestbook property');
		}
		return $this->$method();
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
		return Application_Service_Locator::getChangeTaskService()->getLastModifiedByUser($this->_id);
	}

	public function getMail() {
		return $this->_mail;
	}
	public function setMail($mail) {
		$this->_mail = $mail;
	}

	public function getFbKey() {
		return $this->_fb_key;
	}
}
?>