<?php

/**
 * Adds an "OM4 Service" button to the WordPress toolbar/menu
 */
class OM4_Service_Desk extends OM4_Plugin_Base {

	public function plugins_loaded() {

		$this->hook( 'init' );

		// Only for Authors (and above)
		if ( current_user_can('edit_posts') && apply_filters('om4_wordpress_dashboard_om4_support', true) ) {

			// Display OM4 support desk button in WP Admin toolbar (both in the dashboard and on the website front end)
			add_action( 'admin_bar_menu', array( __CLASS__ , 'admin_bar_menu'), 999 ) ;

			// Don't display support desk feedback button on iframe/popup screens
			if (! defined('IFRAME_REQUEST') && ! defined('DOING_AJAX') ) {
				// Display in WordPress dashboard
				add_action( 'admin_print_footer_scripts', array( __CLASS__ , 'admin_print_footer_scripts') ) ;
				// Display when viewing the WordPress website while logged in
				add_action( 'wp_footer', array( __CLASS__ , 'admin_print_footer_scripts') ) ;
			}

		}
	}

	public function init() {
		if ( ! self::website_guide_page_id( false ) )
			add_action( 'save_post', array( __CLASS__, 'save_post' ), 10, 2 );
	}

	/**
	 * Customise the WordPress 3.3 toolbar (admin bar).
	 * Adds an orange "OM4 Service" menu.
	 * Customises the items in the W (WordPres) menu.
	 *
	 * Ref: http://wpdevel.wordpress.com/2011/12/07/admin-bar-api-changes-in-3-3/
	 *
	 * Executed by the 'admin_bar_menu' hook.
	 *
	 * @param $wp_admin_bar
	 */
	public static function admin_bar_menu($wp_admin_bar) {

		// Google Analytics Link Tags
		// Ref: https://support.google.com/urchin/answer/2633614?hl=en
		$domain_name = str_replace( array('http://', 'https://'), '', site_url() );
		$utm_variables = "?utm_source={$domain_name}&utm_medium=om4-service-button&utm_campaign=";

		$wp_admin_bar->add_menu( array(
				'parent' => 'top-secondary',
				'id'     => 'om4-service',
				'title'  => 'OM4 Service',
				'href'   => '#'
			)
		);

		// Add a link to the Website Guide page (if it exists)
		$guide_page_url = self::website_guide_page_url();
		if ( !empty( $guide_page_url ) ) {
			$wp_admin_bar->add_node( array(
					'id' => 'om4-service-website-guide',
					'title' => 'Website Guide',
					'href' => $guide_page_url,
					'parent' => 'om4-service',
					'meta'   => array(
						'target' => '_blank'
					)
				)
			);
		}

		if ( class_exists( 'Vum' ) ) {
			// Video User Manuals is activated.
			$wp_admin_bar->add_node( array(
					'id' => 'om4-service-videos',
					'title' => 'How To Videos',
					'href' => admin_url( 'admin.php?page=video-user-manuals/plugin.php' ),
					'parent' => 'om4-service'
				)
			);
		}

		$wp_admin_bar->add_node( array(
				'id' => 'om4-service-articles',
				'title' => 'How To Articles',
				'href' => "http://my.om4.com.au/knowledgebase.php{$utm_variables}how-to-articles",
				'parent' => 'om4-service',
				'meta'   => array(
					'target' => '_blank'
				)
			)
		);

		$wp_admin_bar->add_node( array(
				'id' => 'om4-service-ask-question',
				'title' => 'Ask a Question',
				'href' => "https://my.om4.com.au/submitticket.php{$utm_variables}ask-question",
				'parent' => 'om4-service',
				'meta'   => array(
					'target' => '_blank'
				)
			)
		);

		$wp_admin_bar->add_node( array(
				'id' => 'om4-service-service',
				'title' => 'Request a Service',
				'href' => "http://om4.com.au/services/request/{$utm_variables}request-service",
				'parent' => 'om4-service',
				'meta'   => array(
					'target' => '_blank'
				)
			)
		);

		$wp_admin_bar->add_node( array(
				'id' => 'om4-service-education',
				'title' => 'Request Education',
				'href' => "http://om4.com.au/services/education/{$utm_variables}request-education",
				'parent' => 'om4-service',
				'meta'   => array(
					'target' => '_blank'
				)
			)
		);

		$wp_admin_bar->add_node( array(
				'id' => 'om4-service-policy',
				'title' => 'Service Policy',
				'href' => "http://my.om4.com.au/knowledgebase/225/Web-Assist-Policy.html{$utm_variables}service-policy",
				'parent' => 'om4-service',
				'meta'   => array(
					'target' => '_blank'
				)
			)
		);


		// The default WordPress menu items
		$default_wordpress_menu_items = array (
			'wporg' => false,
			'support-forums' => true,
			'documentation' => true,
			'feedback' => true
		);

		foreach ( $default_wordpress_menu_items as $node_id => $prepend_wordpress_org_to_title ) {
			$node = $wp_admin_bar->get_node($node_id);
			$wp_admin_bar->remove_node($node_id);
			if ( $prepend_wordpress_org_to_title )
				$node->title = 'WordPress.org ' . $node->title;
			$wp_admin_bar->add_node($node);
		}
	}

	/**
	 * Obtain/set the page ID of this website's Website Guide page.
	 *
	 *
	 * Note: this data is cached for 7 days to help with performance.
	 *
	 * @param bool $update_if_not_cached Whether or not to attempt to search for the page if it isn't cached
	 * @return int Page ID of existing page, or 0 if no Website Guide page exists.
	 */
	public static function website_guide_page_id( $update_if_not_cached = true ) {
		$guide_page_id = get_transient( 'website_guide_page_id' );
		if ( false === $guide_page_id && $update_if_not_cached ) {
			// Data not in cache

			$urls_to_try = array(
				'/admin/guide/', // Latest URL format for guide page
				'/admin/styling/', // Older sites use this URL
				'/admin/style-guide/' // Some old sites may even use this URL
			);
			foreach ( $urls_to_try as $url ) {
				$page_id = url_to_postid( $url );
				if ( $page_id > 0 ) {
					$guide_page_id = $page_id;
					break;
				}
			}
			if ( ! $guide_page_id ) {
				// No page found
				// Cache a zero value so we don't keep re-checking on every page load
				$guide_page_id = 0;
			}
			self::set_website_guide_page_id( $guide_page_id );
		}
		return intval( $guide_page_id );
	}

	/**
	 * Set or clear the page ID that corresponds to this website's Website guide page.
	 *
	 * @param int|false $page_id Page ID to set it to, or false if the stored data should be cleared
	 *
	 * @return bool
	 */
	public static function set_website_guide_page_id( $page_id ) {
		if ( false === $page_id ) {
			// Clear the existing stored page ID
			delete_transient( 'website_guide_page_id' );
		} else {
			// Save the new page ID
			set_transient( 'website_guide_page_id', $page_id, WEEK_IN_SECONDS );
		}

	}

	/**
	 * If we don't currently have a guide page ID saved, attempt to find one whenever a WordPress page is saved.
	 *
	 * Executed by the save_post hook if we don't currently have a guide page ID saved.
	 *
	 * @param $post_id int
	 * @param $post WP_Post
	 */
	public static function save_post( $post_id, $post ) {
		if ( 'page' === get_post_type( $post ) ) {
			self::set_website_guide_page_id( false );
			self::website_guide_page_id();
		}
	}

	/**
	 * Obtain the URL to this website's Website Guide page.
	 *
	 * @return string URL, or empty string if there is no guide page
	 */
	public static function website_guide_page_url() {
		$guide_page_id = self::website_guide_page_id();
		if ( $guide_page_id > 0 ) {
			$guide_page_url = get_permalink( $guide_page_id );
			if ( !empty( $guide_page_url ) )
				return $guide_page_url;
		}
		return '';
	}

	/**
	 * Custom CSS rules to style the menu
	 */
	public static function admin_print_footer_scripts() {
		?>
	<style type="text/css">
		<!--

		/* Support button in Admin Toolbar: Make it orange so it stands out */
		#wpadminbar #wp-admin-bar-om4-service,
		#wpadminbar #wp-admin-bar-om4-service * {
			background-color: #e95000 !important;
			background-image: none !important;
			border: none;
		}
		#wpadminbar #wp-admin-bar-om4-service div, #wpadminbar #wp-admin-bar-om4-service a {
			color: #ffffff !important;
			text-shadow: none;
		}
		-->
	</style>
	<?php
	}
}
