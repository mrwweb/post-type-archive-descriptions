=== Post Type Archive Descriptions ===
Contributors: mrwweb
Donate link: https://www.paypal.me/rootwiley
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: custom post type, custom post types, post type archive, archives, custom post type archive
Requires at least: 4.6
Tested up to: 5.5
Stable tag: 1.2.0

== Description ==

Enables an editable description for a post type to display at the top of the post type archive page. Output the description via the native `the_archive_description()` function that already works with a taxonomy term descriptions.

Automatically works for any theme that uses `the_archive_description()` (added in WordPress 4.1) like [Twenty Fifteen](https://wordpress.org/themes/twentyfifteen/), [Twenty Sixteen](https://wordpress.org/themes/twentysixteen/), [Twenty Seventeen](https://wordpress.org/themes/twentyseventeen/) or most  [_s-based themes](https://underscores.me).

= Other Plugins by MRWweb =

* [Feature a Page Widget](http://wordpress.org/plugins/feature-a-page-widget/) - Feature a single page in any sidebar.
* [MRW Web Design Simple TinyMCE](https://wordpress.org/plugins/mrw-web-design-simple-tinymce/) - Get rid of bad and obscure TinyMCE buttons. Move the rest to a single top row.
* [Post Status Menu Items](http://wordpress.org/plugins/post-status-menu-items/) - Adds post status links–e.g. "Draft" (7)–to post type admin menus.
* [Hawaiian Characters](http://wordpress.org/plugins/hawaiian-characters/) - Adds the correct characters with diacriticals to the WordPress editor Character Map for Hawaiian

== Installation ==

1. From your WordPress site's dashboard, go to Plugins > Add New.
1. Search for "Post Type Archive Descriptions."
1. Click "Install."
1. Click "Activate."
1. Depending on your theme, you may need to add `the_archive_description()` to your templates in order for it to work.

== Frequently Asked Questions ==

= Is this plugin compatible with WordPress 5.0 / "Gutenberg"? =
Yes. The plugin does not directly integrate with the post editor screen so no changes were required.

Be aware that as of 5 Dec 2019, it [appears the Twenty Nineteen theme will *not* support post type archive descriptions by default](https://github.com/WordPress/twentynineteen/issues/256). Unless something changes, you will need to manually add support for them in a child theme if using Twenty Nineteen. See the next FAQ for how to do that.

= How do I display a custom post type's description? =

In WordPress 4.1, [`the_archive_description()`](https://developer.wordpress.org/reference/functions/the_archive_description/) and [`get_the_archive_description()`](https://developer.wordpress.org/reference/functions/get_the_archive_description/) were introduced. As long as your theme supports those functions, you don't have to do anything!

Older themes can use `ptad_get_post_type_description()` or `ptad_the_post_type_description()` to return or echo a post type description. Both functions take an optional `$post_type` slug argument to return a specific post type. However, that is unnecesarry on a page that returns `true` for is_post_type_archive()`.

Chances are you want this in the `archive-{post_type_slug}.php` or `archive.php` template files.

= Which post types get a description? =

By default, any custom post type excluding Posts and Pages that was registered with `'has_archive' => true`. There is a filter (see below) to add support for *any* post type.

= Are there filters & actions to modify the plugin? =

Glad you asked. Yes. Plenty!

Filters:

- `ptad_post_types` - specify the post types with a description (default is all non-built_in post types where `has_archive` is true)
- `ptad_admin_title` - Modify admin page title
- `ptad_admin_parent` - Change parent page of the Description edit page
- `ptad_menu_label` - Modify the menu item label in the admin
- `ptad_description_capability` - Set capability of who can edit descriptions. Default: `edit_posts`
- `ptad_edit_description_link` - Modify admin bar link to edit the description
- `ptad_view_archive_link` - Modify admin bar link to view the post type archive

Actions:

- `ptad_before_editor` - Between title and description editor for ALL admin pages. Receives `$post_type` arg.
- `ptad_before_editor_{post_type}` - Between title and description editor for any specific post type.
- `ptad_after_editor` - Immediately below description editor for ALL admin pages. Receives `$post_type` arg.
- `ptad_after_editor_{post_type}` -  Immediately below description editor for any specific post type.

== Screenshots ==

1. The editing interface for writing a post type archive description. This plugin adds the "Archive Description" link in the left menu, the "View Books Archive" link in the admin bar, and, of course, the field to save the description.

2. The post type archive description displayed (automatically!) in the Twenty Fifteen theme. The plugin also adds the "Edit Books Description" link.

== Changelog ==
= 1.2.0 (October 3, 2020) =
* [Fix] Now supports post types in admin submenus other than the default. Big props to Jeremy Carlson, @eyesofjeremy
* [Fix] Get correct description and don't show warning on a Post Type Archive that shows multiple post types (probably via pre_get_posts)
* Code cleanup

= 1.1.5 (September 23, 2019) =
* Add global and post-type-specific actions before and after the Description Editor on the admin editing screen: `ptad_before_editor`, `ptad_before_editor_{post_type}`, `ptad_after_editor`, and `ptad_after_editor_{post_type}`
* Don't sanitize plugin output so shortcodes work. Thanks @mmcginnis.

= 1.1.4 (August 13, 2018) =
* [Fix] Fully restrict access to Post Type Description edit screen when `ptad_description_capability` filter is used. [Props](https://wordpress.org/support/topic/ptad_description_capability-filter-should-control-display-of-submenu-pages/) @deucecreative
* Bump requires version to 4.6 and remove `load_plugin_textdomain` usage

= 1.1.3 (May 31, 2018) =
* [Fix] Remove deprecated screen icon notice on Archive Description edit screen
* Bump tested up to version

= 1.1.2 (April 25, 2017) =
* [Fix] Only show "Edit Description" admin bar link if user has correct permissions
* [Docs] Clarify one function's inline documentation

= 1.1.1 (April 18, 2016) =
* [Fix] Typo resulted in error and broken qTranslate-X support.

= 1.1.0 (March 30, 2016) =
* [New] New `ptad_wp_editor_settings` filter to modify Post Type Description TinyMCE settings. Thank you [@katanyan](https://profiles.wordpress.org/katanyan).
* [New] Support for qTranslate-X. Thank you to [@Tusko on GitHub](https://github.com/Tusko).

= 1.0.0 (May 14, 2015) =
* Initial release.
* Forked from [CPT Descriptions](https://wordpress.org/plugins/cpt-descriptions/)

== Upgrade Notice ==
= 1.2.0 =
Fixes for interesting non-standard post type cases. Thanks to Jeremy Carlson for a major fix!

= 1.1.5 =
New actions for developers!

= 1.1.1 =
Resolve error. Fix qTranslate-X support.

= 1.1.0 =
New filter for editor settings and qTranslate-X support.

= 1.0.0 =
Let's do this! 