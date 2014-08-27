<?php
/**
 * Comentario Service.
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
 * Comentario Service.
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
class Application_Service_Comentario
{

    /**
     * @var Application_Dao_Comentario
     */
    private $_comentarioDao;

    public function __construct(Application_Dao_Comentario $comentarioDao)
    {
        $this->_comentarioDao = $comentarioDao;
    }

    /**
     * Consigue por id.
     * 
     * @param Integer $id Id del comentario.
     * 
     * @return Application_Model_Comentario
     */
    public function getById($id)
    {
        return $this->_comentarioDao->getById($id);
    }
    
    /**
     * Consigue todos los comentarios
     * 
     * @return array
     */
	public function fetchAll()
    {
    	return $this->_comentarioDao->fetchAll();
    }

    /**
     * Consigue todos los comentarios de un usuario
     *
     * @return array
     */
    public function getAllByUserId($user_id)
    {
    	return $this->_comentarioDao->getAllByUserId($user_id);
    }
    
    /**
     * Crea un comentario
     *
     * @param Integer $id_usuario
     * @param String $comentario
     * @param String $fecha
     * 
     * @return Application_Model_Comentario
     */
    public function crear($id_usuario, $comentario, $fecha)
    {
    	return $this->_comentarioDao->crear($id_usuario, $comentario, $fecha);
    }
}