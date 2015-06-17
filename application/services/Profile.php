<?php
/**
 * Profile Service.
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
 * Profile Service.
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
class Application_Service_Profile
{

    /**
     * @var Application_Dao_Profile
     */
    private $_ProfileDao;
    
    public function __construct(Application_Dao_Profile $ProfileDao)
    {
        $this->_ProfileDao = $ProfileDao;
    }

    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del Profile.
     * 
     * @return Application_Model_Profile
     */
    public function getById($id)
    {
        return $this->_ProfileDao->getById($id);
    }
    
    /**
     * Consigue todos los Profiles
     * 
     * @return array
     */
	public function fetchAll()
    {
    	return $this->_ProfileDao->fetchAll();
    }
    
    /**
     * Consigue todos los Profiles para una lista
     *
     * @return array
     */
    public function listAll()
    {
    	$profiles = $this->_ProfileDao->fetchAll();
    	$ret = array();
    	if (!empty($profiles)) {
    		foreach ($profiles as $profile) {
	    		$ret[$profile->getId()] = $profile->getDescription();
	    	}
    	}
    	return $ret;
    }

    /**
     * Consigue todos los Profiles de un usuario
     *
     * @return array
     */
    public function getAllByUserId($user_id)
    {
    	return $this->_ProfileDao->getAllByUserId($user_id);
    }
    
    /**
     * Crea un Profile
     *
     * @param String $name
     * 
     * @return Application_Model_Profile
     */
    public function crear($name)
    {
    	return $this->_ProfileDao->crear($name);
    }
    
    /**
     * Update a Profile
     * 
     * @param Integer $id
     * @param String  $name
     */
    public function update($id, $name)
    {
    	$this->_ProfileDao->update($id, $name);
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
		return $this->_ProfileDao->deleteById($id);
    }
}