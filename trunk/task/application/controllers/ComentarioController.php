<?php

class ComentarioController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
    public function crearAction()
    {
    	$this->view
    		->headLink()
    		->appendStylesheet($this->view->pathCss . '/comentario/crear.css' );
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    		$comentario = $request->getParam('comentario');
    		if ($comentario == null) {
    			$this->view->sin_comentario = true;
    			return;
    		}
    		$user = Application_Service_Locator::getSessionService()->getUser();
    		
    		$id_usuario = $user
    			->getId();
    		
	    	$comentario = Application_Service_Locator::getComentarioService()
	    		->crear($id_usuario, $comentario, date('c'));
	    	
	    	if ($comentario != null) {
	    		$this->view->success = true;
	    		$mail = new Zend_Mail();
	    		$mail->setBodyText(
	    			utf8_decode(
	    				'Mail: ' . $user->getEmail() . "\n" .
	    				'Usuario: ' . $user->getUserName() . "\n" .
    					'Comentó: ' . $comentario
					)
				);
	    		$mail->addTo('jonathanmatiasborda@gmail.com', 'JBorda');
	    		$mail->setSubject('Nuevo comentario');
	    		$mail->send();
	    	}
	    	
	    	
	    	
	    	
    	}
    }
    
    public function enviadosAction()
    {
    	$this->view
    		->headLink()
    		->appendStylesheet($this->view->pathCss . '/comentario/enviados.css' );
    	
    	$user_id = Application_Service_Locator::getSessionService()
    		->getUser()
    		->getId();
    	
    	$comentarios = Application_Service_Locator::getComentarioService()
    		->getAllByUserId($user_id);
    	
    	$this->view->comentarios = $comentarios;
    }
    
    public function verAction()
    {
    	$request = $this->getRequest();
    	$id = $request->getParam('id');
    	if (null == $id) {
    		// TODO: Poner un mensaje
    		$this->_redirect('/');
    	}
    	
    	$comentario = Application_Service_Locator::getComentarioService()
    		->getById($id);
    	
    	if (null === $comentario) {
    		// TODO: Poner un mensaje
    		$this->_redirect('/');
    	}
    	
    	$user_id = $comentario->getUserId();
    	
    	$user_session_id = Application_Service_Locator::getSessionService()
    		->getUser()
    		->getId();
    	if ($user_id != $user_session_id) {
    		// TODO: Poner un mensaje
    		$this->_redirect('/');
    	}
    	
    	$this->view->comentario = $comentario;
    	$respuestas = Application_Service_Locator::getRespuestaService()
    		->getAllByCommentId($comentario->getId());
    	
    	$this->view->respuestas = $respuestas;
    }
}