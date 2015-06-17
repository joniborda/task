<?php

class UsuarioController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
    public function registrarAction()
    {
    	$this->view
    		->headLink()
    		->appendStylesheet($this->view->pathCss . '/usuario/registrar.css' );
    	
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

    public function loguearAction()
    {
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
	    	$user_name = $request->getParam('user_name');
	    	$password = $request->getParam('password');
	    	
	    	try {
		    	$user = Application_Service_Locator::getUsuarioService()
		    		->login($user_name, $password, true);
		    	if (null !== $user) {
		    		
		    		if (Application_Service_Session::get('redirect')) {
		    			$url = Application_Service_Session::get('redirect');
		    			
	    				Application_Service_Session::set('redirect', '');
		    			$this->_redirect($url);		    			
		    		} else {
			    		$this->_redirect('/');
		    		}
		    	}
	    	} catch (Zend_Validate_Exception $e) {
	    		$this->view->error = $e->getMessage();
	    	}
    	}
    	
    	$this->view
	    	->headLink()
	    	->appendStylesheet($this->view->pathCss . '/usuario/loguear.css' );
    }
    
    public function salirAction()
    {
    	$url = Application_Service_Session::get('redirect');
    	Application_Service_Session::logout();
    	$this->_redirect('/?redirect=' . $url);
    }
    
    public function searchAction()
    {
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
    				'name'=> $user->getName()
    			);
    		}
    	}
    }
    
    public function createAction()
    {
    	$this->_helper->layout->setLayout('empty');
    	$this->view->profiles = Application_Service_Locator::getProfileService()->listAll();
    }
    
    public function addAction()
    {
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
	    					'id' => $usuario->getId()
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
    					'error' => $error
    			));
    		}
    	} else {
    		
	    	$this->view->assign('response', array('response'=>false, 'error' => 'Faltan campos obligatorios'));
    	}
    }
}