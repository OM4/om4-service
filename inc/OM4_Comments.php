<?php

/**
 * Ensure Gravatar alt tags aren't empty
 */
class OM4_Comments extends OM4_Plugin_Base {

	public function plugins_loaded() {
		$this->filter( 'get_avatar', 10, 5 );
	}

	public function get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

		if ( empty( $alt ) || false == $alt || '' == $alt ) {
			$author = '';

			if ( is_numeric( $id_or_email ) ) {
				$user = get_userdata( $id_or_email );
				if ( $user && isset( $user->display_name ) ) {
					$author = $user->display_name;
				}
			}
			if ( empty($author) ) {
				$author = get_comment_author( $id_or_email );
			}

			$alt = esc_attr( sprintf( __( 'Avatar for %s', 'om4-service' ), $author ) );
			$avatar = str_replace( "alt=''", "alt='$alt'", $avatar );
		}
		return $avatar;
	}

}
