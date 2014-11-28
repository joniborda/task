<?php
/**
 * Service locator.
 *
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @since     1.0.0
 */

/**
 * Service locator.
 *
 * @license   Copyright 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @since     1.0.0
 */
class Application_Service_Locator
{

    /**
     * Get the user service.
     *
     * @return Application_Service_Usuario
     */
    public static function getUsuarioService()
    {
        return new Application_Service_Usuario(
            new Application_Dao_Usuario()
        );
    }
    
    /**
     * Get the session service.
     *
     * @return Application_Service_Session
     */
    public static function getSessionService()
    {
    	return new Application_Service_Session(new Zend_Session());
    }
    
	/**
     * Get the comment service.
     *
     * @return Application_Service_Project
     */
    public static function getProjectService()
    {
        return new Application_Service_Project(
            new Application_Dao_Project(),
        	new Application_Dao_Task()
        );
    }
    
    /**
     * Get the reply service.
     *
     * @return Application_Service_Respuesta
     */
    public static function getRespuestaService()
    {
    	return new Application_Service_Respuesta(
    			new Application_Dao_Respuesta()
    	);
    }
    
    /**
     * Get the comment service.
     *
     * @return Application_Service_Task
     */
    public static function getTaskService()
    {
    	return new Application_Service_Task(
    			new Application_Dao_Task(),
    			new Application_Dao_UserTask(),
    			new Application_Dao_ChangeTask()
    	);
    }
    
    /**
     * Get the user_task service.
     *
     * @return Application_Service_UserTask
     */
    public static function getUserTaskService()
    {
    	return new Application_Service_UserTask(
    			new Application_Dao_UserTask()
    	);
    }
    
    /**
     * Get the Status service.
     *
     * @return Application_Service_Status
     */
    public static function getStatusService()
    {
    	return new Application_Service_Status(
    			new Application_Dao_Status()
    	);
    }
    
    /**
     * Get the ChangeTask service.
     *
     * @return Application_Service_ChangeTask
     */
    public static function getChangeTaskService()
    {
    	return new Application_Service_ChangeTask(
    			new Application_Dao_ChangeTask()
    	);
    }
}