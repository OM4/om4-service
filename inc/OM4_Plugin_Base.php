<?php
/**
 * Base Class for OM4 plugins and/or mu-plugins:
 *
 * - Provides a shorter/simpler syntax for using hooks/filters.
 * - Makes it easy to override WordPress options.
 * - Makes it easy to override the output of WordPress dashboard screens.
 */
class OM4_Plugin_Base {

	public function __construct() {
		$this->hook( 'plugins_loaded' );
	}

	/**
	 * This function should be overridden in the extending class.
	 *
	 * Executed during the plugins_loaded hook.
	 */
	public function plugins_loaded() {

	}

	/**
	 * Hooks into the specified WordPress action/hook.
	 *
	 * Important: You must also implement your own function that matches the name of the hook.
	 * If the action/hook name contains hyphens, then your function name should use underscores instead.
	 *
	 * @param string $hook_name Hook/action name
	 * @param int $priority optional priority
	 * @param int $accepted_args optional number of accepted arguments
	 */
	public function hook( $hook_name, $priority = 10, $accepted_args = 1 ) {
		add_action( $hook_name, array( $this, str_replace( '-', '_', $hook_name ) ), $priority, $accepted_args );
	}

	/**
	 * Hooks into the specified WordPress filter.
	 *
	 * Important: You must also implement your own function that matches the name of the filter.
	 *
	 * @param string $filter_name Filter name
	 * @param int $priority optional priority
	 * @param int $accepted_args optional number of accepted arguments
	 */
	public function filter( $filter_name, $priority = 10, $accepted_args = 1 ) {
		add_filter( $filter_name, array( $this, $filter_name ), $priority, $accepted_args );
	}

	/**
	 * Override the HTML content/output on a WordPress dashboard screen.
	 *
	 * This should be used sparingly, because it uses output buffering.
	 *
	 * Important: You must also implement your own dashboard_screen_{screen_name}( $content ) function!
	 *
	 * @param string $screen_name The name of the WordPress dashboard screen to override. Should match the $page_hook variable in wp-admin/admin.php
	 *
	 * @throws Exception
	 */
	protected function override_dashboard_screen_content( $screen_name ) {
		$function_name_that_needs_implementing = 'dashboard_screen_' . $screen_name;
		if ( ! method_exists( $this, $function_name_that_needs_implementing ) ) {
			throw new Exception( get_class( $this ) . '::' . $function_name_that_needs_implementing . "() must exist if you would like to override the $screen_name dashboard screen." );
		}

		add_action( 'load-' . $screen_name, array( $this, 'init_dashboard_screen_override_' . $screen_name ) );
	}

	/**
	 * Override a WordPress option.
	 *
	 * This ensures that the user can never set the option's value to anything else.
	 *
	 * Important: You must also implement your own option_{option_name}( $value ) function!
	 *
	 * @param string $option_name WordPress option name
	 *
	 * @throws Exception
	 */
	protected function override_option( $option_name ) {
		$function_name_that_needs_implementing = 'option_' . $option_name;
		if ( ! method_exists( $this, $function_name_that_needs_implementing ) ) {
			throw new Exception( get_class( $this ) . '::' . $function_name_that_needs_implementing . "() must exist if you would like to override the $option_name option." );
		}

		add_action( 'pre_option_' . $option_name, array( $this, 'pre_option_' . $option_name ) );
	}

	/**
	 * Filter/Customise a WordPress option value.
	 *
	 * Important: You must also implement your own filter_option_{option_name}( $value ) function!
	 *
	 * @param string $option_name WordPress option name
	 *
	 * @throws Exception
	 */
	protected function filter_option( $option_name ) {
		$function_name_that_needs_implementing = 'filter_option_' . $option_name;
		if ( ! method_exists( $this, $function_name_that_needs_implementing ) ) {
			throw new Exception( get_class( $this ) . '::' . $function_name_that_needs_implementing . "() must exist if you would like to filter the $option_name option value." );
		}

		add_action( 'option_' . $option_name, array( $this, 'filter_option_' . $option_name ) );
	}

	/**
	 * Override the default value of a WordPress option.
	 *
	 * Important: You must also implement your own option_{option_name}( $value ) function!
	 *
	 * @param string $option_name WordPress option name
	 *
	 * @throws Exception
	 */
	protected function override_default_option( $option_name ) {
		$function_name_that_needs_implementing = 'default_option_' . $option_name;
		if ( ! method_exists( $this, $function_name_that_needs_implementing ) ) {
			throw new Exception( get_class( $this ) . '::' . $function_name_that_needs_implementing . "() must exist if you would like to override the default value of the $option_name option." );
		}

		add_action( 'default_option_' . $option_name, array( $this, 'default_option_' . $option_name ) );
	}

	/**
	 * Initialises output buffering on the specified WordPress dashboard screen.
	 *
	 * Used when overriding dashboard screen content/output.
	 *
	 * @param string $screen_name
	 */
	private function init_dashboard_screen_override( $screen_name ) {
		ob_start( array( $this, 'dashboard_screen_' . $screen_name ) );
	}


	/**
	 * Magic method handler.
	 *
	 * @param string $function_name
	 * @param array $args
	 */
	public function __call( $function_name, $args ) {

		if ( 0 === strpos( $function_name, 'init_dashboard_screen_override_' ) ) {

			// Dashboard Screen Overriding

			$this->init_dashboard_screen_override( str_replace( 'init_dashboard_screen_override_', '', $function_name ) );

		} elseif ( 0 === strpos( $function_name, 'pre_option_' ) ) {

			// Option Value Overriding

			$option_name      = str_replace( 'pre_option_', '', $function_name );
			$function_to_call = 'option_' . $option_name;
			return call_user_func_array( array( $this, $function_to_call ), $args );

		} elseif ( 0 === strpos( $function_name, 'default_option_' ) ) {

			// Default Option Value Overriding

			$option_name      = str_replace( 'default_option_', '', $function_name );
			$function_to_call = 'default_option_' . $option_name;
			return call_user_func_array( array( $this, $function_to_call ), $args );

		}

		// Do nothing for unknown method calling

	}

}
