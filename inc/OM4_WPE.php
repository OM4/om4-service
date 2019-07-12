<?php

/**
 * Check WPE staging/development environment,
 * and disables incompatible plugins.
 */
class OM4_WPE extends OM4_Plugin_Base {

	/**
	 * WPE staging/development incompatible Plugins
	 *
	 * @var array
	 */
	protected $watched_plugins = array(
		'cdn-enabler/cdn-enabler.php',
		'instantsearch-for-woocommerce/instantsearch-for-woocommerce.php',
	);

	/**
	 * Active incompatible plugins
	 *
	 * @var array
	 */
	protected $active_plugins = array();

	/**
	 * Executed during the plugins_loaded hook
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		// Do nothing if not in admin area.
		if ( ! is_admin() ) {
			return;
		}
		// Check WPE staging via home_url().
		$not_production       = false !== strpos( home_url(), '.wpengine.com' );
		$this->active_plugins = $this->check_active_plugins();
		// Display message for active plugins.
		if ( $not_production && ( ! empty( $this->active_plugins ) ) ) {
			$this->hook( 'admin_notices' );
		}
	}

	/**
	 * Called by admin_notices hook
	 *
	 * @return void
	 */
	public function admin_notices() {
		foreach ( $this->active_plugins as $plugin_file => $plugin_name ) {
			$lead   = sprintf( __( 'The %s plugin is not compatible with this WP Engine environment.', 'om4-service' ), $plugin_name );
			$url    = wp_nonce_url( self_admin_url( 'plugins.php?action=deactivate&plugin=' . $plugin_file . '&plugin_status=all' ), 'deactivate-plugin_' . $plugin_file );
			$action = sprintf( __( 'Deactivate %s' ), $plugin_name );
			printf(
				'<div class="notice notice-error">
					<p><b>%1$s</b></p>
					<p><a href="%2$s" class="button button-secondary">%3$s</a></p>
				</div>',
				esc_html( $lead ),
				esc_url( $url ),
				esc_html( $action )
			);
		}
	}

	/**
	 * Return array of active plugins from watched
	 *
	 * @return array
	 */
	protected function check_active_plugins() {
		$active = array();
		foreach ( $this->watched_plugins as $plugin_file ) {
			if ( is_plugin_active( $plugin_file ) ) {
				$active[ $plugin_file ] = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file, false )['Name'];
			}
		}
		return $active;
	}
}
