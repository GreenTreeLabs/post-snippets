<?php
/*
Plugin Name: Post Snippets
Plugin URI: http://wpstorm.net/wordpress-plugins/post-snippets/
Description: Build a library with snippets of HTML, PHP code or reoccurring text that you often use in your posts. Variables to replace parts of the snippet on insert can be used. The snippets can be inserted as-is or as shortcodes.
Version: 2.0.1
Author: Johan Steen
Author URI: http://johansteen.se/
Text Domain: post-snippets 

Copyright 2009-2012 Johan Steen  (email : artstorm [at] gmail [dot] com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Base Class.
 */
class Post_Snippets_Base {
	// Constants
	const PLUGIN_OPTION_KEY = 'post_snippets_options';
	const USER_OPTION_KEY   = 'post_snippets';

	static $php_execution_enabled;

	public function __construct() {
		// Allow other plugins to disable the PHP Code execution feature.
		// See http://wordpress.org/extend/plugins/post-snippets/faq/ for more details.
		self::$php_execution_enabled = apply_filters('post_snippets_php_execution_enabled', true);
	}
}

/**
 * Plugin Main Class.
 */
class Post_Snippets extends Post_Snippets_Base
{
	// Constants
	const TINYMCE_PLUGIN_NAME = 'post_snippets';

	// -------------------------------------------------------------------------

	public function __construct() {
		parent::__construct();
		// Define the domain and path for translations
		$rel_path = dirname(plugin_basename($this->get_File())).'/languages/';
		load_plugin_textdomain(	'post-snippets', false, $rel_path );

		$this->init_hooks();
	}

	/**
	 * Initializes the hooks for the plugin
	 */
	function init_hooks() {

		// Add TinyMCE button
		add_action('init', array(&$this, 'add_tinymce_button') );

		// Settings link on plugins list
		add_filter( 'plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2 );
		// Options Page
		add_action( 'admin_menu', array(&$this,'wp_admin') );

		$this->create_shortcodes();

		// Adds the JS and HTML code in the header and footer for the jQuery
		// insert UI dialog in the editor
		add_action( 'admin_init', array(&$this,'enqueue_assets') );
		add_action( 'admin_head', array(&$this,'jquery_ui_dialog') );
		add_action( 'admin_footer', array(&$this,'add_jquery_ui_dialog') );
		
		// Add Editor QuickTag button:
		// IF WordPress is 3.3 or higher, use the new refactored method to add
		// the quicktag button.
		// Start showing a deprecated message from version 1.9 of the plugin for
		// the old method. And remove it completely when the plugin hits 2.0.
		global $wp_version;
		if ( version_compare($wp_version, '3.3', '>=') ) {
			add_action( 'admin_print_footer_scripts', 
						array(&$this,'add_quicktag_button'), 100 );
		} else {
			add_action( 'edit_form_advanced', array(&$this,'add_quicktag_button_pre33') );
			add_action( 'edit_page_form', array(&$this,'add_quicktag_button_pre33') );
		}
	}


	/**
	 * Quick link to the Post Snippets Settings page from the Plugins page.
	 *
	 * @return	Array with all the plugin's action links
	 */
	function plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename( dirname($this->get_FILE()).'/post-snippets.php' ) ) {
			$links[] = '<a href="options-general.php?page=post-snippets/post-snippets.php">'.__('Settings', 'post-snippets').'</a>';
		 }
		return $links;
	}


	/**
	 * Enqueues the necessary scripts and styles for the plugins
	 *
	 * @since		Post Snippets 1.7
	 */
	function enqueue_assets() {
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );

		# Adds the CSS stylesheet for the jQuery UI dialog
		$style_url = plugins_url( '/assets/post-snippets.css', $this->get_FILE() );
		wp_register_style( 'post-snippets', $style_url, false, '2.0' );
		wp_enqueue_style( 'post-snippets' );
	}
	

	// -------------------------------------------------------------------------
	// WordPress Editor Buttons
	// -------------------------------------------------------------------------

	/**
	 * Add TinyMCE button.
	 *
	 * Adds filters to add custom buttons to the TinyMCE editor (Visual Editor)
	 * in WordPress.
	 *
	 * @since	Post Snippets 1.8.7
	 */
	public function add_tinymce_button()
	{
		// Don't bother doing this stuff if the current user lacks permissions
		if ( !current_user_can('edit_posts') &&
			 !current_user_can('edit_pages') )
			return;

		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', 
						array(&$this, 'register_tinymce_plugin') );
			add_filter('mce_buttons',
						array(&$this, 'register_tinymce_button') );
		}
	}

	/**
	 * Register TinyMCE button.
	 *
	 * Pushes the custom TinyMCE button into the array of with button names.
	 * 'separator' or '|' can be pushed to the array as well. See the link
	 * for all available TinyMCE controls.
	 *
	 * @see		wp-includes/class-wp-editor.php
	 * @link	http://www.tinymce.com/wiki.php/Buttons/controls
	 * @since	Post Snippets 1.8.7
	 *
	 * @param	array	$buttons	Filter supplied array of buttons to modify
	 * @return	array				The modified array with buttons
	 */
	public function register_tinymce_button( $buttons )
	{
		array_push( $buttons, 'separator', self::TINYMCE_PLUGIN_NAME );
		return $buttons;
	}

	/**
	 * Register TinyMCE plugin.
	 *
	 * Adds the absolute URL for the TinyMCE plugin to the associative array of
	 * plugins. Array structure: 'plugin_name' => 'plugin_url'
	 *
	 * @see		wp-includes/class-wp-editor.php
	 * @since	Post Snippets 1.8.7
	 *
	 * @param	array	$plugins	Filter supplied array of plugins to modify
	 * @return	array				The modified array with plugins
	 */
	public function register_tinymce_plugin( $plugins )
	{
		// Load the TinyMCE plugin, editor_plugin.js, into the array
		$plugins[self::TINYMCE_PLUGIN_NAME] = 
			plugins_url('/tinymce/editor_plugin.js?ver=1.9', $this->get_FILE());

		return $plugins;
	}

	/**
	 * Adds a QuickTag button to the HTML editor.
	 *
	 * Compatible with WordPress 3.3 and newer.
	 *
	 * @see			wp-includes/js/quicktags.dev.js -> qt.addButton()
	 * @since		Post Snippets 1.8.6
	 */
	public function add_quicktag_button()
	{
		// Only run the function on post edit screens
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ($screen->base != 'post')
				return;
		}

		echo "\n<!-- START: Add QuickTag button for Post Snippets -->\n";
		?>
		<script type="text/javascript" charset="utf-8">
			QTags.addButton( 'post_snippets_id', 'Post Snippets', qt_post_snippets );
			function qt_post_snippets() {
				post_snippets_caller = 'html';
				jQuery( "#post-snippets-dialog" ).dialog( "open" );
			}
		</script>
		<?php
		echo "\n<!-- END: Add QuickTag button for Post Snippets -->\n";
	}


	/**
	 * Adds a QuickTag button to the HTML editor.
	 *
	 * Used when running on WordPress lower than version 3.3.
	 *
	 * @see			wp-includes/js/quicktags.dev.js
	 * @since		Post Snippets 1.7
	 * @deprecated	Since 1.8.6
	 */
	function add_quicktag_button_pre33() {
		// Only run the function on post edit screens
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ($screen->base != 'post')
				return;
		}

		echo "\n<!-- START: Post Snippets QuickTag button -->\n";
		?>
		<script type="text/javascript" charset="utf-8">
		// <![CDATA[
			//edButton(id, display, tagStart, tagEnd, access, open)
			edbuttonlength = edButtons.length;
			edButtons[edbuttonlength++] = new edButton('ed_postsnippets', 'Post Snippets', '', '', '', -1);
		   (function(){
				  if (typeof jQuery === 'undefined') {
						 return;
				  }
				  jQuery(document).ready(function(){
						 jQuery("#ed_toolbar").append('<input type="button" value="Post Snippets" id="ed_postsnippets" class="ed_button" onclick="edOpenPostSnippets(edCanvas);" title="Post Snippets" />');
				  });
			}());
		// ]]>
		</script>
		<?php
		echo "\n<!-- END: Post Snippets QuickTag button -->\n";
	}


	// -------------------------------------------------------------------------
	// JavaScript / jQuery handling for the post editor
	// -------------------------------------------------------------------------

	/**
	 * jQuery control for the dialog and Javascript needed to insert snippets into the editor
	 *
	 * @since		Post Snippets 1.7
	 */
	public function jquery_ui_dialog()
	{
		// Only run the function on post edit screens
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ($screen->base != 'post')
				return;
		}

		echo "\n<!-- START: Post Snippets jQuery UI and related functions -->\n";
		echo "<script type='text/javascript'>\n";
		
		# Prepare the snippets and shortcodes into javascript variables
		# so they can be inserted into the editor, and get the variables replaced
		# with user defined strings.
		$snippets = get_option( self::PLUGIN_OPTION_KEY );
		foreach ($snippets as $key => $snippet) {
			if ($snippet['shortcode']) {
				# Build a long string of the variables, ie: varname1={varname1} varname2={varname2}
				# so {varnameX} can be replaced at runtime.
				$var_arr = explode(",",$snippet['vars']);
				$variables = '';
				if (!empty($var_arr[0])) {
					foreach ($var_arr as $var) {
						// '[test2 yet="{yet}" mupp=per="{mupp=per}" content="{content}"]';
						$var = $this->strip_default_val( $var );

						$variables .= ' ' . $var . '="{' . $var . '}"';
					}
				}
				$shortcode = $snippet['title'] . $variables;
				echo "var postsnippet_{$key} = '[" . $shortcode . "]';\n";
			} else {
				// To use $snippet is probably not a good naming convention here.
				// rename to js_snippet or something?
				$snippet = $snippet['snippet'];
				# Fixes for potential collisions:
				/* Replace <> with char codes, otherwise </script> in a snippet will break it */ 
				$snippet = str_replace( '<', '\x3C', str_replace( '>', '\x3E', $snippet ) );
				/* Escape " with \" */
				$snippet = str_replace( '"', '\"', $snippet );
				/* Remove CR and replace LF with \n to keep formatting */
				$snippet = str_replace( chr(13), '', str_replace( chr(10), '\n', $snippet ) );
				# Print out the variable containing the snippet
				echo "var postsnippet_{$key} = \"" . $snippet . "\";\n";
			}
		}
		?>
		
		jQuery(document).ready(function($){
			<?php
			# Create js variables for all form fields
			foreach ($snippets as $key => $snippet) {
				$var_arr = explode(",",$snippet['vars']);
				if (!empty($var_arr[0])) {
					foreach ($var_arr as $key_2 => $var) {
						$varname = "var_" . $key . "_" . $key_2;
						echo "var {$varname} = $( \"#{$varname}\" );\n";
					}
				}
			}
			?>
			
			var $tabs = $("#post-snippets-tabs").tabs();
			
			$(function() {
				$( "#post-snippets-dialog" ).dialog({
					autoOpen: false,
					modal: true,
					dialogClass: 'wp-dialog',
					buttons: {
						Cancel: function() {
							$( this ).dialog( "close" );
						},
						"Insert": function() {
							$( this ).dialog( "close" );
							var selected = $tabs.tabs('option', 'selected');
							<?php
							foreach ($snippets as $key => $snippet) {
							?>
								if (selected == <?php echo $key; ?>) {
									insert_snippet = postsnippet_<?php echo $key; ?>;
									<?php
									$var_arr = explode(",",$snippet['vars']);
									if (!empty($var_arr[0])) {
										foreach ($var_arr as $key_2 => $var) {
											$varname = "var_" . $key . "_" . $key_2; ?>
											insert_snippet = insert_snippet.replace(/\{<?php echo $this->strip_default_val( $var ); ?>\}/g, <?php echo $varname; ?>.val());
									<?php
											echo "\n";
										}
									}
									?>
								}
							<?php
							}
							?>

							// Decide what method to use to insert the snippet depending
							// from what editor the window was opened from
							if (post_snippets_caller == 'html') {
								// HTML editor in WordPress 3.3 and greater
								QTags.insertContent(insert_snippet);
							} else if (post_snippets_caller == 'html_pre33') {
								// HTML editor in WordPress below 3.3.
								edInsertContent(post_snippets_canvas, insert_snippet);
							} else {
								// Visual Editor
								post_snippets_canvas.execCommand('mceInsertContent', false, insert_snippet);
							}

						}
					},
					width: 500,
				});
			});
		});

// Global variables to keep track on the canvas instance and from what editor
// that opened the Post Snippets popup.
var post_snippets_canvas;
var post_snippets_caller = '';

/**
 * Used in WordPress lower than version 3.3.
 * Not used anymore starting with WordPress version 3.3.
 * Called from: add_quicktag_button_pre33()
 */
function edOpenPostSnippets(myField) {
		post_snippets_canvas = myField;
		post_snippets_caller = 'html_pre33';
		jQuery( "#post-snippets-dialog" ).dialog( "open" );
};
<?php
		echo "</script>\n";
		echo "\n<!-- END: Post Snippets jQuery UI and related functions -->\n";
	}

	/**
	 * Build jQuery UI Window.
	 *
	 * Creates the jQuery for Post Editor popup window, its snippet tabs and the
	 * form fields to enter variables.
	 *
	 * @since		Post Snippets 1.7
	 */
	public function add_jquery_ui_dialog()
	{
		// Only run the function on post edit screens
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ($screen->base != 'post')
				return;
		}

		echo "\n<!-- START: Post Snippets UI Dialog -->\n";
		// Setup the dialog divs
		echo "<div class=\"hidden\">\n";
		echo "\t<div id=\"post-snippets-dialog\" title=\"Post Snippets\">\n";
		// Init the tabs div
		echo "\t\t<div id=\"post-snippets-tabs\">\n";
		echo "\t\t\t<ul>\n";

		// Create a tab for each available snippet
		$snippets = get_option( self::PLUGIN_OPTION_KEY );
		foreach ($snippets as $key => $snippet) {
			echo "\t\t\t\t";
			echo "<li><a href=\"#ps-tabs-{$key}\">{$snippet['title']}</a></li>";
			echo "\n";
		}
		echo "\t\t\t</ul>\n";

		// Create a panel with form fields for each available snippet
		foreach ($snippets as $key => $snippet) {
			echo "\t\t\t<div id=\"ps-tabs-{$key}\">\n";

			// Print a snippet description is available
			if ( isset($snippet['description']) )
				echo "\t\t\t\t<p class=\"howto\">" . $snippet['description'] . "</p>\n";

			// Get all variables defined for the snippet and output them as
			// input fields
			$var_arr = explode(',', $snippet['vars']);
			if (!empty($var_arr[0])) {
				foreach ($var_arr as $key_2 => $var) {
					// Default value exists?
					$def_pos = strpos( $var, '=' );
					if ( $def_pos !== false ) {
						$split = explode( '=', $var );
						$var = $split[0];
						$def = $split[1];
					} else {
						$def = '';
					}
					echo "\t\t\t\t<label for=\"var_{$key}_{$key_2}\">{$var}:</label>\n";
					echo "\t\t\t\t<input type=\"text\" id=\"var_{$key}_{$key_2}\" name=\"var_{$key}_{$key_2}\" value=\"{$def}\" style=\"width: 190px\" />\n";
					echo "\t\t\t\t<br/>\n";
				}
			} else {
				// If no variables and no description available, output a text
				// to inform the user that it's an insert snippet only.
				if ( empty($snippet['description']) )
					echo "\t\t\t\t<p class=\"howto\">" . __('This snippet is insert only, no variables defined.', 'post-snippets') . "</p>\n";
			}
			echo "\t\t\t</div><!-- #ps-tabs-{$key} -->\n";
		}
		// Close the tabs and dialog divs
		echo "\t\t</div><!-- #post-snippets-tabs -->\n";
		echo "\t</div><!-- #post-snippets-dialog -->\n";
		echo "</div><!-- .hidden -->\n";

		echo "<!-- END: Post Snippets UI Dialog -->\n\n";
	}

	/**
	 * Strip Default Value.
	 *
	 * Checks if a variable string contains a default value, and if it does it 
	 * will strip it away and return the string with only the variable name
	 * kept.
	 *
	 * @since	Post Snippets 1.9.3
	 * @param	string	$variable	The variable to check for default value
	 * @return	string				The variable without any default value
	 */
	public function strip_default_val( $variable )
	{
		// Check if variable contains a default defintion
		$def_pos = strpos( $variable, '=' );

		if ( $def_pos !== false ) {
			$split = str_split( $variable, $def_pos );
			$variable = $split[0];
		}
		return $variable;
	}

	// -------------------------------------------------------------------------
	// Shortcode
	// -------------------------------------------------------------------------

	/**
	 * Create the functions for shortcodes dynamically and register them
	 */
	function create_shortcodes() {
		$snippets = get_option( self::PLUGIN_OPTION_KEY );
		if (!empty($snippets)) {
			foreach ($snippets as $snippet) {
				// If shortcode is enabled for the snippet, and a snippet has been entered, register it as a shortcode.
				if ( $snippet['shortcode'] && !empty($snippet['snippet']) ) {
					
					$vars = explode(",",$snippet['vars']);
					$vars_str = '';
					foreach ($vars as $var) {
						$vars_str = $vars_str . '"'.$var.'" => "",';
					}

					// Get the wptexturize setting
					$texturize = isset( $snippet["wptexturize"] ) ? $snippet["wptexturize"] : false;

					add_shortcode($snippet['title'], create_function('$atts,$content=null', 
								'$shortcode_symbols = array('.$vars_str.');
								extract(shortcode_atts($shortcode_symbols, $atts));
								
								$attributes = compact( array_keys($shortcode_symbols) );
								
								// Add enclosed content if available to the attributes array
								if ( $content != null )
									$attributes["content"] = $content;
								

								$snippet = \''. addslashes($snippet["snippet"]) .'\';
								$snippet = str_replace("&", "&amp;", $snippet);

								foreach ($attributes as $key => $val) {
									$snippet = str_replace("{".$key."}", $val, $snippet);
								}

								// Handle PHP shortcodes
								$php = "'. $snippet["php"] .'";
								if ($php == true) {
									$snippet = Post_Snippets::php_eval( $snippet );
								}

								// Strip escaping and execute nested shortcodes
								$snippet = do_shortcode(stripslashes($snippet));

								// WPTexturize the Snippet
								$texturize = "'. $texturize .'";
								if ($texturize == true) {
									$snippet = wptexturize( $snippet );
								}

								return $snippet;') );
				}
			}
		}
	}

	/**
	 * Evaluate a snippet as PHP code.
	 *
	 * @since	Post Snippets 1.9
	 * @param	string	$content	The snippet to evaluate
	 * @return	string				The result of the evaluation
	 */
	public static function php_eval( $content )
	{
		if ( !self::$php_execution_enabled )
			return $content;

		$content = stripslashes($content);

		ob_start();
		eval ($content);
		$content = ob_get_clean();

		return addslashes( $content );
	}


	// -------------------------------------------------------------------------
	// Admin
	// -------------------------------------------------------------------------

	/**
	 * The Admin Page.
	 */
	function wp_admin()	{
		if ( current_user_can('manage_options') ) {
			// If user can manage options, display the admin page
			$option_page = add_options_page( 'Post Snippets Options', 'Post Snippets', 'administrator', $this->get_FILE(), array(&$this, 'options_page') );
			if ( $option_page and class_exists('Post_Snippets_Help') ) {
				$help = new Post_Snippets_Help( $option_page );
			}
		} else {
			// If user can't manage options, but can edit posts, display the overview page
			$option_page = add_options_page( 'Post Snippets', 'Post Snippets', 'edit_posts', $this->get_FILE(), array(&$this, 'overview_page') );
		}
	}

	/**
	 * The options Overview page.
	 *
	 * For users without manage_options cap but with edit_posts cap. A read-only
	 * view.
	 *
	 * @since	Post Snippets 1.9.7
	 */
	public function overview_page()
	{
		$settings = new Post_Snippets_Settings();
		$settings->render( 'overview' );
	}

	/**
	 * The options Admin page.
	 *
	 * For users with manage_options capability.
	 */
	public function options_page()
	{
		$settings = new Post_Snippets_Settings();
		$settings->render( 'options' );
	}
	

	// -------------------------------------------------------------------------
	// Helpers
	// -------------------------------------------------------------------------

	/**
	 * Get __FILE__ with no symlinks.
	 *
	 * For development purposes mainly. Returns __FILE__ without resolved 
	 * symlinks to be used when __FILE__ is needed while resolving symlinks
	 * breaks WP functionaly, so the actual WordPress path is returned instead.
	 * This makes it possible for all WordPress versions to point to the same
	 * plugin folder for faster testing of the plugin in different WordPress
	 * versions.
	 *
	 * @since	Post Snippets 1.9
	 * @return	The __FILE__ constant without resolved symlinks.
	 */
	private function get_FILE()
	{
		$dev_path = 'E:\Code\WordPress';
		$result = strpos( __FILE__, $dev_path );

		if ( $result === false ) {
			return __FILE__;
		} else {
			return str_replace($dev_path, WP_PLUGIN_DIR, __FILE__);
		}
	}

	/**
	 * Allow snippets to be retrieved directly from PHP.
	 *
	 * @since	Post Snippets 1.8.9.1
	 *
	 * @param	string		$snippet_name
	 *			The name of the snippet to retrieve
	 * @param	string		$snippet_vars
	 *			The variables to pass to the snippet, formatted as a query string.
	 * @return	string
	 *			The Snippet
	 */
	public function get_snippet( $snippet_name, $snippet_vars = '' )
	{
		$snippets = get_option( self::PLUGIN_OPTION_KEY );
		for ($i = 0; $i < count($snippets); $i++) {
			if ($snippets[$i]['title'] == $snippet_name) {
				parse_str( htmlspecialchars_decode($snippet_vars), $snippet_output );
				$snippet = $snippets[$i]['snippet'];
				$var_arr = explode(",",$snippets[$i]['vars']);

				if ( !empty($var_arr[0]) ) {
					for ($j = 0; $j < count($var_arr); $j++) {
						$snippet = str_replace("{".$var_arr[$j]."}", $snippet_output[$var_arr[$j]], $snippet);
					}
				}
			}
		}
		return $snippet;
	}
}


// -----------------------------------------------------------------------------
// Start the plugin
// -----------------------------------------------------------------------------

// Check the host environment
$test_post_snippets_host = new Post_Snippets_Host_Environment();

// If environment is up to date, start the plugin
if($test_post_snippets_host->passed) {
	// Load external classes
	if (is_admin()) {
		require plugin_dir_path(__FILE__).'classes/settings.php';
		require plugin_dir_path(__FILE__).'classes/help.php';
		require plugin_dir_path(__FILE__).'classes/import-export.php';
	}

	add_action(
		'plugins_loaded', 
		create_function( 
			'',
			'global $post_snippets; $post_snippets = new Post_Snippets();'
		)
	);
}


/**
 * Post Snippets Host Environment.
 *
 * Checks that the host environment fulfils the requirements of Post Snippets.
 * This class is designed to work with PHP versions below 5, to make sure it's
 * always executed.
 *
 * - PHP Version 5.2.4 is on par with the requirements for WordPress 3.3.
 *
 * @since	Post Snippets 1.8.8
 */
class Post_Snippets_Host_Environment
{
	// Minimum versions required
	var $MIN_PHP_VERSION	= '5.2.4';
	var $MIN_WP_VERSION		= '3.0';
	var $passed				= true;

	/**
	 * Constructor.
	 *
	 * Checks PHP and WordPress versions. If any check failes, a system notice
	 * is added and $passed is set to fail, which can be checked before trying
	 * to create the main class.
	 */
	function Post_Snippets_Host_Environment()
	{
		// Check if PHP is too old
		if (version_compare(PHP_VERSION, $this->MIN_PHP_VERSION, '<')) {
			// Display notice
			add_action( 'admin_notices', array(&$this, 'php_version_error') );
			$this->passed = false;
		}

		// Check if WordPress is too old
		global $wp_version;
		if ( version_compare($wp_version, $this->MIN_WP_VERSION, '<') ) {
			add_action( 'admin_notices', array(&$this, 'wp_version_error') );
			$this->passed = false;
		}
	}

	/**
	 * Displays a warning when installed on an old PHP version.
	 */
	function php_version_error() {
		echo '<div class="error"><p><strong>';
		printf(
			'Error: Post Snippets requires PHP version %1$s or greater.<br/>'.
			'Your installed PHP version: %2$s',
			$this->MIN_PHP_VERSION, PHP_VERSION);
		echo '</strong></p></div>';
	}

	/**
	 * Displays a warning when installed in an old Wordpress version.
	 */
	function wp_version_error() {
		echo '<div class="error"><p><strong>';
		printf(
			'Error: Post Snippets requires WordPress version %s or greater.',
			$this->MIN_WP_VERSION );
		echo '</strong></p></div>';
	}
}

// -----------------------------------------------------------------------------
// Helper functions
// -----------------------------------------------------------------------------

/**
 * Allow snippets to be retrieved directly from PHP.
 * This function is a wrapper for Post_Snippets::get_snippet().
 *
 * @since	Post Snippets 1.6
 *
 * @param	string		$snippet_name
 *			The name of the snippet to retrieve
 * @param	string		$snippet_vars
 *			The variables to pass to the snippet, formatted as a query string.
 * @return	string
 *			The Snippet
 */
function get_post_snippet( $snippet_name, $snippet_vars = '' ) {
	global $post_snippets;
	return $post_snippets->get_snippet( $snippet_name, $snippet_vars );
}
