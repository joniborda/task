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
    
    /**
     * @var Application_Dao_ChangeTask
     */
    private $_ChangeTaskDao;
    
    public function __construct(Application_Dao_Task $TaskDao, Application_Dao_UserTask $UserTaskDao,
    		Application_Dao_ChangeTask $ChangeTaskDao)
    {
        $this->_TaskDao = $TaskDao;
        $this->_UserTaskDao = $UserTaskDao;
        $this->_ChangeTaskDao = $ChangeTaskDao;
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
     * Consigue todos los Tasks segun el filtro
     *
     * @return array
     */
    public function getAllByFilters($project_id = null, $status_id = null, $user_id = null, $title = null)
    {
    	return $this->_TaskDao->getAllByFilters($project_id, $status_id, $user_id, $title);
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
    	$task = $this->_TaskDao->getById($id);
    	
    	if ($task) {
    		
    		$changes = array();
    		foreach ($data as $key => $value) {
    			if ($data[$key] != $task->__get($key)) {
    				if ($key == 'user_id') {
    					$changes[$key] = $data[$key];
    					continue;
    				}
    				
    				$changes[$key] =  $task->__get($key);
    			}
    		}
    		
	    	$ret = $this->_TaskDao->updateById($id, $data);
	    	
	    	if (!empty($changes)) {
	    		$changes['task_id'] = $id;
	    		$session_user = Application_Service_Locator::getSessionService()->getUser();
	    		
	    		if ($session_user) {
	    			$changes['user_id'] = $session_user->getId();
	    		}
	    		$this->_ChangeTaskDao->crear($changes);
	    	}
	    	
	    	return $ret;
    	}
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
    
    /**
     * Consigue la cantidad de tareas abiertas
     *
     * @return Integer
     */
    public function getCountOpenned($project_id = null)
    {
    	return $this->_TaskDao->getCountOpenned($project_id);
    }
    
    /**
     * Upload image to show in task view
     * 
     * @param unknown $id
     * @param unknown $file
     * @throws Zend_File_Transfer_Exception
     * @return boolean
     */
    public function uploadImage($id, $file) {
    	if ($id == null) {
    		throw new Zend_File_Transfer_Exception('El id es nulo');
    	}
    	
    	if (empty($file)) {
    		throw new Zend_File_Transfer_Exception('Falta el archivo');
    	}
    	
    	$extension = strtolower(pathinfo($file['file']['name'][0],PATHINFO_EXTENSION));
    	
    	if($extension != "jpg" && $extension != "png" && $extension != "jpeg"
    			&& $extension != "gif" ) {
    		throw new Zend_File_Transfer_Exception('La extension ' . $extension . ' no es permitida');
    	}
    	
    	$path = Application_Config_Application::getExtImagePath () . '/task/' . $id. '/';

    	if (!is_dir($path)) {
	    	mkdir($path);
    	}
    	
    	$new_name = $id . '.'  . $extension;
    	
    	if (!move_uploaded_file($_FILES["file"]["tmp_name"][0], $path . $new_name)) {
    		throw new Zend_File_Transfer_Exception('No se pudo copiar');
    	}
    	return true;
    }
    
    /**
     * Return array with images
     * 
     * @param Application_Model_Task $task
     * 
     * @return Array
     */
    public function getImages(Application_Model_Task $task) {
    	
    	$dir = Application_Config_Application::getExtImagePath () . '/task/' . $task->getId () . '/';
    	
	    if (is_dir($dir)) {
		    if ($dh = opendir($dir)) {
		    	$images = array();
		        while (($file = readdir($dh)) !== false) {
		            if (filetype($dir . $file) == 'file') {
		            	$images[] = Application_Config_Application::getUrlExtImage() . '/task/' . $task->getId() . '/' . $file;
		            }
		        }
		        closedir($dh);
		        return $images;
		    }
		}    	
    	return array();
    }
}