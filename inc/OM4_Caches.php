<?php

/**
 * Whenever the following hooks/actions occur, ensure the WP Engine, Beaver Builder and WP-Rocket caches are cleared.
 */
class OM4_Caches extends OM4_Plugin_Base {

	public $actions = array();

	public function plugins_loaded() {

		// OM4 Custom CSS plugin
		// Necessary because cached pages would otherwise refer to a custom-xyz.css file that has just been deleted
		$this->actions[] = 'om4_custom_css_saved';

		// OM4 Custom Header/Footer Code plugin
		// Necessary because cached pages would otherwise refer to the previous header/footer script/code.
		$this->actions[] = 'om4_header_footer_code_saved';

		// Members Only plugin
		// Whenever members only settings are changed
		$this->actions[] = 'update_option_members_only_options';

		// Whenever WordPress updates any plugins/themes/core/languages.
		$this->actions[] = 'upgrader_process_complete';

		// Allow other plugins to override the list of actions that should cause a cache flush
		$this->actions = apply_filters( 'om4_cache_clear_actions', $this->actions );

		foreach ( $this->actions as $action ) {
			add_action( $action , 'OM4_Service::cache_flush', 10, 0 );
		}

	}

}
