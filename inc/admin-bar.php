<?php
/****************************************************
 * 
 * Add Edit / View Links to the WordPress Admin Bar
 * 
 * Props: blog.rutwick.com/add-items-anywhere-to-the-wp-3-3-admin-bar
 * 
 ****************************************************/

/**
 * add links to view/edit archive in the admin bar
 */
function ptad_admin_bar_links( $admin_bar ) {

	if(
		! is_admin()
		&& is_post_type_archive( ptad_get_post_types() )
		&& current_user_can( ptad_allow_edit_posts() )
	 ) {
		$post_type = ptad_get_post_type_from_queried_object();
		$post_type_object = get_post_type_object( $post_type );

		if( is_object( $post_type_object ) ) {

			$post_type_name = $post_type_object->labels->name;

			$link_text = sprintf( __( 'Edit %1$s Description', 'post-type-archive-descriptions' ), $post_type_name );

			/**
			 * filter the "Edit {Post Type} Description" link
			 * @param $link_text string default test
			 * @param $post_type_name string name of post type for targeting specific type
			 */
			$link_text = apply_filters( 'ptad_edit_description_link', $link_text, $post_type_name );

			$parent_page = ptad_settings_page_parent( $post_type, $post_type_object->show_in_menu );
			
			$args = array(
				'id'    => 'wp-admin-bar-edit',
				'title' => $link_text,
				'href'  => admin_url( $parent_page . '&page=' . $post_type . '-description' )
			);
			$admin_bar->add_menu( $args );

		}
	}

	if( is_admin() && isset( $_GET['page'] ) ) {

		$screen = get_current_screen();
		$post_type = ptad_get_post_type_from_admin_page_slug();
		$slug = $post_type . '-description';
		$base_ends_with_slug = substr_compare( $screen->base, $slug, strlen( $screen->base ) - strlen( $slug ), strlen( $slug ) );

		if( 0 === $base_ends_with_slug ) {

			$post_type_object = get_post_type_object( $post_type );
			$post_type_name = $post_type_object->labels->name;

			$link_text = sprintf( __( 'View %1$s Archive', 'post-type-archive-descriptions' ), $post_type_name );

			/**
			 * filter the "View {Post Type} Archive" link
			 * @param $link_text string default test
			 * @param $post_type_name string name of post type for targeting specific type
			 */
			$link_text = apply_filters( 'ptad_view_archive_link', $link_text, $post_type_name );

			$args = array(
				'id'    => 'wp-admin-bar-edit',
				'title' => $link_text,
				'href'  => get_post_type_archive_link( $post_type )
			);
			$admin_bar->add_menu( $args );
		}
	}

}
add_action('admin_bar_menu', 'ptad_admin_bar_links',  100);
