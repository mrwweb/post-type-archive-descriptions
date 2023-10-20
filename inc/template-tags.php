<?php
/**
 * Private/Legacy functions to get the description page content
 *
 * Developers: To display the archive description use get_the_archive_description() or the_archive_description().
 */
/**
 * Echo the post type archive description
 *
 * If on a post type archive, automatically grabs current post type
 *
 * @param  string $post_type slug for post type to show description for (optional)
 * @return string            post type description
 */
function ptad_the_post_type_description( $post_type = '' ) {
	echo ptad_get_post_type_description( $post_type );
}

/**
 * Return post type archive description
 *
 * If on a post type archive, automatically grabs current post type
 *
 * @param  string $post_type slug for post type to show description for (optional)
 * @return string            post type description
 */
function ptad_get_post_type_description( $post_type = '' ) {

	// get global $post_type if not specified
	if ( '' === $post_type ) {
		$post_type = ptad_get_post_type_from_queried_object();
	}

	$all_descriptions = (array) get_option( 'ptad_descriptions' );

	if ( array_key_exists( $post_type, $all_descriptions ) ) {
		$post_type_description = $all_descriptions[ $post_type ];
	} else {
		$post_type_description = '';
	}

	// Intentionally run the description through the_content filter
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	$description = apply_filters( 'the_content', $post_type_description );

	return $description;

}
