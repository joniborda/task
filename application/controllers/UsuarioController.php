<?php

class UsuarioController extends Zend_Controller_Action {

	public function init() {
		/* Initialize action controller here */
	}

	public function indexAction() {
		// action body
	}

	public function registrarAction() {
		$this->view
		     ->headLink()
		     ->appendStylesheet($this->view->pathCss . '/usuario/registrar.css');

		if ($this->_request->isPost()) {
			$email = $this->_request->getParam('email');
			$user_name = $this->_request->getParam('user_name');
			$password = $this->_request->getParam('password');
			$repeat_password = $this->_request->getParam('repeat_password');

			try {
				$user = Application_Service_Locator::getUsuarioService()
					->registrar($email, $user_name, $password, $repeat_password);
			} catch (Zend_Exception $e) {
				if ($e instanceof Zend_Validate_Exception) {
					$this->view->error = $e->getMessage();
				}

				if ($e instanceof Zend_Db_Statement_Mysqli_Exception) {
					$this->view->error = 'No se puedo registrar';
				}
				return;
			}
			Application_Service_Session::login($user);
			$this->_redirect('/');
		}
	}

	public function loguearAction() {
		$request = $this->getRequest();

		if ($request->isPost()) {

			$user_name = $request->getParam('user_name');
			$password = $request->getParam('password');

			try {
				$user = Application_Service_Locator::getUsuarioService()
					->login($user_name, $password, true);
				if (null !== $user) {

					$redirect = $request->getParam('redirect');
					$redirect = urldecode(str_replace('___', '%2F', $redirect));

					$this->_redirect($redirect);

				}
			} catch (Zend_Validate_Exception $e) {
				$this->view->error = $e->getMessage();
			}
		}

		$this->view
		     ->headLink()
		     ->appendStylesheet($this->view->pathCss . '/usuario/loguear.css');
	}

	public function salirAction() {
		$url = $_SERVER['HTTP_REFERER'];
		Application_Service_Session::logout();
		$this->_redirect('/usuario/loguear/redirect/' . str_replace('%2F', '___', urlencode($url)));
	}

	public function searchAction() {
		$this->_helper->layout->setLayout('empty');
		$this->view->users = array();
		if ($this->_request->isXmlHttpRequest()) {

			$name = $this->_request->getParam('name');
			$discard = $this->_request->getParam('discard');

			$users = Application_Service_Locator::getUsuarioService()->searchByName($name, $discard);

			$this->view->users = array();
			foreach ($users as $user) {
				$this->view->users[] = array(
					'id' => $user->getId(),
					'name' => $user->getName(),
				);
			}
		}
	}

	public function createAction() {
		$this->_helper->layout->setLayout('empty');
		$this->view->profiles = Application_Service_Locator::getProfileService()->listAll();
	}

	public function addAction() {
		$this->_helper->layout->setLayout('empty');
		if (
			($name = $this->getRequest()->getParam('name')) &&
			($password = $this->getRequest()->getParam('password')) &&
			($repeat_password = $this->getRequest()->getParam('repeat_password')) &&
			($profile_id = $this->getRequest()->getParam('profile_id'))
		) {
			$mail = $this->getRequest()->getParam('mail');

			try {
				if (($usuario = Application_Service_Locator::getUsuarioService()
					->registrar($mail, $name, $password, $repeat_password, $profile_id))) {
					$this->view->assign('response', array(
						'response' => true,
						'id' => $usuario->getId(),
					));
				}

			} catch (Zend_Exception $e) {
				if ($e instanceof Zend_Validate_Exception) {
					$error = $e->getMessage();
				}

				if ($e instanceof Zend_Db_Statement_Exception) {
					$error = 'No se puedo registrar';
				}

				$this->view->assign('response', array(
					'response' => false,
					'error' => $error,
				));
			}
		} else {

			$this->view->assign('response', array('response' => false, 'error' => 'Faltan campos obligatorios'));
		}
	}

	public function fbloginAction() {
		$request = $this->getRequest();
		if ($request->isXmlHttpRequest()) {

			$fb_key = $request->getParam('fb_key');
			$fb_name = $request->getParam('fb_name');
			try {

				$user = Application_Service_Locator::getUsuarioService()
					->getByFbKey($fb_key);

				$response = array(
					'response' => false,
					'redirect' => $this->getFrontController()->getBaseUrl() . '/usuario/profile',
				);

				if ($user) {
					$response['redirect'] = $this->getFrontController()->getBaseUrl() . '/index';
				}

				$user = Application_Service_Locator::getUsuarioService()
					->fblogin($fb_key, $fb_name);

				if (null !== $user) {

					$response['response'] = true;
					echo json_encode($response);
					die;
				}

			} catch (Zend_Validate_Exception $e) {
				echo json_encode($e->getMessage());
				die;
			}
		}
	}

	public function profileAction() {

		$this->view
		     ->headLink()
		     ->appendStylesheet($this->view->pathCss . '/usuario/profile.css');

		if ($this->_request->isPost()) {
			$email = $this->_request->getParam('mail');
			$password = $this->_request->getParam('password');
			$repeat_password = $this->_request->getParam('repeat_password');
			try {
				$user = Application_Service_Session::getUser();

				Application_Service_Locator::getUsuarioService()
					->update($user->getId(), $email, $password, $repeat_password);

				$this->_redirect('/index/index/');
			} catch (Zend_Exception $e) {
				if ($e instanceof Zend_Validate_Exception) {
					$this->view->error = $e->getMessage();
				}

				if ($e instanceof Zend_Db_Statement_Mysqli_Exception) {
					$this->view->error = 'No se pudieron guardar los datos';
				}
				return;
			}
		}

	}

	public function generateimageAction() {
		if (Application_Service_Locator::getUsuarioService()->generateImage()) {
			$this->_redirect('/index/index/');
		} else {
			$this->view->error = 'Verifique que tiene permisos sobre la carpeta de avatars';
		}
	}

	public function twitterconnectAction() {

		$consumer_key = 'UxnnweOnFrOAgFgddGYErPloI';
		$consumer_secret = 'qHyxcMDw9rB9kIgyevHRb3YoCQcHxU15yJJG8YAMYPYZaGQO1m';

		//$access_token = '3941172137-i5D8sbD9CfbdVKiIXGkQrdKJe7m9dDfxAWamZ4A';
		//$access_token_secret = 'VGS08DiH3qMXeBpy4FOaJ2be6oa6cHqHCMc4mN4as2szq';

		if (isset($_SESSION['oauth_token'])) {
			$oauth_token = $_SESSION['oauth_token'];
			unset($_SESSION['oauth_token']);

			$connection = new Zend_Twitter_TwitterOAuth($consumer_key, $consumer_secret);
			//necessary to get access token other wise u will not have permision to get user info
			$params = array("oauth_verifier" => $_GET['oauth_verifier'], "oauth_token" => $_GET['oauth_token']);
			$access_token = $connection->oauth("oauth/access_token", $params);
			//now again create new instance using updated return oauth_token and oauth_token_secret because old one expired if u dont u this u will also get token expired error
			$connection = new Zend_Twitter_TwitterOAuth($consumer_key, $consumer_secret,
				$access_token['oauth_token'], $access_token['oauth_token_secret']);
			$content = $connection->get("account/verify_credentials");

			$_SESSION['twitter_data'] = $content;

			$user = Application_Service_Locator::getUsuarioService()
				->twLogin($content->id, $content->screen_name);
			if ($user) {
				Application_Service_Locator::getUsuarioService()->saveImageTw($content->profile_image_url);
				$this->_redirect('/index/index/');
			}
			$this->view->error = 'No se pudo loguear con Twitter';
			$this->_redirect('/usuario/login');

		} else {
			// main startup code
			//this code will return your valid url which u can use in iframe src to popup or can directly view the page as its happening in this example

			$connection = new Zend_Twitter_TwitterOAuth($consumer_key, $consumer_secret);
			$temporary_credentials = $connection->oauth('oauth/request_token', array("oauth_callback" => 'http://localhost/task/usuario/twitterConnect'));
			$_SESSION['oauth_token'] = $temporary_credentials['oauth_token'];
			$_SESSION['oauth_token_secret'] = $temporary_credentials['oauth_token_secret'];
			$url = $connection->url("oauth/authorize", array("oauth_token" => $temporary_credentials['oauth_token']));
			// REDIRECTING TO THE URL
			header('Location: ' . $url);
		}
	}
}