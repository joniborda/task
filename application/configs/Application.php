<?php
/**
 * Application Config class for Feria de ciencias.
 *
 * @category  Feria de ciencias
 * @package   Feria_ciencias_Config
 * @copyright 2012 Mapaeduactivo
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.mapaeducativo.edu.ar/
 * @since     1.0.0
 */

/**
 * Application Config class for ShModa.
 *
 * @category  Feria de ciencias
 * @package   Feria_ciencias_Config
 * @author    JBorda <jonathanmatiasborda@gmail.com>
 * @copyright 2012 Mapaeduactivo
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.mapaeducativo.edu.ar/
 * @since     1.0.0
 */
class Application_Config_Application {
	private static $_config;

	const APPLICATION_NAME = 'applicationName';
	const EXT_IMAGE_PATH = 'extImagePath';
	const URL_EXT_IMAGE = 'urlExtImage';
	const PROFILE_PATH = 'profilePath';

	/**
	 * Sets the config.
	 *
	 * @param array $config An associative array with config keys and values.
	 *
	 * @return void
	 */
	public static function setConfig(array $config) {
		self::$_config = $config;
	}

	/**
	 * Retriees the application name.
	 *
	 * @return string
	 */
	public static function getApplicationName() {
		return self::$_config[self::APPLICATION_NAME];
	}

	/**
	 * Retriees the image path
	 *
	 * @return string
	 */
	public static function getExtImagePath() {
		return self::$_config[self::EXT_IMAGE_PATH];
	}

	/**
	 * Retriees the image url
	 *
	 * @return string
	 */
	public static function getUrlExtImage() {
		return self::$_config[self::URL_EXT_IMAGE];
	}

	/**
	 * Retriees the profile path
	 *
	 * @return string
	 */
	public static function getProfilePath() {
		return self::$_config[self::PROFILE_PATH];
	}
}