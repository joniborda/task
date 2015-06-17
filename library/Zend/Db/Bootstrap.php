<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
/**
     * Sets the config to the application's config manager.
     *
     * @return void
     */
    protected function _initConfig()
    {
        Application_Config_Application::setConfig(
            array(
                Application_Config_Application::APPLICATION_NAME => 'servicio_tecnico'
            )
        );
    }

   /**
     * Init the db adapter
     *
     * @return void
     */
    protected function _initDbAdapter()
    {
        $config = array(
            'host'       => 'mysql17.000webhost.com',
            'port'		 => '3306',
            'username'   => 'a9740855_joni',
            'password'   => 'que mierda pongo',
            'dbname'     => 'a9740855_service',
            'charset'    => 'utf8',
        );

        $dbLocalhost = Zend_Db::factory('MYSQLI', $config);
        Zend_Registry::set('localhost', $dbLocalhost);
        
    }
    
	/**
     * Inits the default Zend_View instance.
     *
     * @return Zend_View
     */
    protected function _initView()
    {
    	
    	// SET DEFAULT MAIL TRANSPORT
    	$config = array(
			'auth' => 'login',
			'username' => 'serviciotecnicojborda@gmail.com',
			'password' => 'nosenose'
		);
    	$tr = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);    	
    	
    	// By default Zend_Mime::ENCODING_QUOTEDPRINTABLE
    	$mail = new Zend_Mail('UTF-8');
    	
    	// Reset to Base64 Encoding because Russian expressed in KOI8-R is
    	// different from Roman letters-based languages greatly.
    	$mail->setHeaderEncoding(Zend_Mime::ENCODING_QUOTEDPRINTABLE);
    	
    	$mail->setDefaultTransport($tr);
    	$mail->setDefaultFrom('serviciotecnicojborda@gmail.com', utf8_decode('Servicio Técnico JB'));
    	
    	// SETEO LA BASE-URL
    	$router     = new Zend_Controller_Router_Rewrite();
    	$controller = Zend_Controller_Front::getInstance();
    	$controller->setBaseUrl('/servicio_tecnico'); // set the base url!
    	
        // Initialize view
        $view = new Zend_View();

        $view->setEncoding('UTF-8');
        
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Assign current enviroment to the view
        $view->env = $this->getApplication()->getEnvironment();

        // Set default paginator template
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'paginator.phtml'
        );

        $view->baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/servicio_tecnico';
        $view->webApp = $view->baseUrl . '/public';
        $view->pathCss = $view->baseUrl . '/public/css';

        
//        	$view->user = Application_Service_Session::getUser();
       	
		
        Zend_Registry::set('view', $view);
        
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

}

