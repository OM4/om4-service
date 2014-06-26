<?php


/**
 * Header (<head>) common modifications
 */
class OM4_Header extends OM4_Plugin_Base {

	public function plugins_loaded() {

		/*
		 * Remove the rel=prev and rel=next links from the <head> section.
		 * Otherwise hidden/private/admin pages can be found easily.
		 * (This is already done for sites using Yoast's SEO plugin, but it should be done on all sites regardless).
		 */
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
	}

}