<?php

/**
 * Flush/purge caches whenever specific actions/hooks occur
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

		// Allow other plugins to override the list of actions that should cause a cache flush
		$this->actions = apply_filters( 'om4_cache_clear_actions', $this->actions );

		foreach ( $this->actions as $action ) {
			add_action( $action , 'OM4_Service::cache_flush' );
		}

	}

}
