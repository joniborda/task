<?php
/**
 * Task Service.
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
 * Task Service.
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
class Application_Service_Task
{

    /**
     * @var Application_Dao_Task
     */
    private $_TaskDao;

    /**
     * @var Application_Dao_UserTask
     */
    private $_UserTaskDao;
    
    public function __construct(Application_Dao_Task $TaskDao, Application_Dao_UserTask $UserTaskDao)
    {
        $this->_TaskDao = $TaskDao;
        $this->_UserTaskDao = $UserTaskDao;
    }

    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del Task.
     * 
     * @return Application_Model_Task
     */
    public function getById($id)
    {
        return $this->_TaskDao->getById($id);
    }
    
    /**
     * Consigue todos los Tasks
     * 
     * @return array
     */
	public function fetchAll()
    {
    	return $this->_TaskDao->fetchAll();
    }

    /**
     * Consigue todos los Tasks de un usuario
     *
     * @return array
     */
    public function getAllByUserId($user_id)
    {
    	return $this->_TaskDao->getAllByUserId($user_id);
    }
    
    /**
     * Crea un Task
     *
     * @param String $title
     * @param String $project_id
     * @param Array $users
     * 
     * @return Application_Model_Task
     */
    public function crear($title,$project_id, $users)
    {
    	$task = $this->_TaskDao->crear($title,$project_id);
    	
    	if ($task) {
    		foreach ($users as $user_id) {
	    		$this->_UserTaskDao->crear($task->getId(), $user_id);
    		}
    	}
    	return $task;
    }
    
    /**
     * Consigue todos los Tasks de un Project
     *
     * @return array
     */
    public function getAllByProjectId($project_id)
    {
    	return $this->_TaskDao->getAllByProjectId($project_id);
    }
    
    /**
     * Set status
     *
     * @param Integer $id
     * @param Array   $data
     *
     * @return Integer The number of rows updated
     */
    public function updateById($id, $data)
    {
    	return $this->_TaskDao->updateById($id, $data);
    }
    
    /**
     * Delete by id
     *
     * @param Integer $id
     *
     * @return Boolean
     */
    public function deleteById($id) 
    {
    	return $this->_TaskDao->deleteById($id);
    }
}