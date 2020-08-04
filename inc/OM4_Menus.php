<?php

/**
 * WordPress Nav Menus
 */
class OM4_Menus extends OM4_Plugin_Base {

	public $actions = array();

	public function plugins_loaded() {

		$this->filter( 'wp_nav_menu_args' );

	}

	/**
	 * Automatically wrap WordPress 3+ nav menu items in a <span> tag.
	 * This will apply anywhere a nav menu is output, including:
	 * - 'Custom Menu' sidebar widgets
	 * - wp_nav_menu() uses
	 * - [show-menu] shortcode
	 *
	 * Executed by the 'wp_nav_menu_args' filter
	 *
	 * @param $args Array of wp_nav_menu() arguments
	 *
	 * @return array
	 */
	public function wp_nav_menu_args( $args ) {
		if ( empty( $args['link_before'] ) && empty( $args['link_after'] ) ) {
			$args['link_before'] = '<span>';
			$args['link_after']  = '</span>';
		}
		return $args;
	}

}
