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

	/**
	 * Get by FB key
	 *
	 * @param String $fb_key
	 *
	 * @return Application_Model_Usuario
	 */
	public function getByFbKey($fb_key) {
		return $this->_usuarioDao->getByFbKey($fb_key);
	}

	/**
	 * Resistra un usuario
	 *
	 * @param String $email
	 * @param String $user_name
	 * @param String $password
	 * @param String $repeat_password
	 *
	 * @return Application_Model_Usuario
	 */
	public function update($id, $email, $password, $repeat_password) {
		if ($password == $repeat_password) {
			$this->_usuarioDao->update($id, $email, $password);
			return;
		}

		throw new Zend_Validate_Exception('La contraseña no coincide');
	}

	public function generateImage() {
		$ancho = 320;
		$alto = 320;
		$img = imagecreate($ancho, $alto);
		$white = imagecolorallocate($img, 224, 224, 223);
		$colors = array(
			imagecolorallocate($img, 61, 167, 164),
			imagecolorallocate($img, 213, 86, 76),
			imagecolorallocate($img, 217, 189, 124),
			imagecolorallocate($img, 196, 194, 85),
			imagecolorallocate($img, 128, 202, 161),
			imagecolorallocate($img, 208, 89, 209),
		);

		$color = $colors[rand(0, count($colors) - 1)];

		// Draw a white rectangle
		for ($x1 = $ancho / 8; $x1 <= $ancho / 2; $x1 = $x1 + $ancho / 8) {
			for ($y1 = $alto / 8; $y1 <= $alto * 7 / 8; $y1 = $y1 + $alto / 8) {
				for ($x2 = $ancho / 8; $x2 <= $ancho / 2; $x2 = $x2 + $ancho / 8) {
					for ($y2 = $alto / 8; $y2 <= $alto * 7 / 8; $y2 = $y2 + $alto / 8) {

						if ($x1 != $x2 and $y1 != $y2) {

							if (rand(0, 1)) {
								imagefilledrectangle($img, $x1, $y1, $x2, $y2, $white);
								imagefilledrectangle($img, $ancho - $x1, $y1, $ancho - $x2, $y2, $white);
							} else {
								imagefilledrectangle($img, $x1, $y1, $x2, $y2, $color);
								imagefilledrectangle($img, $ancho - $x1, $y1, $ancho - $x2, $y2, $color);
							}
						}
					}
				}
			}
		}

		$path = $this->_createPathImage();

		// Save the image
		imagepng($img, $path . DIRECTORY_SEPARATOR . 'profile.png');
		imagedestroy($img);

		return true;
	}

	/**
	 * Login By twitter
	 *
	 * @param String $twitter_id
	 * @param String $screen_name
	 *
	 * @return Application_Model_Usuario
	 */
	public function twlogin($twitter_id, $screen_name) {
		$user = $this->_usuarioDao->getByTwitterId($twitter_id);

		if ($user !== null) {
			Application_Service_Session::login($user, true);
			return $user;
		}
		$user = $this->_usuarioDao->registrar_tw($twitter_id, $screen_name);
		if ($user !== null) {
			Application_Service_Session::login($user, true);
			return $user;
		}

		throw new Zend_Validate_Exception('No se puede registrar');
	}

	private function _createPathImage() {
		$path =
		Application_Config_Application::getProfilePath() . DIRECTORY_SEPARATOR .
		Application_Service_Session::getUser()->getId();

		if (!is_dir($path)) {
			mkdir($path);
		}

		return $path;
	}

	public function saveImageTw($image_url_tw) {

		file_put_contents(
			$this->_createPathImage() . DIRECTORY_SEPARATOR .
			'profile.png',
			file_get_contents($image_url_tw)
		);
	}
}
