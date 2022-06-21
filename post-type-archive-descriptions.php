<?php
/*
Plugin Name: Post Type Archive Descriptions
Description: Enables an editable description for a post type to display at the top of the post type archive page.
Author: Mark Root-Wiley, MRW Web Design, NonprofitWP.org
Author URI: https://MRWweb.com
Version: 1.4.0
License: GPL v3
Text Domain: post-type-archive-descriptions

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

require_once( 'inc/helpers.php' );
require_once( 'inc/settings.php' );
require_once( 'inc/admin-bar.php' );
require_once( 'inc/template-tags.php' );

require_once( 'compat/wordpress-core.php' );
require_once( 'compat/qtranslate-x.php' );
require_once( 'compat/the-events-calendar.php' );
