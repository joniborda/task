<?php
/**
 * Job Service.
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
 * Job Service.
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
class Application_Service_Persona
{

    /**
     * @var Application_Dao_Persona
     */
    private $_personaDao;

    public function __construct(Application_Dao_Persona $personaDao)
    {
        $this->_personaDao = $personaDao;
    }

    /**
     * Consigue las personas por de un trabajo.
     * 
     * @param Integer $trabajo_id Id del trabajo.
     * 
     * @return array
     */
    public function getByTrabajoId($trabajo_id)
    {
        return $this->_personaDao->getByTrabajoId($trabajo_id);
    }

}