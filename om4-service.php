<?php
/*
Plugin Name: OM4 Service
Plugin URI: http://om4.com.au/wordpress-plugins/
Description: OM4 Service / Web Assist integration.
Version: 1.1-dev
Author: OM4
Author URI: http://om4.com.au/
Text Domain: om4-service
Git URI: https://github.com/OM4/om4-service
Git Branch: release
License: GPLv2
*/

/*

   Copyright 2012-2014 OM4 (email: info@om4.com.au    web: http://om4.com.au/)

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Main OM4 Service class.
 *
 * Initialises the necessary modules/classes.
 */
class OM4_Service {

	/**
	 * Refers to a single instance of this class
	 */
	private static $instance = null;

	public $dir = '';

	public $inc_dir = '';

	public $modules = array();

	/**
	 * Creates or returns an instance of this class
	 * @return OM4_Service A single instance of this class
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;

	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->dir = dirname(__FILE__);
		$this->inc_dir = $this->dir . '/inc';
	}

	/**
	 * Load the required modules
	 */
	public function initialise() {

		require( $this->inc_dir . '/OM4_Plugin_Base.php' );

		$this->load( 'OM4_Revisions.php' );
		$this->load( 'OM4_Search.php' );
		$this->load( 'OM4_Service_Desk.php' );

	}

	/**
	 * Load and instansisate a module (file).
	 *
	 * Each module should extend the OM4_Plugin_Base class
	 *
	 * @param string $file_name
	 */
	public function load( $file_name ) {
		require( $this->inc_dir . '/' . $file_name );
		$class_name = str_replace( '.php', '', $file_name );
		if ( class_exists( $class_name, false ) ) {
			$this->modules[] = new $class_name();
		}
	}

	/**
	 * Detects whether or not this website is running on WP Engine.
	 *
	 * @return bool
	 */
	public static function is_wp_engine() {
		return ( class_exists( 'WPE_API', false ) );
	}

	/**
	 * Flush all caches.
	 *
	 * This includes WP Engine caches or W3 Total Cache caches.
	 */
	public static function cache_flush() {

		if ( function_exists( 'w3tc_pgcache_flush' ) ) {

			// W3 Total Cache is active, so flush the page cache
			w3tc_pgcache_flush();

		} else if ( self::is_wp_engine() && class_exists( 'WpeCommon' ) ) {
			// Running on WP Engine, so flush their caches

			if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
				WpeCommon::purge_memcached();
			}
			if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
				WpeCommon::clear_maxcdn_cache();
			}
			if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
				WpeCommon::purge_varnish_cache();
			}

		}

	}

}

OM4_Service::instance()->initialise();