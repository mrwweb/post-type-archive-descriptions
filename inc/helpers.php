<?php
/**
 * Get post types that should use the Post Type Archive Description
 *
 * Adjust this this list with `ptad_post_types` filter
 *
 * @return array post types to use description with (default, all non-built-in with archive)
 */
function ptad_get_post_types() {

	$args       = array(
		'_builtin'    => false,
		'has_archive' => true,
	);
	$post_types = apply_filters( 'ptad_post_types', get_post_types( $args ) );

	return $post_types;

}

/**
 * Extract a post type slug from the admin page slug
 */
function ptad_get_post_type_from_admin_page_slug() {
	/* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
	$page      = $_GET['page'];
	$post_type = preg_replace( '/-description$/', '', $page );

	return $post_type;
}

/**
 * Get the post type from the queried object
 */
function ptad_get_post_type_from_queried_object() {
	$queried_object = get_queried_object();
	return $queried_object->name;
}
