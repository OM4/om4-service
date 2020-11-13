<?php
/*
Plugin Name: OM4 Service
Plugin URI: https://om4.com.au/plugins/
Description: Adds the OM4 Service orange button to the WordPress dashboard. Also improves default WordPress functionality.
Version: 1.6.3-dev
Author: OM4
Author URI: https://om4.com.au/plugins/
Text Domain: om4-service
Git URI: https://github.com/OM4/om4-service
Git Branch: release
License: GPLv2
*/

/*
Copyright 2012-2020 OM4 (email: plugins@om4.com.au    web: https://om4.com.au/)

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
	 *
	 * @return OM4_Service A single instance of this class
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;

	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->dir     = dirname( __FILE__ );
		$this->inc_dir = $this->dir . '/inc';
	}

	/**
	 * Load the required modules
	 */
	public function initialise() {

		if ( ! class_exists( 'OM4_Plugin_Base' ) ) {
			require $this->inc_dir . '/OM4_Plugin_Base.php';
		}

		$this->load( 'OM4_BeaverBuilder.php' );
		$this->load( 'OM4_Caches.php' );
		$this->load( 'OM4_Comments.php' );
		$this->load( 'OM4_Disable_Builtin_Sitemap.php' );
		$this->load( 'OM4_Header.php' );
		$this->load( 'OM4_Imsanity.php' );
		$this->load( 'OM4_Menus.php' );
		$this->load( 'OM4_Revisions.php' );
		$this->load( 'OM4_Exclusions.php' );
		$this->load( 'OM4_Service_Desk.php' );
		$this->load( 'OM4_WPE.php' );

	}

	/**
	 * Load and instansisate a module (file).
	 *
	 * Each module should extend the OM4_Plugin_Base class
	 *
	 * @param string $file_name
	 */
	public function load( $file_name ) {
		require $this->inc_dir . '/' . $file_name;
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
		return ( class_exists( 'WpeCommon', false ) );
	}

	/**
	 * Check whether the plugin is active.
	 *
	 * @param string $plugin Base plugin path from plugins directory
	 *
	 * @return bool
	 */
	public static function is_plugin_active( $plugin ) {
		if ( ! function_exists( ( 'is_plugin_active' ) ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		return is_plugin_active( $plugin );
	}

	/**
	 * Flush all caches.
	 *
	 * This includes WP Engine caches, Beaver Builder cache and the WP-Rocket Cache.
	 */
	public static function cache_flush() {

		if ( self::is_wp_engine() ) {
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

		// Clear Beaver Builder caches
		if ( class_exists( 'FLBuilderModel' ) && method_exists( 'FLBuilderModel', 'delete_asset_cache_for_all_posts' ) ) {
			FLBuilderModel::delete_asset_cache_for_all_posts();
		}

		// Clear Beaver Builder Theme cache
		if ( class_exists( 'FLCustomizer' ) && method_exists( 'FLCustomizer', 'clear_all_css_cache' ) ) {
			FLCustomizer::clear_all_css_cache();
		}

		// WP Rocket caches
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
		if ( function_exists( 'rocket_clean_minify' ) ) {
			rocket_clean_minify();
		}
		if ( function_exists( 'rocket_clean_cache_busting' ) ) {
			rocket_clean_cache_busting();
		}
		if ( function_exists( 'rocket_generate_advanced_cache_file' ) ) {
			rocket_generate_advanced_cache_file();
		}

	}

}

OM4_Service::instance()->initialise();
