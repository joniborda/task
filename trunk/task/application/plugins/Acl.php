<?php
/**
 * Plugin to enforce ACL.
 *
 */
class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     * The ACL.
     *
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * Create a new instance of the ACL plugin.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_acl = new Zend_Acl();
        $this->_acl->addRole(new Zend_Acl_Role('guest'))
                    ->addRole(new Zend_Acl_Role('user'), 'guest');

        // Define each path as a resource. This is done this way because
        // we have in different modules controllers with the same name and they
        // collide in the acl.
        $this->_acl->addResource(new Zend_Acl_Resource('/usuario'))
                   ->addResource(new Zend_Acl_Resource('/error'))
                   ->addResource(new Zend_Acl_Resource('/index'))
                   ->addResource(new Zend_Acl_Resource('/task'))
        		   ->addResource(new Zend_Acl_Resource('/project'));

        // tendría que denegar todos primero y despues permitir los necesarios
        $this->_acl->deny('guest')
        			->allow('guest', '/usuario')
        			->allow('guest', '/error')
                    ->allow('user');

    }

    /**
     * Called before Zend_Controller_Front enters its dispatch loop.
     *
     * @param Zend_Controller_Request_Abstract $request The request
     *                                                  being dispatched
     *
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract
        $request)
    {
	
	
    }

    /**
     * Called before an action is dispatched by Zend_Controller_Dispatcher.
     *
     * @param Zend_Controller_Request_Abstract $request The request
     *                                                  being dispatched
     *
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $path = '/' . $request->getControllerName();
        if (!$this->_acl->has($path)) {
//            Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')
//                ->gotoUrlAndExit('/guestbook');
        }

        $user = Application_Service_Session::getUser();
        $view = Zend_Registry::get('view');
        $view->user = $user;
        
        $role = 'guest';
        
        if (null !== $user) {
            $role = 'user';
        }
        
        // Check if the user has permissions over the module/controller/action
        $allowed = $this->_acl->isAllowed(
            $role, $path, $request->getActionName()
        );
        
        if (!$allowed) {
            Zend_Controller_Action_HelperBroker::getStaticHelper('redirector')
                ->gotoUrlAndExit('/usuario/loguear');
        }
    }
}
