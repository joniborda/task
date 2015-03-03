<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    { 
//    	var_dump($_SESSION);die();
    	$this->view
    		->headLink()
    		->appendStylesheet($this->view->pathCss . '/tooltipster/tooltipster.css' )
    		->appendStylesheet($this->view->pathCss . '/tooltipster/themes/tooltipster-shadow.css' )
    		->appendStylesheet($this->view->pathCss . '/index/index.css' );
    	$this->view->deny =  $this->getRequest()->getParam('deny');
    	
    	$this->view->assign(
    		'projects',
    		Application_Service_Locator::getProjectService()->fetchAll()
    	);
    	
    	$this->view->assign(
    			'users',
    			Application_Service_Locator::getUsuarioService()->fetchAll()
    	);
    	$this->view->headScript()
    		->appendFile($this->view->webApp . '/js/tooltipster/jquery.tooltipster.js')
    		->appendFile($this->view->webApp . '/js/index/index.js');
    }


}

