<?php
/**
 * Set the template which the Archive Description should appear before in v2 Tribe Events templates - OR - disable automatic output of archive description on Events templates with false
 */
$before_template = apply_filters( 'ptad_tribe_template_before_include', 'events/v2/components/events-bar' );
if( $before_template ) {

	add_action( 'tribe_template_before_include:' . esc_attr( $before_template ), 'ptad_tribe_events_archive_header' );
	/**
	 * Add Title to Events Pages
	 */
	function ptad_tribe_events_archive_header() {

		if( is_post_type_archive() && in_array( 'tribe_events', ptad_get_post_types() ) ) {
			the_archive_description( '<div class="archive-description">', '</div>' );
		}

	}

}
