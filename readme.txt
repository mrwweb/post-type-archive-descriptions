=== Post Type Archive Descriptions ===
Contributors: mrwweb, tusko-trush, jcdesign
Donate link: https://www.paypal.me/rootwiley
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: custom post type, custom post types, post type archive, archives, custom post type archive
Requires at least: 4.6
Tested up to: 6.0
Stable tag: 1.4.0

== Description ==

Enables an editable description to display on post type archive pages. Show the description with WordPress's `the_archive_description()` function that also displays taxonomy term descriptions. Will work automatically with many themes, including most default WordPress themes.

**Translations:** Archive descriptions are translatable via [Polylang](https://wordpress.org/plugins/polylang/), [WPML (affiliate link)](https://wpml.org/?aid=255503&affiliate_key=8ZIRtAbJbX4x&dr=post-type-archive-descriptions-plugin), and [qTranslate-X](https://wordpress.org/plugins/qtranslate-x/).

**The Events Calendar:** The archive description is automatically added above the Events Bar when using the latest templates from [The Events Calendar](https://wordpress.org/plugins/the-events-calendar/). Filter the location of the description or disable it entirely with the `ptad_tribe_template_before_include` filter.

= Other Plugins by MRWweb =

* [MRW Simplified Editor](https://wordpress.org/plugins/mrw-web-design-simple-tinymce/) - Get rid of bad and obscure TinyMCE buttons. Move the rest to a single top row.
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

Use [`the_archive_description()`](https://developer.wordpress.org/reference/functions/the_archive_description/) or [`get_the_archive_description()`](https://developer.wordpress.org/reference/functions/get_the_archive_description/).

Chances are you want this in the `archive-{post_type_slug}.php` or `archive.php` template files.

As of v1.3.0 of this plugin, the archive description is automatically added to The Events Calendar archive pages using the plugin's latest design.

= Which post types get a description? =

By default, any custom post type excluding Posts and Pages that was registered with `'has_archive' => true`. There is a filter (see below) to add support for *any* post type.

= How do I set up an editable description for my Blog / Posts? =

Since this plugin does not support descriptions for Posts or Pages, I recommend a different approach.

First make the blog page (aka the "Page for Posts") editable with this snippet in `functions.php` or an `mu-plugin`:

`add_filter( 'replace_editor', 'ptad_enable_gutenberg_editor_for_blog_page', 10, 2 );
/**
 * Simulate non-empty content to enable Gutenberg editor on the Blog page
 *
 * @param bool    $replace Whether to replace the editor.
 * @param WP_Post $post    Post object.
 * @return bool
 *
 * @see https://wordpress.stackexchange.com/a/350563/9844
 */
function ptad_enable_gutenberg_editor_for_blog_page( $replace, $post ) {

    if ( ! $replace && absint( get_option( 'page_for_posts' ) ) === $post->ID && empty( $post->post_content ) ) {
        // This comment will be removed by Gutenberg since it won't parse into block.
        $post->post_content = '<!--non-empty-content-->';
    }

    return $replace;

}`

Then output that content on the blog page with the `home.php` template:

`echo '<div class="archive-description blog-description">' . apply_filters( 'the_content', get_the_content( null, false, (int) get_option( 'page_for_posts' ) ) ) . '</div>';`

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
= 1.4.0 (June 21, 2022) =
* Tested up to 6.0.
* [Fix] The "Edit Archive" admin bar button was broken in the last version. It is now fixed
* [Fix] Prevent error when editing the Description of a post type that contains "-description" in the post type slug. Hilariously edge-casey.
* [Fix] Restore archive description before events bar component in The Events Calendar
* [Dev] Minor code reorganization
* Want a way to edit the blog page? [Leave your feedback!](https://github.com/mrwweb/post-type-archive-descriptions/issues/22)

== Upgrade Notice ==
= 1.4.0 =
Tested up to WP 6.0. Restore missing "Edit Description" buttons in admin bar and description on The Events Calendar calendar page.