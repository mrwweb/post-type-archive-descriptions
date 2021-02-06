=== Post Type Archive Descriptions ===
Contributors: mrwweb, tusko-trush, jcdesign
Donate link: https://www.paypal.me/rootwiley
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: custom post type, custom post types, post type archive, archives, custom post type archive
Requires at least: 4.6
Tested up to: 5.6
Stable tag: 1.3.0

== Description ==

Enables an editable description to display on post type archive pages. Show the description with WordPress's `the_archive_description()` function that also displays taxonomy term descriptions. Will work automatically with many themes, including most default WordPress themes.

**Translations:** Archive descriptions are translatable via [Polylang](https://wordpress.org/plugins/polylang/), [WPML (affiliate link)](https://wpml.org/?aid=255503&affiliate_key=8ZIRtAbJbX4x&dr=post-type-archive-descriptions-plugin), and [qTranslate-X](https://wordpress.org/plugins/qtranslate-x/).

**The Events Calendar:** The archive description is automatically added above the Events Bar when using the latest templates from [The Events Calendar](https://wordpress.org/plugins/the-events-calendar/). Filter the location of the description or disable it entirely with the `ptad_tribe_template_before_include` filter.

= Other Plugins by MRWweb =

* [MRW Simplified Editor](https://wordpress.org/plugins/mrw-web-design-simple-tinymce/) - Get rid of bad and obscure TinyMCE buttons. Move the rest to a single top row.
* [Post Status Menu Items](http://wordpress.org/plugins/post-status-menu-items/) - Adds post status links–e.g. "Draft" (7)–to post type admin menus.
* [Hawaiian Characters](http://wordpress.org/plugins/hawaiian-characters/) - Adds the correct characters with diacriticals to the WordPress editor Character Map for Hawaiian
* [Feature a Page Widget](http://wordpress.org/plugins/feature-a-page-widget/) - Feature a single page in any sidebar.

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

Use [`the_archive_description()`](https://developer.wordpress.org/reference/functions/the_archive_description/) or [`get_the_archive_description()`](https://developer.wordpress.org/reference/functions/get_the_archive_description/).

Chances are you want this in the `archive-{post_type_slug}.php` or `archive.php` template files.

As of v1.3.0 of this plugin, the archive description is automatically added to The Events Calendar archive pages using the plugin's latest design.

= Which post types get a description? =

By default, any custom post type excluding Posts and Pages that was registered with `'has_archive' => true`. There is a filter (see below) to add support for *any* post type.

= Are there filters & actions to modify the plugin? =

Yes. Plenty!

Filters:

- `ptad_post_types` - specify the post types with a description (default is all non-built_in post types where `has_archive` is true)
- `ptad_admin_title` - Modify admin page title
- `ptad_admin_parent` - Change parent page of the Description edit page
- `ptad_menu_label` - Modify the menu item label in the admin
- `ptad_description_capability` - Set capability of who can edit descriptions. Default: `edit_posts`
- `ptad_edit_description_link` - Modify admin bar link to edit the description
- `ptad_view_archive_link` - Modify admin bar link to view the post type archive
- `ptad_tribe_template_before_include` - Modify which The Events Calendar template part the description should appear _before_, or `false` to disable automatic output.

Actions:

- `ptad_before_editor` - Between title and description editor for ALL admin pages. Receives `$post_type` arg.
- `ptad_before_editor_{post_type}` - Between title and description editor for any specific post type.
- `ptad_after_editor` - Immediately below description editor for ALL admin pages. Receives `$post_type` arg.
- `ptad_after_editor_{post_type}` -  Immediately below description editor for any specific post type.

== Screenshots ==

1. The editing interface for writing a post type archive description. This plugin adds the "Archive Description" link in the left menu, the "View Books Archive" link in the admin bar, and, of course, the field to save the description.

2. The post type archive description displayed (automatically!) in the Twenty Fifteen theme. The plugin also adds the "Edit Books Description" link in the Admin Bar.

== Changelog ==
= 1.3.0 (February 6, 2020) =
* [NEW!] Support for Polylang and WPML translations
* [NEW!] Automatically add archive description to The Events Calendar plugin's archive pages (Month, List, Week, Map, etc.)
* [Fix] Resolve warnings on Event Category pages when using The Events Calendar or other similar situations
* [Dev] Reorganize code

= 1.2.0 (October 3, 2020) =
* [Fix] Now supports post types in admin submenus other than the default. Big props to Jeremy Carlson, @jcdesign
* [Fix] Get correct description and don't show warning on a Post Type Archive that shows multiple post types (probably via pre_get_posts)
* [Dev] Code cleanup

== Upgrade Notice ==
= 1.3.0 =
New compatibility with Polylang, WPML, and The Events Calendar