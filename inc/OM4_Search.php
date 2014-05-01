<?php
/**
 * Modifications to the WordPress search functionality.
 *
 * Exclude admin pages from search results
 */
class OM4_Search extends OM4_Plugin_Base {

    /**
     * List of page slugs to exclude from search results.
     * All sub-pages will be automaticaly excluded as well.
     * @var array
     */
    protected $slugs_to_exclude = array(
        '/admin/',
        '/offers/'
    );

    public function plugins_loaded() {
			$this->filter( 'pre_get_posts', 9999999 );
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

            $page_ids_to_exclude = array();

            foreach ( $this->slugs_to_exclude as $slug_to_exclude ) {

                $page = get_page_by_path( $slug_to_exclude );

                if ( $page ) {
                    // Exclude this page ID
                    $page_ids_to_exclude[] = $page->ID;

                    // Now fetch all sub pages (grandchildren) of this page (not just immediate descendants)
                    $children_data = get_pages( array(
                        'child_of' => $page->ID,
                        'post_type' => 'page'
                    ) );

                    // Now exclude all sub pages
                    foreach( $children_data as $child ) {
                        $page_ids_to_exclude[] = $child->ID;
                    }
                }
            }
            $query->set('post__not_in', $page_ids_to_exclude );
        }

        return $query;
    }

}