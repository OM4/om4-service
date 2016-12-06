<?php
/**
 * Exclude admin pages from search results.
 * Exclude admin pages from unauthenticated REST API queries.
 * Exclude non image media library items from unauthenticated REST API queries.
 */
class OM4_Exclusions extends OM4_Plugin_Base {

    /**
     * List of page slugs to exclude from search results, and unauthenticated REST API queries for pages
     * All sub-pages will be automatically excluded as well.
     * @var array
     */
    protected $slugs_to_exclude = array(
        '/admin/',
        '/offers/'
    );

    public function plugins_loaded() {
			$this->filter( 'pre_get_posts', 9999999 );
			$this->hook( 'save_post', 10, 2 );

			$this->filter( 'rest_page_query', 10, 2 );
			$this->filter( 'rest_attachment_query', 10, 2 );
    }


    /**
     * Filter the list of pages/posts that are returned in a WordPress search.
     *
     * Executed by the 'pre_get_posts' filter
     *
     * @static
     * @param $query
     * @return query
     */
    public function pre_get_posts($query) {
        if ( $query->is_search && !is_admin() ) {
            // WordPress search
            $query->set('post__not_in', $this->get_page_ids_to_exclude() );
        }
        return $query;
		}

	/**
	 * Obtain the list of page IDs that should be excluded.
	 *
	 * This data is cached for one month for performance reasons.
	 *
	 * @return array List of page IDs
	 */
	protected function get_page_ids_to_exclude() {
		if ( false !== $ids = get_transient( 'om4_page_ids_to_exclude' ) ) {
			return $ids;
		}
		$page_ids_to_exclude = array();
		foreach ( $this->slugs_to_exclude as $slug_to_exclude ) {

			$page = get_page_by_path( $slug_to_exclude );

			if ( $page ) {
				// Exclude this page ID
				$page_ids_to_exclude[] = $page->ID;

				// Now fetch all sub pages (grandchildren) of this page (not just immediate descendants)
				$children_data = get_pages( array(
						'child_of'  => $page->ID,
						'post_type' => 'page'
				) );

				// Now exclude all sub pages
				foreach ( $children_data as $child ) {
					$page_ids_to_exclude[] = $child->ID;
				}
			}
		}
		set_transient( 'om4_page_ids_to_exclude', $page_ids_to_exclude, 24 * MONTH_IN_SECONDS );
		return $this->get_page_ids_to_exclude();
	}

	/**
	 * Whenever a page is added/edited/deleted, invalidate the cached list of excluded page IDs.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public function save_post( $post_id, $post ) {
		if ( 'page' === get_post_type( $post ) ) {
			delete_transient( 'om4_page_ids_to_exclude' );
		}
	}

	/**
	 * Unauthenticated REST API queries: exclude admin pages from the "Pages" query: /wp-json/wp/v2/pages
	 *
	 * @param $args
	 * @param $request
	 *
	 * @return mixed
	 */
	public function rest_page_query( $args, $request ) {

		if ( !isset($args['post__not_in']) ) {
			$args['post__not_in'] = array();
		}
		foreach ( $this->get_page_ids_to_exclude() as $page_id ) {
			if ( ! current_user_can( 'edit_page', $page_id ) ) {
				$args['post__not_in'][] = $page_id;
			}
		}

		return $args;
	}

	/**
	 * Unauthenticated REST API queries: only show image media library items: /wp-json/wp/v2/media
	 *
	 * This is done because PDF and zip files etc shouldn't be public.
	 *
	 * @param $args
	 * @param $request
	 *
	 * @return mixed
	 */
	public function rest_attachment_query( $args, $request ) {

		if ( current_user_can( 'upload_files' ) ) {
			return $args;
		}

		if ( ! isset( $args['post_mime_type'] ) ) {
			$args['post_mime_type'] = array();
		}
		// Get the list of image mime types
		$types = wp_get_ext_types();
		foreach ( $types['image'] as $ext ) {
			$args['post_mime_type'][] = "image/$ext";
		}

		return $args;
	}

}


