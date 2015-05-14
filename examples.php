<?php

/**
 * modify the admin menu title
 * 
 * @param $title is the current label
 * @param $post_type is the $post_type object
 * 
 * @return modified menu label 
 */
function my_change_ptad_menu_title( $title, $post_type ) {
	return $post_type->name . ' Listing Page';
}
add_filter( 'ptad_menu_label', 'my_change_ptad_menu_title', 10, 2 );

/**
 * modify the description settings page title
 * 
 * @param $title is the current page title
 * @param $post_type is the $post_type object
 * 
 * @return modified page title
 */
function my_change_ptad_settings_page_title( $title, $post_type ) {
    return 'Edit ' . $post_type->label . ' Listing Page';
}
add_filter( 'ptad_admin_title', 'my_change_ptad_settings_page_title', 10, 2 );

/**
 * add or remove an array of post types from those that get a "Descriptions" settings page
 * 
 * @param $post_types array initial post_types receiving the setting
 * 
 * @return modified array of post types 
 */
function my_add_ptad_post_types( $post_types ) {

	// add one or more post_types
    $post_types_to_add = array( 'my_post_type' );
    foreach( $post_types_to_add as $post_type ) {
        $post_types[] = $post_type;
    }

    // remove one or more post_types
    $post_types_to_remove = array( 'my_other_post_type' );
    foreach( $post_types_to_remove as $post_type ) {
        if( array_key_exists($post_type, $post_types) ) {
            unset($post_types[$post_type]);
        }
    }

    return $post_types;

}
add_filter( 'ptad_post_types', 'my_add_ptad_post_types' );