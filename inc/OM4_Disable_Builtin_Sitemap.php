<?php

/**
 * Disable Default WordPress sitemap.
 * Introduced in WP 5.5.0.
 */
class OM4_Disable_Builtin_Sitemap extends OM4_Plugin_Base
{
	public function plugins_loaded()
	{
		add_filter('wp_sitemaps_enabled', '__return_false');
	}
}
