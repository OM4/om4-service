<?php

/**
 * Beaver Builder (Page Builder) modifications.
 */
class OM4_BeaverBuilder extends OM4_Plugin_Base {

	public function plugins_loaded() {
	    if ( defined( 'FL_BUILDER_VERSION' ) ) {
	        $this->filter( 'fl_builder_render_css', 100 );
        }
	}

    /**
     * When Beaver Builder generates it's CSS rules, use relative URLs (rather than full URLs)
     * so that background images are loaded via a CDN if one is being used.
     *
     * @param string $css Beaver Builder's generated CSS rules.
     * @return string
     */
	public function fl_builder_render_css( $css ) {
        return str_replace( home_url(), '', $css );
    }

}
