<?php
/**
 * UserTask Service.
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
 * UserTask Service.
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
class Application_Service_UserTask
{

    /**
     * @var Application_Dao_UserTask
     */
    private $_UserTaskDao;
    
    public function __construct(Application_Dao_UserTask $UserTaskDao)
    {
        $this->_UserTaskDao = $UserTaskDao;
    }

    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del UserTask.
     * 
     * @return Application_Model_UserTask
     */
    public function getById($id)
    {
        return $this->_UserTaskDao->getById($id);
    }
    
    /**
     * Consigue todos los UserTasks
     * 
     * @return array
     */
	public function fetchAll()
    {
    	return $this->_UserTaskDao->fetchAll();
    }

    /**
     * Consigue todos los UserTasks de un usuario
     *
     * @return array
     */
    public function getAllByUserId($user_id)
    {
    	return $this->_UserTaskDao->getAllByUserId($user_id);
    }
    
    /**
     * Crea un UserTask
     *
     * @param String $task_id
     * @param String $user_id
     * 
     * @return Application_Model_UserTask
     */
    public function crear($task_id, $user_id)
    {
		return $this->_UserTaskDao->crear($task_id, $user_id);
    }
    
    /**
     * Consigue todos los UserTasks de un Project
     * 
     * @param Integer $task_id
     *
     * @return array
     */
    public function getAllByTaskId($task_id)
    {
    	return $this->_UserTaskDao->getAllByTaskId($task_id);
    }
    
    /**
     * Remove a UserTask
     *
     * @param String $task_id
     * @param String $user_id
     *
     * @return Integer
     */
    public function remove($task_id, $user_id)
    {
    	return $this->_UserTaskDao->remove($task_id, $user_id);
    }
}