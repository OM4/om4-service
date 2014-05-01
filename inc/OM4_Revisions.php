<?php

/**
 * Automatically overrides WordPress' revisions limit to 5 per post/page.
 */
class OM4_Revisions extends OM4_Plugin_Base {

	private $number_of_revisions_to_keep = 5;

	public function plugins_loaded() {
		// Let other plugins/themes override the default
		$this->number_of_revisions_to_keep = apply_filters( 'om4_revisions_to_keep', $this->number_of_revisions_to_keep );

		$this->filter( 'wp_revisions_to_keep' );
	}

	public function wp_revisions_to_keep( $number ) {
		return $this->number_of_revisions_to_keep;
	}

}
