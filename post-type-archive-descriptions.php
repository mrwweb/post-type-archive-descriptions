<?php
/*
Plugin Name: Post Type Archive Descriptions
Description: Enables an editable description for a post type to display at the top of the post type archive page.
Author: Mark Root-Wiley
Author URI: http://mrwweb.com
Version: 1.0.0
License: GPL v3
Text Domain: post-type-archive-descriptions
Domain Path: /languages/

Post Type Archive Descriptions
Copyright (C) 2015, Mark Root-Wiley - info@MRWweb.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/****************************************************
 *
 * Load the text domain
 *
 ****************************************************/
load_plugin_textdomain(
	'post-type-archive-descriptions',
	false,
	dirname( plugin_basename( __FILE__ ) ) . '/languages/'
);

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

/**
 * Output filterable name of Settings page
 * 
 * @param string $post_type name of post type for the page
 * @param 'label'|'name' $pt_val whether $post_type is the label (default) or name
 * @return name for settings page
 */
function ptad_settings_page_title( $post_type, $pt_val = 'label' ) {
	if( $pt_val == 'name' ) {
		$post_type_info = get_post_types( array( 'name' => $post_type ), 'objects' );
		$post_type = $post_type_info[$post_type]->labels->name;
	}
	$settings_page_title = sprintf( __( 'Description for the %1$s Archive', 'post-type-archive-descriptions' ), $post_type );
	/**
	 * filter for admin menu label
	 * 
	 * @var string $settings_page_menu_label label text (default: "Description for the {Post Type} Archive")
	 * @var string $post_type post_type name if needed
	 */
	$settings_page_title = apply_filters( 'ptad_admin_title', $settings_page_title, $post_type );
	return $settings_page_title;
}

/**
 * Output filterable menu label for a post type's description settings page.
 * @param  string $post_type post_type to create label for
 * @param 'label'|'name' $pt_val whether $post_type is the label (default) or name
 * @return string            admin menu label
 */
function ptad_settings_menu_label( $post_type, $pt_val = 'label' ) {
	if( $pt_val == 'name' ) {
		$post_type_info = get_post_types( array( 'name' => $post_type ), 'objects' );
		$post_type = $post_type_info[$post_type]->labels->name;
	}
	$settings_page_menu_label = __( 'Archive Description', 'post-type-archive-descriptions' );
	/**
	 * filter for admin menu label
	 * 
	 * @var string $settings_page_menu_label label text (default: "Description")
	 * @var string $post_type post_type name if needed
	 */
	$settings_page_menu_label = apply_filters( 'ptad_menu_label', $settings_page_menu_label, $post_type );
	return $settings_page_menu_label;
}

/****************************************************
 * 
 * Register Menu Pages, Settings, and Callbacks
 * 
 ****************************************************/

add_action( 'admin_menu', 'ptad_enable_pages' );
/**
 * Register admin pages for description field
 */

function ptad_enable_pages() {

	$post_types = ptad_get_post_types();

	foreach ( $post_types as $post_type ) {

		if( post_type_exists( $post_type ) ) {

			add_submenu_page(
				'edit.php?post_type=' . $post_type, // $parent_slug
				ptad_settings_page_title( $post_type, 'name' ), // $page_title
				ptad_settings_menu_label( $post_type, 'name' ), // $menu_label
				'edit_posts', // $capability
				$post_type . '-description', // $menu_slug
				'ptad_settings_page' // $function
			);

		} // end if

	} // end foreach

}

/**
 * Register Setting, Settings Section, and Settings Field
 */

add_action( 'admin_init', 'ptad_register_settings' );
function ptad_register_settings() {

	$post_types = ptad_get_post_types();

	// A single option will hold all our descriptions
	register_setting(
		'ptad_descriptions', // $option_group
		'ptad_descriptions', // $option_name
		'ptad_sanitize_inputs' // $sanitize_callback
	);


	// add a settings section and field for each $post_type
	foreach ( $post_types as $post_type ) {

		if( post_type_exists( $post_type ) ) {

			// Register settings and call sanitization functions
			add_settings_section(
				'ptad_settings_section_' . $post_type, // $id
				'', // $title
				'ptad_settings_section_callback', // $callback
				$post_type . '-description' // $page
			);

			// Field for our setting
			add_settings_field(
				'ptad_setting_' . $post_type, // $id
				__( 'Description Text', 'post-type-archive-descriptions' ), // $title
				'ptad_editor_field', // $callback
				$post_type . '-description', // $page
				'ptad_settings_section_' . $post_type, // $section
				array( // $args
					'post_type' => $post_type,
					'field_name' => 'ptad_descriptions[' . $post_type . ']',
					'label_for' => 'ptad_descriptions[' . $post_type . ']'
				)
			);

		} // endif

	} // end foreach

}

// There is no need for this function at this time.
function ptad_settings_section_callback() {}

/**
 * Output a wp_editor instance for use by settings fields
 */
function ptad_editor_field( $args ) {

	$post_type = $args['post_type'];

	$descriptions = (array) get_option( 'ptad_descriptions' );

	if( array_key_exists($post_type, $descriptions) ) {
		$description = $descriptions[$post_type];
	} else {
		$description = '';
	}

	$editor_settings = array(
		'textarea_name' => $args['field_name'],
		'textarea_rows' => 15,
		'media_buttons' => true,
		'class' 		=> 'wp-editor-area wp-editor multilanguage-input qtranxs-translatable'
	);

	wp_editor( $description, 'ptadeditor', $editor_settings );
	
	add_filter('the_editor', 'qtranslate_admin_loadConfig');
	
}

/**
 * Output settings pages
 */
function ptad_settings_page() {
	$screen = get_current_screen();
	$post_type = $screen->post_type;
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php echo ptad_settings_page_title( $post_type, 'name' ); ?></h2>
		<form action="options.php" method="POST">
				<?php settings_fields( 'ptad_descriptions' ); ?>
				<?php do_settings_sections( $post_type . '-description' ); ?>
				<?php submit_button(); ?>
		</form>
	</div> <?php
}

/**
 * sanitize description inputs before saving option
 */
function ptad_sanitize_inputs( $input ) {
	// get all descriptions
	$all_descriptions = (array) get_option( 'ptad_descriptions' );
	// sanitize input
	foreach( $input as $post_type => $description ) {
		$sanitized_input[$post_type] = wp_kses_post( $description );
	}
	// merge with other descriptions into array setting
	$input = array_merge( $all_descriptions, $sanitized_input );

	return $input;
}

/**
 * Allow editors to save Post Type Descriptions
 * 
 * See: http://core.trac.wordpress.org/ticket/14365
 */
function ptad_allow_edit_posts() {
	$capability = 'edit_posts';
	/**
	 * filter the capability for who can edit descriptions
	 * 
	 * @var string $capability capability required to edit post type descriptions (default: edit_posts)
	 */
	$capability = apply_filters( 'ptad_description_capability', $capability );
	
	return esc_attr( $capability );
}
add_filter( 'option_page_capability_ptad_descriptions', 'ptad_allow_edit_posts' );

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

	if( !is_admin() && is_post_type_archive( ptad_get_post_types() ) ) {
		global $post_type;
		$post_type_object = get_post_type_object( $post_type );
		$post_type_name = $post_type_object->labels->name;

		$link_text = sprintf( __( 'Edit %1$s Description', 'post-type-archive-descriptions' ), $post_type_name );
		/**
		 * filter the "Edit {Post Type} Description" link
		 * @var $link_text string default test
		 * @var $post_type_name string name of post type for targeting specific type
		 */
		$link_text = apply_filters( 'ptad_edit_description_link', $link_text, $post_type_name );

		$args = array(
			'id'    => 'wp-admin-bar-edit',
			'title' => $link_text,
			'href'  => admin_url( 'edit.php?post_type=' . $post_type . '&page=' . $post_type . '-description' )
		);
		$admin_bar->add_menu( $args );
	}

	if( is_admin() ) {

		$screen = get_current_screen();
		$post_type = $screen->post_type;
		$description_page = $post_type . '_page_' . $post_type . '-description';

		if( $screen->base == $description_page ) {
			$post_type_object = get_post_type_object( $post_type );
			$post_type_name = $post_type_object->labels->name;

			$link_text = sprintf( __( 'View %1$s Archive', 'post-type-archive-descriptions' ), $post_type_name );
			/**
			 * filter the "View {Post Type} Archive" link
			 * @var $link_text string default test
			 * @var $post_type_name string name of post type for targeting specific type
			 */
			$link_text = apply_filters( 'ptad_view_archive_link', $link_text, $post_type_name );

			$post_type_object = get_post_type_object( $post_type );
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

/****************************************************
 * 
 * Automatically display content if using *_archive_description() introduced in 4.1!
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
	return wp_kses_post( $description );
}

/****************************************************
 * 
 * Functions to get Description Page Content
 * 
 ****************************************************/
/**
 * echo post type archive description
 * 
 * if on a post type archive, automatically grabs current post type
 * 
 * @param  string $post_type slug for post type to show description for (optional)
 * @return string            post type description
 */
function ptad_the_post_type_description( $post_type = '' ) {
	echo ptad_get_post_type_description( $post_type );
}

/**
 * return post type archive description
 * 
 * if on a post type archive, automatically grabs current post type
 * 
 * @param  string $post_type slug for post type to show description for (optional)
 * @return string            post type description
 */
function ptad_get_post_type_description( $post_type = '' ) {
	
	// get global $post_type if not specified
	if ( '' == $post_type ) {
		global $post_type;
	}

	$all_descriptions = (array) get_option( 'ptad_descriptions' );
	if( array_key_exists($post_type, $all_descriptions) ) {
		$post_type_description = $all_descriptions[$post_type];
	} else {
		$post_type_description = '';
	}
	$description = apply_filters( 'the_content', $post_type_description );

	return wp_kses_post( $description );

}

if ( ! defined( 'QTX_VERSION' ) ) {
	
	function ptad_qtranslate_support($page_configs) {
		{
			//edit.php?post_type=$post_type&page=
			$page_config = array();
			
			//get post types
			$post_types = ptad_get_post_types();

			// add a settings section and field for each $post_type
			foreach ( $post_types as $post_type ) {

				if( post_type_exists( $post_type ) ) {
					$page_config['pages'] = array( 'edit.php' => 'post_type=' . $post_type . '&page=' );
				}
				
			}

			$page_config['forms'] = array();

			$f = array();

			$f['fields'] = array();
			$fields = &$f['fields'];

			//textarea support
			$fields[] = array( 'tag' => 'textarea' );

			$page_config['forms'][] = $f;
			$page_configs[] = $page_config;
		}

		return $page_configs;
	}

	add_filter('qtranslate_load_admin_page_config', 'ptad_qtranslate_support', 99); // 99 priority is important, loaded after registered post types

}