<?php
namespace PostSnippets;

/**
 * Post Snippets WP Editor.
 *
 */
class WPEditor
{
    const TINYMCE_PLUGIN_NAME = 'post_snippets';

    public function __construct()
    {
        // Add TinyMCE button
        add_action('init', array(&$this, 'addTinymceButton'));

        // Add Editor QuickTag button:
        add_action(
            'admin_print_footer_scripts',
            array(&$this, 'addQuicktagButton'),
            100
        );

        add_action('admin_head', array(&$this, 'jqueryUiDialog'));
        add_action('admin_footer', array(&$this, 'addJqueryUiDialog'));

        // Adds the JS and HTML code in the header and footer for the jQuery
        // insert UI dialog in the editor
        add_action('admin_init', array(&$this, 'enqueueAssets'));
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
     * @since   Post Snippets 1.8.7
     */
    public function addTinymceButton()
    {
        // Don't bother doing this stuff if the current user lacks permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        // Add only in Rich Editor mode
        if (get_user_option('rich_editing') == 'true') {
            add_filter(
                'mce_external_plugins',
                array(&$this, 'registerTinymcePlugin')
            );
            add_filter(
                'mce_buttons',
                array(&$this, 'registerTinymceButton')
            );
        }
    }

    /**
     * Register TinyMCE button.
     *
     * Pushes the custom TinyMCE button into the array of with button names.
     * 'separator' or '|' can be pushed to the array as well. See the link
     * for all available TinyMCE controls.
     *
     * @see     wp-includes/class-wp-editor.php
     * @link    http://www.tinymce.com/wiki.php/Buttons/controls
     * @since   Post Snippets 1.8.7
     *
     * @param   array   $buttons    Filter supplied array of buttons to modify
     * @return  array               The modified array with buttons
     */
    public function registerTinymceButton($buttons)
    {
        if (!$this->isEditingPost()) {
            return $buttons;
        }

        array_push($buttons, 'separator', self::TINYMCE_PLUGIN_NAME);
        return $buttons;
    }

    /**
     * Register TinyMCE plugin.
     *
     * Adds the absolute URL for the TinyMCE plugin to the associative array of
     * plugins. Array structure: 'plugin_name' => 'plugin_url'
     *
     * @see     wp-includes/class-wp-editor.php
     * @since   Post Snippets 1.8.7
     *
     * @param   array   $plugins    Filter supplied array of plugins to modify
     * @return  array               The modified array with plugins
     */
    public function registerTinymcePlugin($plugins)
    {
        if (!$this->isEditingPost()) {
            return $plugins;
        }

        // Load the TinyMCE plugin, editor_plugin.js, into the array
        $plugins[self::TINYMCE_PLUGIN_NAME] =
            plugins_url('/tinymce/editor_plugin.js?ver=1.9', \PostSnippets::FILE);

        return $plugins;
    }


    /**
     * Adds a QuickTag button to the HTML editor.
     *
     * Compatible with WordPress 3.3 and newer.
     *
     * @see         wp-includes/js/quicktags.dev.js -> qt.addButton()
     * @since       Post Snippets 1.8.6
     */
    public function addQuicktagButton()
    {
        if (!$this->isEditingPost()) {
            return;
        }

        echo "\n<!-- START: Add QuickTag button for Post Snippets -->\n";
        ?>
        <script type="text/javascript" charset="utf-8">
            if (typeof QTags != 'undefined') {
                function qt_post_snippets() {
                    post_snippets_caller = 'html';
                    jQuery("#post-snippets-dialog").dialog("open");
                }
                QTags.addButton('post_snippets_id', 'Post Snippets', qt_post_snippets);
            }
        </script>
        <?php
        echo "\n<!-- END: Add QuickTag button for Post Snippets -->\n";
    }


    // -------------------------------------------------------------------------
    // JavaScript / jQuery handling for the post editor
    // -------------------------------------------------------------------------

    /**
     * Enqueues the necessary scripts and styles for the plugins
     *
     * @since       Post Snippets 1.7
     */
    public function enqueueAssets()
    {
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_style('wp-jquery-ui-dialog');

        # Adds the CSS stylesheet for the jQuery UI dialog
        $style_url = plugins_url('/assets/post-snippets.css', \PostSnippets::FILE);
        wp_register_style( 'post-snippets', $style_url, false, PS_VERSION );
        wp_enqueue_style('post-snippets');
    }

    /**
     * jQuery control for the dialog and Javascript needed to insert snippets into the editor
     *
     * @since       Post Snippets 1.7
     */
    public function jqueryUiDialog()
    {
        if (!$this->isEditingPost()) {
            return;
        }

        # Prepare the snippets and shortcodes into javascript variables
        # so they can be inserted into the editor, and get the variables replaced
        # with user defined strings.
        $snippets = get_option(\PostSnippets::OPTION_KEY, array());

        //Let other plugins change the snippets array
        $snippets = apply_filters('post_snippets_snippets_list', $snippets);

        $snippetStack = array();

	  if (is_array($snippets) || is_object($snippets)) {
		    foreach ( $snippets as $key => $snippet ) {
			    if ( $snippet['shortcode'] ) {
				    # Build a long string of the variables, ie: varname1={varname1} varname2={varname2}
				    # so {varnameX} can be replaced at runtime.
				    $var_arr   = explode( ",", $snippet['vars'] );
				    $variables = '';
				    if ( ! empty( $var_arr[0] ) ) {
					    foreach ( $var_arr as $var ) {
						    $var = $this->stripDefaultVal( $var );

						    $variables .= ' ' . $var . '="{' . $var . '}"';
					    }
				    }
				    $shortcode = $snippet['title'] . $variables;
				    array_push( $snippetStack, "var postsnippet_{$key} = '[" . $shortcode . "]';\n" );
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
				    $snippet = str_replace( chr( 13 ), '', str_replace( chr( 10 ), '\n', $snippet ) );
				    # Print out the variable containing the snippet
				    array_push( $snippetStack, "var postsnippet_{$key} = \"" . $snippet . "\";\n" );
			    }
		    }
	    }
        ?>

        <?php
        $data = array(
            'methods' => $this,
            'snippets' => $snippets,
            'snippetStack' => $snippetStack
        );
        echo View::render('jquery_ui_dialog_head', $data);
    }

    /**
     * Build jQuery UI Window.
     *
     * Creates the jQuery for Post Editor popup window, its snippet tabs and the
     * form fields to enter variables.
     *
     * @since       Post Snippets 1.7
     */
    public function addJqueryUiDialog()
    {
        if (!$this->isEditingPost()) {
            return;
        }

        $snippets = get_option(\PostSnippets::OPTION_KEY, array());

        //Let other plugins change the snippets array
        $snippets = apply_filters('post_snippets_snippets_list', $snippets);
        $data = array('snippets' => $snippets);

        echo View::render('jquery_ui_dialog_footer', $data);
    }

    /**
     * Determine if current screen is a post editing screen.
     *
     * @return boolean
     */
    protected function isEditingPost()
    {
        $settings = get_option(\PostSnippets::SETTINGS);
        $exclude = isset($settings['exclude_from_custom_editors']) ?
            $settings['exclude_from_custom_editors'] :
            false;

        // If we are not excluding from custom editors, always return true.
        if (!$exclude) {
            return true;
        }

        // If get_current_screen doesn't exist, we're on the frontend,
        // so return false, as it's then definately a custom editor
        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
        } else {
            $screen = false;
        }

        return is_object($screen) ? $screen->base == 'post' : false;
    }

    /**
     * Strip Default Value.
     *
     * Checks if a variable string contains a default value, and if it does it
     * will strip it away and return the string with only the variable name
     * kept.
     *
     * @since   Post Snippets 1.9.3
     * @param   string  $variable   The variable to check for default value
     * @return  string              The variable without any default value
     */
    public function stripDefaultVal($variable)
    {
        // Check if variable contains a default defintion
        $def_pos = strpos($variable, '=');

        if ($def_pos !== false) {
            $split = str_split($variable, $def_pos);
            $variable = $split[0];
        }
        return $variable;
    }
}