<?php
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

	foreach ( $post_types as $post_type ) :

		if( post_type_exists( $post_type ) ) :

			// Check if post type has a particular parent menu location
			$post_type_object = get_post_type_object( $post_type );
			$show_in_menu     = $post_type_object->show_in_menu;
		    
			add_submenu_page(
				ptad_settings_page_parent( $post_type, $show_in_menu ), // $parent slug
				ptad_settings_page_title( $post_type, 'name' ), // $page_title
				ptad_settings_menu_label( $post_type, 'name' ), // $menu_label
				ptad_allow_edit_posts(), // $capability
				$post_type . '-description', // $menu_slug
				'ptad_settings_page' // $function
			);

		endif;

	endforeach;

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
	foreach ( $post_types as $post_type ) :

		if( post_type_exists( $post_type ) ) :

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

		endif;

	endforeach;

}

// There is no need for this function at this time.
function ptad_settings_section_callback( $args ) {

	$post_type = str_replace( 'ptad_settings_section_', '', $args['id'] );
	
	/**
	 * Action before Description editor field in the admin
	 *
	 * $post_type 	slug of post type the description is for
	 */
	do_action( 'ptad_before_editor', $post_type );
	
	/**
	 * Action before Description editor field in the admin only for a specific post type
	 *
	 * example add_action( 'ptad_before_editor_my_custom_post_type', 'mycpt_action' );
	 */
	do_action( 'ptad_before_editor_' . $post_type );

}

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
		'classes' 		=> 'wp-editor-area wp-editor'
	);

	$editor_settings = apply_filters( 'ptad_wp_editor_settings', $editor_settings, $args, $description );

	wp_editor( $description, 'ptadeditor', $editor_settings );

	/**
	 * Action before Description editor field in the admin
	 *
	 * $post_type 	slug of post type the description is for
	 */
	do_action( 'ptad_after_editor', $post_type );
	
	/**
	 * Action before Description editor field in the admin only for a specific post type
	 *
	 * example add_action( 'ptad_after_editor_my_custom_post_type', 'mycpt_action' );
	 */
	do_action( 'ptad_after_editor_' . $post_type );
	
	if ( ! defined( 'QTX_VERSION' ) ) {
		add_filter( 'the_editor', 'qtranslate_admin_loadConfig' );
	}
	
}

/**
 * Output settings pages
 */
function ptad_settings_page( $post_type ) {

	// occurs when parent menu item is not the post type
	if( empty( $post_type ) ) {
		$post_type = ptad_get_post_type_from_admin_page_slug();
	}
	?>

	<div class="wrap">
		<h2><?php echo ptad_settings_page_title( $post_type, 'name' ); ?></h2>
		<form action="options.php" method="POST">
			<?php settings_fields( 'ptad_descriptions' ); ?>
			<?php do_settings_sections( $post_type . '-description' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>

	<?php
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
 * Return capability that's allowed to edit posts
 * 
 * See: http://core.trac.wordpress.org/ticket/14365
 */
function ptad_allow_edit_posts() {

	$capability = 'edit_posts';

	/**
	 * filter the capability for who can edit descriptions
	 * 
	 * @param string $capability capability required to edit post type descriptions (default: edit_posts)
	 */
	$capability = apply_filters( 'ptad_description_capability', $capability );
	
	return esc_attr( $capability );

}
/* Set options page permissions to honor specific permissions for editing the description */
add_filter( 'option_page_capability_ptad_descriptions', 'ptad_allow_edit_posts' );

/**
 * Output filterable parent of Settings page
 *
 * @param string $post_type name of post type for the page
 * @return bool|string parent for settings page
 */
function ptad_settings_page_parent( $post_type, $show_in_menu = false ) {
 
	$settings_page_parent = $show_in_menu;
	
	// Default is standard post type editing screen
	if( $settings_page_parent && is_bool( $settings_page_parent ) ) {
		$settings_page_parent = "edit.php?post_type=$post_type";
	}
	
	/**
	 * filter for parent of Archive Settings page
	 *
	 * @param string $settings_page_parent address (default: "edit.php?post_type=$post_type")
	 * @param string $post_type post_type name if needed
	 */
	$settings_page_parent = apply_filters( 'ptad_admin_parent', $settings_page_parent, $post_type );

	return $settings_page_parent;

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
	 * @param string $settings_page_menu_label label text (default: "Description for the {Post Type} Archive")
	 * @param string $post_type post_type name if needed
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
	 * @param string $settings_page_menu_label label text (default: "Description")
	 * @param string $post_type post_type name if needed
	 */
	$settings_page_menu_label = apply_filters( 'ptad_menu_label', $settings_page_menu_label, $post_type );

	return $settings_page_menu_label;

}
