<?php
/**
 * A service to work with user's session.
 *
 * @category  JoniPhp
 * @package   JoniPhp_Service
 * @copyright 2010 Monits
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.joniphp.com/
 * @since     1.0.0
 */


/**
 * A service to work with user's session.
 *
 * @category  JoniPhp
 * @package   JoniPhp_Service
 * @author    Jonathan Borda <jonatahnmatiasborda@gmail.com>
 * @copyright 2010 JoniPhp
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.joniphp.com/
 * @since     1.0.0
 */
class Application_Service_Session
{
    protected static $_user = null;

    /**
     * Namespaces instances. Allow reusage, dealing with single instances.
     *
     * @var array
     */
    protected static $_namespaces = array();

    /**
     * Retrieves a session namespace by name.
     *
     * @param string $name The name of the session namespace to retrieve.
     *
     * @return Zend_Session_Namespace
     */
    protected static function _getNamespace($name = 'Default')
    {
        if (!isset(self::$_namespaces[$name])) {
            self::$_namespaces[$name] = new Zend_Session_Namespace($name, true);
        }

        return self::$_namespaces[$name];
    }

    /**
     * Logs a user in, initializing the session appropiately.
     *
     * @param Application_Model_Usuario $user       The user that just logged in.
     * @param boolean                   $rememberMe Whether to remember the user.
     *
     * @return void
     */
    public static function login(Application_Model_Usuario $user, $rememberMe = false)
    {
        /**
         * The user just changed privileges level,
         * regenerate session id to help prevent session hijacking.
         */
        Zend_Session::regenerateId();

        if ($rememberMe) {
            Zend_Session::rememberMe();
        }

        self::_getNamespace('user')->user = $user;
        self::_getNamespace('user')->rememberMe = $rememberMe;
    }

    /**
     * Logout the user, completely destroying the current session.
     * The session is completely destroyed, along with any rememberMe cookies.
     *
     * @return void
     */
    public static function logout()
    {
        Zend_Session::forgetMe();
        Zend_Session::destroy();
    }

    /**
     * Retrieves the current logged user.
     *
     * @return Application_Model_Usuario
     */
    public static function getUser()
    {
        return self::_getNamespace('user')->user;
    }

    /**
     * Retrieves the rememberMe
     *
     * @return boolean
     */
    public static function userWantsToBeRemembered()
    {
        return self::_getNamespace('user')->rememberMe;
    }
}