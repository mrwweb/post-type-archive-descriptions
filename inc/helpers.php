<?php
/****************************************************
 *
 * Get post types for plugin, filterable by users
 *
 ****************************************************/
/**
 * return array of post types that should use the Post Type Archive Description
 * @return array post types to use description with (default, all non-built-in with archive)
 */
function ptad_get_post_types() {

	$args = array(
		'_builtin' => false,
		'has_archive' => true
	);
	$post_types = apply_filters( 'ptad_post_types', get_post_types( $args ) );

	return $post_types;

}

function ptad_get_post_type_from_admin_page_slug() {
	$page = $_GET['page'];
	$post_type = preg_replace( '/-description$/', '', $page );

	return $post_type;
}

function ptad_get_post_type_from_queried_object() {
	$queried_object = get_queried_object();
	return $queried_object->name;
}
