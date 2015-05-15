=== Post Type Archive Descriptions ===
Contributors: mrwweb
Donate link: https://cash.me/$MRWweb
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: cpt, custom post types, description, custom post type description, post type archive, archives, custom post type archive
Requires at least: 3.1
Tested up to: 4.2
Stable tag: 1.0.0

== Description ==

Enables an editable description for a post type to display at the top of the post type archive page. Works just like a taxonomy term description.

Automatically works for any theme that uses `the_archive_description()`, added in WordPress 4.1.

Also comes with a specific template tag—`ptad_the_post_type_description()` and `ptad_get_post_type_description()`—for other themes.

== Installation ==

1. Upload the `post-type-archive-descriptions` folder to the `/wp-content/plugins/` directory
1. Activate the Post Type Archive Descriptions plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I display a custom post type's description? =

In WordPress 4.1, [`the_archive_description()`](https://developer.wordpress.org/reference/functions/the_archive_description/) and [`get_the_archive_description()`](https://developer.wordpress.org/reference/functions/get_the_archive_description/) were introduced. As long as your theme supports those functions, you don't have to do anything!

Older themes can use `ptad_get_post_type_description()` or `ptad_the_post_type_description()` to return or echo a post type description. Both functions take an optional `$post_type` slug argument to return a specific post type. However, that is unnecesarry on a page that returns `true` for is_post_type_archive()`.

Chances are you want this in the `archive-post_type_slug.php` or `archive.php` template files.

= Are there filters to modify the plugin? =

Glad you asked. Yes. Plenty!

- `ptad_post_types` - specific the post types with a description (default is all non-built_in post types where `has_archive` is true)
- `ptad_admin_title` - Modify admin page title
- `ptad_menu_label` - Modify the menu item label in the admin
- `ptad_description_capability` - Set capability of who can edit descriptions. Default: `edit_posts`
- `ptad_edit_description_link` - Modify admin bar link to edit the description
- `ptad_view_archive_link` - Modify admin bar link to view the post type archive

== Screenshots ==

1. The editing interface for writing a post type archive description. This plugin adds the "Archive Description" link in the left menu, the "View Books Archive" link in the admin bar, and, of course, the field to save the description.

2. The post type archive description displayed (automatically!) in the Twenty Fifteen theme. The plugin also adds the "Edit Books Description" link.

== Changelog ==

= 1.0.0 (May 14, 2015) =

* Initial release.
* Forked from [CPT Descriptions](https://wordpress.org/plugins/cpt-descriptions/)

== Upgrade Notice == 

= 1.0.0 =
Let's do this! 