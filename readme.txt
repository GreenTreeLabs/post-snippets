=== Post Snippets ===
Contributors: artstorm
Tags: post, admin, snippet, shortcode, html, custom, page, dynamic, editor, php, code
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Keep a snippet library of text, HTML or PHP code to be used in posts. Variables
can be set for more flexibility. Inserts directly or as shortcodes.

== Description ==

This plugin lets you build a library with snippets of HTML, PHP code or 
reoccurring text that you often use in your posts and pages. You can use
predefined variables to replace parts of the snippet on insert. All snippets are
available in the post editor via a button in the Visual and HTML modes. The 
snippet can be inserted as defined, or as a shortcode to keep flexibility for
updating the snippet. PHP code is supported for snippets inserted as shortcodes.

= Features =

* **Insert** All defined snippets is inserted from a button directly in the post
  editor.
* **Shortcodes** You can use this plugin to create your own shortcodes.
* **PHP** A shortcode snippet can optionally be evaluated as PHP code.
* **Buttons** The snippets are available in the viusal editor with a TinyMCE
  button and in the HTML editor with a quicktag button.
* **Admin** Easy to use administration panel where you can add, edit and remove
  snippets.
* **Variables** Each snippet can have as many custom variables as you like,
  which can be used on insert.
* **Import/Export** Snippets can be imported and exported between sites.
* **Uninstall** If you delete the plugin from your plugins panel it cleans up
  all data it has created in the Wordpress database. 

= Related Links =

* [Documentation](http://wpstorm.net/wordpress-plugins/post-snippets/ 
  "Complete usage instructions")
* [Support Forum](http://wordpress.org/tags/post-snippets?forum_id=10 
  "Use this for support and feature requests")

See the [Changelog](http://wordpress.org/extend/plugins/post-snippets/changelog/) 
for what's new. Available [Translations](http://wpstorm.net/wordpress-plugins/post-snippets/#translations).


== Installation ==

= Requirements =

* PHP version 5.2.4 or greater.
* WordPress version 3.0 or greater.

= Install =

1. Upload the 'post-snippets' folder  to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings -> Post Snippets and start entering your snippets.

= Uninstall =

1. Deactivate Post Snippets in the 'Plugins' menu in Wordpress.
2. Select Post Snippets in the 'Recently Active Plugins' section and select 
   'Delete' from the 'Bulk Actions' drop down menu.
3. This will delete all the plugin files from the server as well as erasing all
   options the plugin has stored in the database.

== Frequently Asked Questions ==

= Why do importing Snippets on a multi site install fail? =

Upload of zip files must be allowed, enable this in Sites Network Admin ->
Settings -> Upload Settings -> Upload file types.

= How can I use the content in an enclosed shortcode? =

If the shortcode is enclosed and contains content between the tags in a post. 
Example: `[shortcode]Some text[/shortcode]` the text within will be availble in
a variable called content. So in your snippet use {content} to display it. Don't
enter 'content' in the variable field, it's automatically assigned.

= Where can I send bug reports? =

Please visit the GitHub repository for [Post Snippet](https://github.com/artstorm/post-snippets)
and open an [issue](https://github.com/artstorm/post-snippets/issues).
Please create an issue that conforms with [necolas's guidelines](https://github.com/necolas/issue-guidelines).

= Where can I get support? =

Please visit the [Support Forum](http://wordpress.org/tags/post-snippets?forum_id=10 "Use this for support and feature requests") 
for questions, answers, support and feature requests.

= How can I disable the PHP Code Execution feature? =

﻿To disable the "PHP Code" execution feature in this plugin, add the following code your theme's functions.php file:
`add_filter('post_snippets_php_execution_enabled', '__return_false');`

This is useful if you are using this plugin for client sites, and don't want your clients to be able to use PHP code in a post snippet.

= How can I contribute to the plugin? =

If you want to contribute improved code or new features for the plugin. Please
open a pull request at the [Post Snippets GitHub repository](https://github.com/artstorm/post-snippets).
Please open a pull request that conforms with [necolas's guidelines](https://github.com/necolas/issue-guidelines).
Contributions are appreciated and encouraged.


== Screenshots ==

1. The Admin page where you create and manage snippets.
2. The TinyMCE button for Post Snippets.
3. The Post Snippet insert dialog.
4. Help system available on the admin page.


== Changelog ==

= Version X.X - XX XX 201X =
 * Allow other plugins or themes to disable the PHP Code execution feature using the new 'post_snippets_php_execution_enabled' filter.
 * Migrated to GitHub to maintain the code in development. [Post Snippets at GitHub](https://github.com/artstorm/post-snippets).
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
   [Read more](http://wpstorm.net/wordpress-plugins/post-snippets/#filters).

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
   [Read more](http://wpstorm.net/wordpress-plugins/post-snippets/#php).
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