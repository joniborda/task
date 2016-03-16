<?php

class Application_Dao_Usuario {
	protected $_dbTable;

	public function setDbTable($dbTable) {
		if (is_string($dbTable)) {
			$dbTable = new $dbTable(array('db' => 'localhost'));
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	/**
	 *
	 * @return Application_Model_DbTable_Usuario
	 */
	public function getDbTable() {
		if (null === $this->_dbTable) {
			$this->setDbTable('Application_Model_DbTable_Usuario');
		}
		return $this->_dbTable;
	}

	public function fetchAll() {
		$resultSet = $this->getDbTable()->fetchAll();
		$entries = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Usuario($row);
			$entries[] = $entry;
		}
		return $entries;
	}

	/**
	 * Consigue un usuario por id.
	 *
	 * @param Integer $id El id del usuario.
	 *
	 * @return Application_Model_Usuario
	 */
	public function getById($id) {
		$where = $this->getDbTable()->select()->where('id = ?', $id);

		$resultSet = $this->getDbTable()->find($id)->current();

		if ($resultSet != null) {
			return new Application_Model_Usuario($resultSet);
		}

		return null;
	}

	/**
	 * Consigue por name
	 *
	 * @param String $name
	 *
	 * @return Application_Model_Usuario
	 */
	public function getByName($name) {
		$where = $this->getDbTable()
		              ->select()
		              ->where('name = ?', $name);

		$resultSet = $this->getDbTable()->fetchRow($where);

		if ($resultSet != null) {
			return new Application_Model_Usuario($resultSet);
		}

		return null;
	}

	/**
	 * Registra un usuario
	 *
	 * @param String $email
	 * @param String $user_name
	 * @param String $password
	 * @param Boolean $is_admin Default false.
	 *
	 * @return Application_Model_Usuario
	 */
	public function registrar($email, $user_name, $password, $profile_id) {
		if ($email) {
			$validator = new Zend_Validate_EmailAddress();

			if (!$validator->isValid($email)) {
				throw new Zend_Validate_Exception('Ingrese un mail correcto');
			}
		}

		$validator = new Zend_Validate_StringLength();
		$validator->setMin(6);

		if (!$validator->isValid($password)) {
			throw new Zend_Validate_Exception('Ingrese una contraseña de más de 6 caracteres');
		}

		$data = array(
			'mail' => $email,
			'name' => $user_name,
			'password' => $password,
			'created' => 'NOW()',
			'profile_id' => $profile_id,
		);

		$id = $this->getDbTable()->insert($data);
		$data['id'] = $id;

		return new Application_Model_Usuario($data);
	}

	/**
	 * Search by Name
	 * @param String $name
	 * @param Array  $discard
	 *
	 * @return Array
	 */
	public function searchByName($name, $discard = array()) {
		$where = array(
			'name ilike ?' => '%' . $name . '%',
		);
		if (!empty($discard)) {
			$where['name not in (?)'] = $discard;
		}

		$resultSet = $this->getDbTable()->fetchAll($where);
		$entries = array();

		foreach ($resultSet as $row) {
			$entry = new Application_Model_Usuario($row);
			$entries[] = $entry;
		}
		return $entries;
	}

	/**
	 * Consigue por fb_key
	 *
	 * @param String $fb_key
	 *
	 * @return Application_Model_Usuario
	 */
	public function getByFbKey($fb_key) {
		$where = $this->getDbTable()
		              ->select()
		              ->where('fb_key = ?', $fb_key);

		$resultSet = $this->getDbTable()->fetchRow($where);
		if ($resultSet != null) {
			return new Application_Model_Usuario($resultSet);
		}

		return null;
	}

	/**
	 * Registra por fb
	 *
	 * @param String $fb_key
	 * @param String $fb_name
	 *
	 * @return Application_Model_Usuario
	 */
	public function registrar_fb($fb_key, $fb_name) {

		$data = array(
			'fb_key' => $fb_key,
			'name' => str_replace(' ', '.', strtolower($fb_name)),
			'created' => 'NOW()',
			'profile_id' => 1,
		);

		$id = $this->getDbTable()->insert($data);
		$data['id'] = $id;

		return new Application_Model_Usuario($data);
	}

	/**
	 * Registra un usuario
	 *
	 * @param String $email
	 * @param String $password
	 *
	 * @return Application_Model_Usuario
	 */
	public function update($id, $email, $password) {
		if ($email) {
			$validator = new Zend_Validate_EmailAddress();

			if (!$validator->isValid($email)) {
				throw new Zend_Validate_Exception('Ingrese un mail correcto');
			}
		}

		$validator = new Zend_Validate_StringLength();
		$validator->setMin(6);

		if (!$validator->isValid($password)) {
			throw new Zend_Validate_Exception('Ingrese una contraseña de más de 6 caracteres');
		}

		$data = array(
			'mail' => $email,
			'password' => $password,
		);

		$this->getDbTable()->update($data, array('id = ?' => (int) $id));
	}

	/**
	 * Registra por twitter
	 *
	 * @param String $twitter_id
	 * @param String $screen_name
	 *
	 * @return Application_Model_Usuario
	 */
	public function registrar_tw($twitter_id, $screen_name) {

		$data = array(
			'twitter_id' => $twitter_id,
			'name' => str_replace(' ', '.', strtolower($screen_name)),
			'created' => 'NOW()',
			'profile_id' => 1,
		);

		$id = $this->getDbTable()->insert($data);
		$data['id'] = $id;

		return new Application_Model_Usuario($data);
	}

	/**
	 * Consigue por twitter_id
	 *
	 * @param String $twitter_id
	 *
	 * @return Application_Model_Usuario
	 */
	public function getByTwitterId($twitter_id) {
		$where = $this->getDbTable()
		              ->select()
		              ->where('twitter_id = ?', $twitter_id);

		$resultSet = $this->getDbTable()->fetchRow($where);
		if ($resultSet != null) {
			return new Application_Model_Usuario($resultSet);
		}

		return null;
	}
}
?>