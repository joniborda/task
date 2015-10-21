<?php
/**
 * User Service.
 *
 * @category  Application
 * @package   Application_Service
 * @copyright 2012 Joni
 * @license   Copyright (C) 2012. All rights reserved
 * @version   Release: 1.0.0
 * @link      http://www.jonijoni.com
 * @since     1.0.0
 */

/**
 * User Service.
 *
 * @category  Application
 * @package   Applications_Service
 * @author    jborda <jonathanmatiasborda@gmail.com>
 * @copyright 2012 Joni
 * @license   Copyright (C) 2012. All rights reserved
 * @version   Release: 1.0.0
 * @link      http://www.jonijoni.com
 * @since     1.0.0
 */
class Application_Service_Usuario {

	/**
	 * @var Application_Dao_Usuario
	 */
	private $_usuarioDao;

	public function __construct(Application_Dao_Usuario $usuarioDao) {
		$this->_usuarioDao = $usuarioDao;
	}

	public function fetchAll() {
		return $this->_usuarioDao->fetchAll();
	}

	public function getById($id) {
		return $this->_usuarioDao->getById($id);
	}

	/**
	 * Resistra un usuario
	 *
	 * @param String $email
	 * @param String $user_name
	 * @param String $password
	 * @param String $repeat_password
	 * @param Boolean $is_admin Default false.
	 *
	 * @return Application_Model_Usuario
	 */
	public function registrar($email, $user_name, $password, $repeat_password, $profile_id) {
		if ($password == $repeat_password) {
			return $this->_usuarioDao->registrar($email, $user_name, $password, $profile_id);
		}

		throw new Zend_Validate_Exception('La contraseña no coincide');
	}

	/**
	 * Login a user
	 *
	 * @param String $user_name
	 * @param String $password
	 * @param String $rememberMe
	 *
	 * @return Application_Model_Usuario
	 */
	public function login($user_name, $password, $rememberMe) {
		$user = $this->_usuarioDao->getByName($user_name);

		if ($user !== null) {
			if ($user->getPassword() == $password) {
				Application_Service_Session::login($user, $rememberMe);
				return $user;
			}
			throw new Zend_Validate_Exception('La contraseña es incorrecta');
		}
		throw new Zend_Validate_Exception('No existe el usuario');
	}

	/**
	 * Search by Name
	 * @param String $name
	 * @param Array $discard
	 * @return Array
	 */
	public function searchByName($name, $discard = array()) {
		return $this->_usuarioDao->searchByName($name, $discard);
	}

	/**
	 * Consigue por name
	 *
	 * @param String $name
	 *
	 * @return Application_Model_Usuario
	 */
	public function getByName($name) {
		return $this->_usuarioDao->getByName($name);
	}

	/**
	 * Login By fb
	 *
	 * @param String $fb_key
	 * @param String $fb_name
	 *
	 * @return Application_Model_Usuario
	 */
	public function fblogin($fb_key, $fb_name) {
		$user = $this->_usuarioDao->getByFbKey($fb_key);

		if ($user !== null) {
			Application_Service_Session::login($user, true);
			return $user;
		}
		$user = $this->_usuarioDao->registrar_fb($fb_key, $fb_name);
		if ($user !== null) {
			Application_Service_Session::login($user, true);
			return $user;
		}

		throw new Zend_Validate_Exception('No se puede registrar');
	}
}