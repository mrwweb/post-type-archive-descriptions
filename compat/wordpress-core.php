<?php
/****************************************************
 * 
 * Automatically display content if using *_archive_description() introduced in WordPress 4.1!
 * 
 ****************************************************/
add_filter( 'get_the_archive_description', 'ptad_archive_description' );

/**
 * filter the_archive_description & get_the_archive_description to show post type archive
 * @param  string $description original description
 * @return string              post type description if on post type archive
 */
function ptad_archive_description( $description ) {
	if( is_post_type_archive( ptad_get_post_types() ) ) {
		$description = ptad_get_post_type_description();
	}
	return $description;
}
