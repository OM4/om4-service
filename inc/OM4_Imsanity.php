<?php

/**
 * If the Imsanity plugin is active, ensure the maximum dimensions are always set to 2560.
 *
 * If a site needs to upload an image larger than this, then the Imsanity plugin should be deactivated.
 */
class OM4_Imsanity extends OM4_Plugin_Base {

	private $options_to_override = array(
		'imsanity_max_width',
		'imsanity_max_height',
		'imsanity_max_width_library',
		'imsanity_max_height_library',
		'imsanity_max_width_other',
		'imsanity_max_height_other'
	);

	public function plugins_loaded() {
		foreach ( $this->options_to_override as $option_name ) {
			add_filter( 'pre_option_' . $option_name, array( $this, 'override_option' ) );
		}
	}

	public function override_option( $value ) {
		return 2560;
	}

}
