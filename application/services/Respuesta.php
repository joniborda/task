<?php
/**
 * Respuesta Service.
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
 * Respuesta Service.
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
class Application_Service_Respuesta
{

    /**
     * @var Application_Dao_Respuesta
     */
    private $_respuestaDao;

    public function __construct(Application_Dao_Respuesta $respuestaDao)
    {
        $this->_respuestaDao = $respuestaDao;
    }

    /**
     * Respuesta por id.
     * 
     * @param Integer $id Id del respuesta.
     * 
     * @return Application_Model_Respuesta
     */
    public function getById($id)
    {
        return $this->_respuestaDao->getById($id);
    }
    
    /**
     * Consigue todos los comentarios
     * 
     * @return array
     */
	public function fetchAll()
    {
    	return $this->_respuestaDao->fetchAll();
    }

    /**
     * Consigue todas las respuesta de un usuario
     *
     * @return array
     */
    public function getAllByUserId($user_id)
    {
    	return $this->_respuestaDao->getAllByUserId($user_id);
    }
    
    /**
     * Crea un comentario
     *
     * @param Integer $id_usuario
     * @param String $id_comentario
     * @param String $comentario
     * @param String $fecha
     * 
     * @return Application_Model_Comentario
     */
    public function crear($id_usuario, $id_comentario, $comentario, $fecha)
    {
    	return $this->_respuestaDao->crear($id_usuario, $id_comentario, $comentario, $fecha);
    }
    
    /**
     * Consigue todas las respuesta de un comentario
     * 
     * @param Integer $comentario_id
     *
     * @return array
     */
    public function getAllByCommentId($comentario_id)
    {
    	return $this->_respuestaDao->getAllByCommentId($comentario_id);
    }
}