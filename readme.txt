=== Post Snippets ===
Contributors: davdebcom
Tags: custom snippet, custom shortcode, snippet, snippets, shortcode, shortcodes, block, blocks, html
Requires at least: 3.3
Tested up to: 4.9
Requires PHP: 5.3
Stable tag: 3.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create custom shortcodes and reusable content and insert them in into your posts and pages.

== Description ==

> Create custom shortcodes and reusable content and insert them in into your posts and pages.

This plugin lets you build a library with snippets of HTML, PHP code or reoccurring text that you often use in your posts and pages. You can use predefined variables to replace parts of the snippet on insert. All snippets are available in the post editor via a button in the Visual mode. The snippet can be inserted as defined, or as a shortcode to keep flexibility for updating the snippet. PHP code is supported for snippets inserted as shortcodes.

= Features =

* **Insert** All defined snippets is inserted from a button directly in the post editor.
* **Shortcodes** You can use this plugin to create your own custom shortcodes.
* **PHP** A shortcode snippet can optionally be processed as PHP code.
* **Buttons** The snippets can be found in the (visual) WordPress editor with a button and in the HTML editor with a quicktag.
* **User-friendly** Easy to use 'Manage Snippets' page where you can add, edit and remove snippets.
* **Variables** Each snippet can have as many custom variables as you like, which can be used on insert.
* **Import/Export** Snippets can be imported and exported between sites.
* **Documentation** Full documentation is available directly from the help panel in the plugin (top right in WordPress).
* **Uninstall** If you delete the plugin from your plugins panel it cleans up all data it has created in the WordPress database.

= Related Links =

* [Website](https://www.postsnippets.com/)
* [Support](http://wordpress.org/support/plugin/post-snippets)

== Installation ==

= Requirements =

* PHP version 5.3.0 or greater.
* WordPress version 3.3 or greater.

= Automatic installation =

1. Install the plugin via Plugins > New plugin. Search for 'Post Snippets'.
2. Activate the 'Post Snippets' plugin through the 'Plugins' menu in WordPress.
3. Go to Settings > Post Snippets and start entering your snippets.

= Manual installation =

1. Unpack the downloaded package
2. Unzip and upload the directory 'post-snippets' to the `/wp-content/plugins/` directory
3. Activate the 'Post Snippets' plugin through the 'Plugins' menu in WordPress
4. Go to Settings > Post Snippets and start entering your snippets.

= Uninstall =

1. Deactivate Post Snippets in the 'Plugins' menu in WordPress.
2. Select Post Snippets in the 'Recently Active Plugins' section and select
   'Delete' from the 'Bulk Actions' drop down menu.
3. This will delete all the plugin files from the server as well as erasing all
   options the plugin has stored in the database.

== Frequently Asked Questions ==

= Where can I the documentation? =

Documentation for the plugin is available directly from the Help panel in the
plugin administration screen (top right corner of WordPress).

= Why does importing Snippets on a Multisite installation fail? =

Uploading of zip files must be allowed, enable this in Sites Network Admin > Settings > Upload Settings > Upload file types.

= How can I use the content in an enclosed shortcode? =

If the shortcode is enclosed and contains content between the tags in a post. Example: `[shortcode]Some text[/shortcode]` the text within will be available in a variable called content. So in your snippet use {content} to display it. Don't enter 'content' in the variable field, it's automatically assigned.

= Where can I get support? =

Please visit the [Support Forum](https://wordpress.org/support/plugin/post-snippets) for questions, answers, support and feature requests.

= Can I disable the PHP Code Execution feature? =

To disable the "PHP Code" execution feature in this plugin, add the following code your theme's functions.php or to wp-config.php: `define('POST_SNIPPETS_DISABLE_PHP', true);`

This is useful if you are using this plugin for client sites, and don't want
your clients to be able to use PHP code in a post snippet.

== Screenshots ==

1. Settings > Post Snippets: the admin page where you create and manage snippets.
2. The convenient button for Post Snippets in the WordPress editor.
3. The Post Snippet insert dialog that shows all snippets.
4. Example of an inserted snippet, with optional variables.
5. Easy to access inline documentation (top right 'Help' button in WordPress).

== Changelog ==

= Version 3.0.5 - 13 May 2018 =

* Fix conflict with SiteOrigin Page Builder (Editor), increase z-index so Post Snippets Insert box opens on top of the editor
* Only show submenu when Post Snippets is opened, not on all Settings pages, and remove Support Forum and Contact submenu items
* Improve stability by updating Post Snippets to string-based menu slug
* Add instructions and optimize UI for new users/new sites (with slightly pulsating "Add New Snippet" button)
* Automatically disable Post Snippets if premium version is activated
* Make 'Post Snippets' header title in plugin translatable

= Version 3.0.4 - 20 Feb 2018 =

* Add "Support forum" link to support forum
* Add "Contact Us" link with contact form
* Add "Account" and "Upgrade" link for Pro version
* Updated Freemius SDK to 1.2.4

= Version 3.0.3 - 15 Feb 2018 =

* FIX:
    * Check for unique titles/shortcodes when creating new snippets, prevent duplicates
    * Improper slashes parsing, some users woudl see multiple clashes in shortcodes/PHP code, causing snippets to not render correctly
    * Prevent some editors (TinyMCE Advanced) from stacking above the Post Snippets dialog, add zindex to .ui-dialog

= Version 3.0.2 - 13 Jan 2018 =

* Fixed a PHP error by changing a new style array syntax to the version that also works in PHP 5.3 (this: array())

= Version 3.0.0 - 13 Jan 2018 =

* NOTES:
    * Reviewed close to 30 issues and either closed, fixed or registered them to be fixed in the future, see https://github.com/davdebcom/post-snippets/issues/68

* NEW:
    * A nice new User Interface, a good starting point for more improvements in the future. Switched from old tables to div's and css
    * The new UI supports updating the snippet name/shortcode without a page refresh
    * Expanding and collapsing individual snippets, or all snippets with a click, is now possible
    * The state of snippets (expand/collapse) is personal, so snippet managers can open and close snippets as they see fit
    * Added a date to the export filename as requested by multiple users, example: post-snippets-export-2014-06-14.zip

* FIX:
    * Compatibility for PHP 7.2
    * Fix some URL's in the plugin so they also work when post-snippets folder is not exactly "post-snippets"
    * Remove uninstall actions, I believe it's not user friendly to remove data when user uninstalls, what if they are updating (manually)?

* DEV:
    * Added version tags to assets, so updated of javascript and css happen without issues in the following updates
    * Added conditional code for checking if dialog and tabs functions exists, by KZeni
    * Added Freemius SDK, which will help with improving Post Snippets in the future. It's completely opt-in, you can keep it disabled.

= Version 2.5.4 - 30 Nov 2017 =
 * UI improvement: Add update, add new and delete buttons to top of Snippets list, not just bottom
 * Add better note to guide new users to the documentation ('Help' in the top right of the screen)
 * Update URL's etc to https://www.postsnippets.com and update other plugin details
 * Add 'Get started' admin notice to guide new users to Post Snippets in WordPress Settings
 * Add a newsletter opt-in so users can get Post Snippets updates via email
 * Add 'Pro features' page for feature voting, tell me what you need in Post Snippets

= Version 2.5.3 - 14 Feb 2016 =
 * Fixes an issue with the compression library for import and export.

= Version 2.5.2 - 7 Feb 2016 =
 * Fixes an issue than can occur when other plugins bootstrap WordPress Admin
   and then includes `admin_head` but not `admin_footer`, like download monitor.
 * Refactors parts of the code to prepare for a future snippet storage update.

= Version 2.5.1 - 6 Feb 2016 =
 * Makes strings inside javascripts translatable.
 * Removes `{content}` from shortcode output if the shortcode is not enclosed.

= Version 2.5 - 21 Jan 2016 =
 * Implements an options tab to handle plugin settings.
 * Reverts the change implemented in version 2.3.9 to exclude post snippets from
   custom editors by default, and instead adds it as an optional setting.
 * Fixes a conflict with plugins that adds custom editors on the frontend.

= Version 2.4 - 18 Dec 2015 =
 * Fixes potential conflict with WP Editor on none post screens.
 * Removes notice message on some screens if WordPress debug mode is enabled.

= Version 2.3.9 - 14 Dec 2015 =
 * Only includes the javascript code to include Post Snippets in WordPress'
   editor on post editing related screens, to avoid potential conflicts with
   other plugins.

= Version 2.3.8 - 10 Dec 2015 =
 * Adds validation of shortcode names. Invalid shortcode names now gets
   highlighted in red.

= Version 2.3.7 - 4 Nov 2015 =
 * Updates translatable strings to be Language Pack compatible.

= Version 2.3.6 - 15 Jul 2015 =
 * Bumps minimum required PHP version to 5.3.0.
 * Adds list of translators to inline documentation.

= Version 2.3.5 - 18 Jan 2015 =
 * Adds new developer filter, `post_snippets_snippets_list`.
 * Integrates plugin doc to the post editor help tab.
 * Adds complete plugin usage documentation to the settings help tab.

= Version 2.3.4 - 20 Sep 2014 =
 * Tested up to WordPress v4.0.
 * Update Swedish translation.
 * Add Ukrainian translation.

= Version 2.3.3 - 12 Apr 2014 =
 * Updates Post Snippets admin screen to not let Chrome XSS Auditor preventing
   snippets containg form elements to be saved.
 * Adds Serbo-Croatian translation.
 * Updated Polish translation.

= Version 2.3.2 - 5 Aug 2013 =
 * Updates insert snippets into the editor to be compatible with jQuery 1.9+
   (which makes it compatible with WordPress 3.6).
 * Changes WP version check to comply with WordPress 3.3 as the minimum required
   version.

= Version 2.3.1 - 1 Jun 2013 =
 * Removes the `$isArray` argument from `PostSnippets::getSnippet()` as it was
   not needed.

= Version 2.3 - 1 Jun 2013 =
 * Updates `PostSnippets::getSnippet($name, $variables)` to be able
   to accept an array with variables and not only a querystring. Fixes
   [issue #22](https://github.com/davdebcom/post-snippets/issues/22).
 * Removes `get_post_snippet()` which was deprecated in version 2.1.
 * Adds POST_SNIPPETS_DISABLE_PHP constant to easy disable the PHP code
   execution in snippets. Add
   `define('POST_SNIPPETS_DISABLE_PHP', true);`
   to wp-config.php or the theme's functions.php to disable PHP execution in the
   plugin.

= Version 2.2.3 - 11 May 2013 =
 * Fixes issue with the QuickTag button not being displayed in Firefox.

= Version 2.2.2 - 10 May 2013 =
 * The Post Snippets buttons now works everywhere there is a wp_editor present
   and not only on post/page edit screens.

= Version 2.2.1 - 1 May 2013 =
 * Added an option to give users with `edit_posts` capability access to the
   Post Snippets Admin. Add
   `define('POST_SNIPPETS_ALLOW_EDIT_POSTS', true);`
   to wp-config.php to enable access for those users. Fixes
   [issue #12](https://github.com/davdebcom/post-snippets/issues/12).
 * Optimizes code for the admin section.

= Version 2.2 - 26 Apr 2013 =
 * Bumps required WordPress version to v3.3.
 * Fixes a problem with using some HTML entities in snippets.
 * Removes the screenshots from the plugin archive, to make a smaller archive.
 * Updates help text for `PostSnippets::getSnippet()`.
 * Fixes a PHP warning that occurred when no post snippets exist.
 * Makes the plugin PSR-2 compliant and introduces Travis CI for testing.

= Version 2.1.1 - 23 Feb 2013 =
 * Fixes a bug that PHP snippets called an old class definition.

= Version 2.1 - 22 Feb 2013 =
 * Default values are now respected as shortcode defaults and not only to
   populate the insert window's fields.
 * Allow other plugins or themes to disable the PHP Code execution feature using
   the new `post_snippets_php_execution_enabled` filter.
 * `PostSnippets::getSnippet()` now executes shortcodes within snippets.
 * The function `get_post_snippet()` used to retrieve snippets from other
   places in WordPress has been deprecated. Please update any code using this
   function to use `PostSnippets::getSnippet()` instead, which replaces the old
   function. `get_post_snippet()` will be removed in a future version. Most
   users are not affected by this change.
 * Refactored code to comply with the PSR-0 standard.
 * Migrated to GitHub to maintain the code in development.
   [Post Snippets at GitHub](https://github.com/davdebcom/post-snippets).
 * Included Polish translation (pl_PL) by Tomasz Wesołowski.
 * Included Slovak translation (sk_SK) by Branco Radenovich.

= Version 2.0 - 29 Mar 2012 =
 * Added tabs to the Admin page, and moved Import/Export to a separate tab.
 * Pressing enter in a text field now defaults to Update and not Add.
 * Fixed debug notice message displayed when deleting without any snippets
   selected.
 * Various code refactoring and optimizations.

= Version 1.9.7 - 22 Mar 2012 =
 * Updated the styling for the snippet insert window in the post editor. This
   fixes the visual glitch with tabs spanning multiple rows.
 * Users without `manage_options` but with `edit_posts` capability (authors,
   contributors, editors) can now see a read-only list of available snippets and
   related info.
 * Users with read-only access can toggle if they want to see their snippets
   overview as rendered output or as-is.
 * Enabled `label for=` with checkboxes.
 * Included Romanian translation by Web Hosting Geeks.

= Version 1.9.6 - 19 Mar 2012 =
 * Added two new filters. `post_snippets_import` and `post_snippets_export`.

= Version 1.9.5 - 17 Mar 2012 =
 * The HTML and scripts for the popup window in the post editor is now only
   generated on the screens where it's needed.

= Version 1.9.4 - 8 Feb 2012 =
 * Added an option to run shortcodes through wptexturize before output.
 * Bugfix: Default values could be cut off. Corrected in this update.

= Version 1.9.3 - 30 Jan 2012 =
 * Fixed a bug that variables using a default value wasn't inserted properly.

= Version 1.9.2 - 29 Jan 2012 =
 * A variable can now be assigned a default value that will be used in the
   insert window. Use the = sign to give a variable a default value. Ie.
   var1,var2=default,var3.
 * Added versioning to the admin jQuery dialog CSS and the TinyMCE plugin
   JavaScript to prevent browser caching of older versions on update.

= Version 1.9.1 - 22 Jan 2012 =
 * Updated the built-in help text to include all the latest features added.

= Version 1.9 - 17 Jan 2012 =
 * Initial implementation to allow snippets to be evaluated as PHP code.
 * PHP version 5.2.4 or greater is now required to run Post Snippets.

= Version 1.8.9.2 - 15 Jan 2012 =
 * Added an additional check to see if Post Snippets is loaded via a
   bootstrapped WP Admin that doesn't set the is_admin() flag, so it works in
   that environment as well.

= Version 1.8.9.1 - 11 Jan 2012 =
 * A bug fixed with get_post_snippets() that were introduced in the last update.
 * Unit test for get_post_snippets() added to automate testing that it won't
   break in future updates.

= Version 1.8.9 - 10 Jan 2012 =
 * Updated the help text to take advantage of the new Help API introduced with
   WordPress 3.3.
 * Updated the Swedish translation.

= Version 1.8.8 - 28 Dec 2011 =
 * Removed the unneeded QuickTag checkbox from the settings screen for snippets,
   as all snippets are now always available from the HTML editor's QuickTag
   button.

= Version 1.8.7 - 25 Dec 2011 =
 * Updated the TinyMCE plugin for the Post Snippets button in WordPress Visual
   Editor to use the same jQuery UI Dialog window that the HTML button have had
   for some time. The consolidation of using the same window and code for the
   different buttons will make Post Snippets easier to maintain and update.
 * Added an admin notice when running on PHP versions below 5.2.4 to prepare
   users that future Post Snippets requirements will be on par with WordPress
   3.3.

= Version 1.8.6 - 15 Dec 2011 =
 * The Post Snippets HTML editor button is updated to be compatible with
   WordPress 3.3 refactored QuickTags.

= Version 1.8.5 - 22 Nov 2011 =
 * Included German translation by Brian Flores.
 * For all translators: Updated the .pot file to include all the latest strings
   and changes.

= Version 1.8.4 - 10 Nov 2011 =
 * Included Belarusian translation by Alexander Ovsov.

= Version 1.8.3 - 13 Oct 2011 =
 * Included Hebrew translation by Sagive.

= Version 1.8.2 - 3 Sep 2011 =
 * Added support for using enclosed shortcodes with the snippets. Use the
   variable {content} in your snippets to retrieve the enclosed content.
 * Updated the dropdown help text.
 * Included Lithuanian translation by Nata Strazda.

= Version 1.8.1 - 11 Jul 2011 =
 * Fixed that a PHP warning is thrown when other scripts called the
   get_post_snippet() function without supplying a second argument.

= Version 1.8 - 30 May 2011 =
 * Fixed an escaping problem with the snippet description.
 * Added Import / Export functionality.
 * Snippets used as shortcodes can now nest other shortcodes in them.

= Version 1.7.3 - 3 Mar 2011 =
 * Added a text area field in the settings panel to enter an optional
   description for each snippet. This decription is displayed for the editor
   writing a post in the jQuery Post Snippet dialog.
 * Fixed the styling of the quicktag jQuery window when the user have disabled
   the visual editor completely.
 * Fixed problem with line formatting in the new quicktag snippets.
 * Fixed a problem with JavaScript snippets breaking the admin page.
 * Various small bugfixes.

= Version 1.7.2 - 28 Feb 2011 =
 * Specified text/javascript for the UI dialog script.
 * Updated the Spanish translation by Melvis E. Leon Lopez.

= Version 1.7.1 - 26 Feb 2011 =
 * Added styling to the Tabs in the Quicktag jQuery dialog window to make them
   more "tab-like".
 * Added the possibility to use a description for each snippet to display for
   the user when opening the Quicktag jQuery dialog window. Snippets without
   description and variables, has a default information message.
 * Moved the help text from below the snippets to the contextual help dropdown
   menu at the top of the settings page.
 * **Changed the required version of WordPress to 3.0**.
 * Request by proximity2008: A snippet without anything entered in the snippet
   field will not be registered as a shortcode.

= Version 1.7 - 26 Feb 2011 =
 * Complete rewrite of the QuickTags insert functionality. It now uses jQuery UI
   to display a similar tabbed window as the TinyMCE button does. There is now
   one 'Post Snippets' button in the HTML editor instead of a separate button
   for each snippet. As the QuickTags function is completely rewritten, and this
   is the initial release of the new method, please report if you encounter any
   problems with it.
 * Fixed QuickTags compability with WordPress 3.1.
 * Added a link to the Post Snippets Settings directly from the entry on the
   'Plugins List' page.
 * Added get_post_snippet() function to retrieve snippets directly from PHP.

= Version 1.5.4 - 26 Jan 2011 =
 * Included Turkish translation by Ersan Özdil.

= Version 1.5.3 - 19 Sep 2010 =
 * Included Spanish translation by Melvis E. Leon Lopez.

= Version 1.5.2 - 17 Sep 2010 =
 * The plugin now keeps linefeed formatting when inserting a snippet directly
   with a quicktag in the HTML editor.
 * Updated the code to not generate warnings when running WordPress in debug
   mode.

= Version 1.5.1 - 12 Mar 2010 =
 * Fixed ampersands when used in a shortcode, so they are XHTML valid.

= Version 1.5 - 12 Jan 2010 =
 * Updated the plugin so it works with WordPress 2.9.x (the quicktags didn't
   work in 2.9, now fixed.).

= Version 1.4.9.1 - 5 Sep 2009 =
 * Included French translation by Thomas Cailhe (Oyabi).

= Version 1.4.9 - 10 Aug 2009 =
 * Included Russian translation by FatCow.

= Version 1.4.8 - 9 May 2009 =
 * Changed the handling of the TinyMCE button as some server configurations had
   problems finding the correct path.
 * Fixed a problem that didn't let a snippet contain a </script> tag.

= Version 1.4.7 - 27 Apr 2009 =
 * Added a workaround for a bug in WordPress 2.7.x wp-includes/compat.php that
   prevented the plugin to work correctly on webservers running with PHP below
   version 5.1.0 together with WP 2.7.x. This bug is patched in WordPress 2.8.

= Version 1.4.6 - 25 Apr 2009 =
 * Updated all code to follow the WordPress Coding Standards for consistency, if
   someone wants to modify my code.
 * Removed the nodechangehandler from the TinyMCE js, as it didn't fill any
   purpose.
 * Updated the save code to remove the PHP Notice messages, if using error
   logging on the server.
 * Added additional proofing for the variables string.

= Version 1.4.5 - 24 Apr 2009 =
 * Fixed a problem in the admin options that didn't allow a form with a textarea
   to be used as a snippet.
 * Widened the columns for SC and QT slightly in the options panel so they
   should look a bit better on the mac.

= Version 1.4.4 - 19 Apr 2009 =
 * Minor fix with quicktags and certain snippets that was left out in the last
   update.

= Version 1.4.3 - 16 Apr 2009 =
 * Fixed an escaping problem with the recently implemented shortcode function,
   that could cause problems on certain strings.
 * Fixed an escaping problem with the quicktag javascript, that could cause
   problems on certain strings.

= Version 1.4.2 - 11 Apr 2009 =
 * Fixed some additional syntax for servers where the short_open_tag
   configuration setting is disabled.

= Version 1.4.1 - 10 Apr 2009 =
 * Removed all short syntax commands and replaced them with the full versions so
   the plugin also works on servers with the short_open_tag configuration
   setting disabled.

= Version 1.4 - 10 Apr 2009 =
 * Added a checkbox for Shortcodes (SC) in the admin panel. When checking this
   one a dynamic shortcode will be generated and inserted instead of the
   snippet, which allows snippets to be updated later on for all posts it's been
   inserted into when using this option.
 * Added a checkbox for Quicktags (QT) in the admin panel, so Quicktags are
   optional. Speeds up loading of the post editor if you don't need the quicktag
   support, and only use the visual editor. Defaults to off.

= Version 1.3.5 - 9 Apr 2009 =
 * Fixed so the TinyMCE window adds a scrollbar if there is more variables for a
   snippet than fits in the window.
 * Fixed a bug that snippets didn't get inserted when using the visual editor in
   fullscreen mode.

= Version 1.3 - 2 Apr 2009 =
 * Fixed a problem with the regular expressions that prohibited variables
   consisting of just a single number to work.
 * Updated the Help info in the admin page to take less space.
 * Included a check so the plugin only runs in WP 2.7 or newer.

= Version 1.2 - 1 Apr 2009 =
 * Added support for Quicktags so the snippets can be made available in the HTML
   editor as well.

= Version 1.1 - 24 Mar 2009 =
 * Included Swedish translation.
 * Added TextDomain functionality for I18n.

= Version 1.0 - 23 Mar 2009 =
 * Initial Release

== Upgrade Notice ==

= 1.9 =
Note that starting with this version and moving forward, at least PHP v5.2.4 is
required to run Post Snippets.

= 2.1 =
The function `get_post_snippet()` used to retrieve snippets from other places in
WordPress has been deprecated. Please update any code you might have modified
that uses this function to use `PostSnippets::getSnippet()` instead, which
replaces the old function. `get_post_snippet()` will be removed in a future
version.

Most users are not affected by this change.

= 2.2 =
Note that at least WordPress v3.3 are required for Post Snippets v2.2.

= 2.3 =
The function `get_post_snippet()` used to retrieve snippets was deprecated
in version 2.1. In this update it is now completely removed. Please update any
code you might have that uses this function to use `PostSnippets::getSnippet()` instead, which replaces the deprecated function.

Most users are not affected by this change.

= 2.3.6 =
The minimum required PHP version have changed from v5.2.4 to v5.3.0 with this
update of Post Snippets to prepare for the next major release.

Most servers should be using PHP v5.3.0 or newer by now, but if you are unsure
what PHP version your server is using, please check before updating to this
version of the plugin.
