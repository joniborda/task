<?php
/**
 * Project Service.
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
 * Project Service.
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
class Application_Service_Project
{

    /**
     * @var Application_Dao_Project
     */
    private $_ProjectDao;
    
    /**
     * @var Application_Dao_Task
     */
    private $_TaskDao;

    public function __construct(Application_Dao_Project $ProjectDao, Application_Dao_Task $TaskDao)
    {
        $this->_ProjectDao = $ProjectDao;
        $this->_TaskDao = $TaskDao;
    }

    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del Project.
     * 
     * @return Application_Model_Project
     */
    public function getById($id)
    {
        return $this->_ProjectDao->getById($id);
    }
    
    /**
     * Consigue todos los Projects
     * 
     * @return array
     */
	public function fetchAll()
    {
    	return $this->_ProjectDao->fetchAll();
    }

    /**
     * Consigue todos los Projects de un usuario
     *
     * @return array
     */
    public function getAllByUserId($user_id)
    {
    	return $this->_ProjectDao->getAllByUserId($user_id);
    }
    
    /**
     * Crea un Project
     *
     * @param String $name
     * 
     * @return Application_Model_Project
     */
    public function crear($name, $site_url = null, $description = null)
    {
    	return $this->_ProjectDao->crear($name, $site_url, $description);
    }
    
    /**
     * Update a Project
     * 
     * @param Integer $id
     * @param String  $name
     */
    public function update($id, $name, $site_url = null, $description = null)
    {
    	$this->_ProjectDao->update($id, $name, $site_url, $description);
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
		return $this->_ProjectDao->deleteById($id);
    }
}