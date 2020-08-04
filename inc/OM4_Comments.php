<?php

/**
 * Ensure Gravatar alt tags aren't empty
 */
class OM4_Comments extends OM4_Plugin_Base {

	public function plugins_loaded() {
		$this->filter( 'get_avatar', 10, 5 );
	}

	public function get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

		if ( empty( $alt ) || false === $alt || '' === $alt ) {
			$author = '';

			if ( is_numeric( $id_or_email ) ) {
				$user = get_userdata( $id_or_email );
				if ( $user && isset( $user->display_name ) ) {
					$author = $user->display_name;
				}
			} elseif ( '' === $id_or_email ) {
				return $avatar;
			}
			if ( empty( $author ) && ( is_numeric( $id_or_email ) || is_a( $id_or_email, 'WP_Comment' ) ) ) {
				// $id_or_email is a comment ID or WP_Comment object
				$author = get_comment_author( $id_or_email );
			}

			// Translators: %s: Author (WP User).
			$alt    = empty( $author ) ? __( 'Avatar', 'om4-service' ) : sprintf( __( 'Avatar for %s', 'om4-service' ), $author );
			$alt    = esc_attr( $alt );
			$avatar = str_replace( "alt=''", "alt='$alt'", $avatar );
		}
		return $avatar;
	}

}
