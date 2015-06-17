<?php
/**
 * Revision Service.
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
 * Revision Service.
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
class Application_Service_Revision
{

    /**
     * @var Application_Dao_Revision
     */
    private $_RevisionDao;

    /**
     * @var Application_Dao_UserRevision
     */
    private $_UserRevisionDao;
    
    public function __construct(Application_Dao_Revision $RevisionDao)
    {
        $this->_RevisionDao = $RevisionDao;
    }

    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del Revision.
     * 
     * @return Application_Model_Revision
     */
    public function getById($id)
    {
        return $this->_RevisionDao->getById($id);
    }
    
    /**
     * Consigue todos los Revisions
     * 
     * @return array
     */
	public function fetchAll()
    {
    	return $this->_RevisionDao->fetchAll();
    }

    /**
     * Crea un Revision
     * 
     * @param unknown $revision
     * @param unknown $author
     * @param unknown $message
     * @param unknown $date
     * 
     * @return Application_Model_Revision
     */
    public function crear($revision, $author, $message, $date)
    {
    	return $this->_RevisionDao->crear($revision, $author, $message, $date);
    }
    
    /**
     * Set revision
     *
     * @param Integer $id
     * @param Array   $data
     *
     * @return Integer The number of rows updated
     */
    public function updateById($id, $data)
    {
    	return $this->_RevisionDao->updateById($id, $data);
    }
    
    /**
     * Get last insert id
     * 
     * @return Application_Model_Revision
     */
    public function getLastId()
    {
    	return $this->_RevisionDao->getLastId();
    }
}