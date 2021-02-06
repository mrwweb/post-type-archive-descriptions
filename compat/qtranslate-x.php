<?php
/**
 * One additional line of compatability is in the `ptad_editor_field` function of the main plugin
 */
if ( ! defined( 'QTX_VERSION' ) ) {

	add_filter( 'ptad_wp_editor_settings', 'ptad_qtranslate_editor_args' );

	/**
	 * filter editor settings to add necessary text editor classes for support
	 * @param  array $editor_settings tinymce settings array
	 * @return array                  filtered settings
	 */
	function ptad_qtranslate_editor_args( $editor_settings ) {
		 $editor_settings['classes'] = $editor_settings['classes'] . ' multilanguage-input qtranxs-translatable';

		 return $editor_settings;
	}
	
	add_filter('qtranslate_load_admin_page_config', 'ptad_qtranslate_support', 99); // 99 priority is important, loaded after registered post types

	/**
	 * filter qtranslate so it knows to pay attention on archive description editor pages
	 */
	function ptad_qtranslate_support( $page_configs ) {

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

		return $page_configs;

	}

}
