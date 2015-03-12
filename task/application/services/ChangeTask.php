<?php
/**
 * ChangeTask Service.
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
 * ChangeTask Service.
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
class Application_Service_ChangeTask
{

    /**
     * @var Application_Dao_ChangeTask
     */
    private $_ChangeTaskDao;

    public function __construct(Application_Dao_ChangeTask $ChangeTaskDao)
    {
        $this->_ChangeTaskDao = $ChangeTaskDao;
    }

    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del ChangeTask.
     * 
     * @return Application_Model_ChangeTask
     */
    public function getById($id)
    {
        return $this->_ChangeTaskDao->getById($id);
    }
    
    /**
     * Consigue todos los ChangeTasks
     * 
     * @return array
     */
	public function fetchAll()
    {
    	return $this->_ChangeTaskDao->fetchAll();
    }

    /**
     * Consigue todos los ChangeTasks de un usuario
     *
     * @return array
     */
    public function getAllByUserId($user_id)
    {
    	return $this->_ChangeTaskDao->getAllByUserId($user_id);
    }
    
    /**
     * Consigue todos los ChangeTasks segun el filtro
     *
     * @return array
     */
    public function getAllByFilters($project_id = null, $status_id = null, $user_id = null)
    {
    	return $this->_ChangeTaskDao->getAllByFilters($project_id, $status_id, $user_id);
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
    	return $this->_ChangeTaskDao->deleteById($id);
    }
    
    /**
     * Consigue todos los ChangeTasks de una tarea
     *
     * @return array
     */
    public function getAllByTask($task_id)
    {
    	return $this->_ChangeTaskDao->getAllByTask($task_id);
    }
    
    /**
     * Get Last Modified by User
     *
     * @param string $user_id
     *
     * @return Date
     */
    public function getLastModifiedByUser($user_id = null) {
    	
    	$last = $this->_ChangeTaskDao->getLastModifiedByUser($user_id);

    	if ($last) {
    		
    		$start = new DateTime($last);
    		$now = new DateTime();
    		
    		$diff = $start->diff($now);
    		
			if ($year = $diff->format('%y')) {
				return '+ ' . $year . ' años';
			} elseif ($month = $diff->format('%m')) {
					return '+ ' . $month . ' meses';
			} elseif ($days = $diff->format('%d')) {
					return '+ ' . $days . ' días';
			}
	    	return 'Hoy';
    	} else {
    		return 'Nunca';
    	}
    }
    
    /**
     * Get unrevised
     *
     * @return Array
     */
    public function getUnrevised() {
    	return $this->_ChangeTaskDao->getUnrevised();
    }
    
    /**
     * Set status
     *
     * @param Integer $id
     * @param Array $data
     *
     * @return Integer The number of rows updated
     */
    public function updateById($id, $data) {
    	return $this->_ChangeTaskDao->updateById($id, $data);
    }
    
    /**
     * Make to revised
     * 
     * @param Integer $id
     */
    public function make_revised($id) {
    	return $this->updateById($id, array('revisado' => true));
    }
}