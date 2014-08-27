<?php
/**
 * Status Service.
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
 * Status Service.
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
class Application_Service_Status
{

    /**
     * @var Application_Dao_Status
     */
    private $_StatusDao;

    /**
     * @var Application_Dao_UserStatus
     */
    private $_UserStatusDao;
    
    public function __construct(Application_Dao_Status $StatusDao)
    {
        $this->_StatusDao = $StatusDao;
    }

    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del Status.
     * 
     * @return Application_Model_Status
     */
    public function getById($id)
    {
        return $this->_StatusDao->getById($id);
    }
    
    /**
     * Consigue todos los Statuss
     * 
     * @return array
     */
	public function fetchAll()
    {
    	return $this->_StatusDao->fetchAll();
    }

    /**
     * Crea un Status
     *
     * @param String $descripcion
     * 
     * @return Application_Model_Status
     */
    public function crear($descripcion)
    {
    	return $this->_StatusDao->crear($descripcion);
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
    	return $this->_StatusDao->updateById($id, $data);
    }
    
    /**
     * Get by Description
     *
     * @param String $descripcion
     *
     * @return Application_Model_Status
     */
    public function getByDescripcion($descripcion)
    {
    	return $this->_StatusDao->getByDescripcion($descripcion);
    }
}