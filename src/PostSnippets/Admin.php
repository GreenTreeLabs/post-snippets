<?php

namespace PostSnippets;

/**
 * Post Snippets Settings.
 *
 * Class that renders out the HTML for the settings screen and contains helpful
 * methods to simply the maintainance of the admin screen.
 *
 */
class Admin {
    /**
     * Plugin settings.
     *
     * @var array
     */
    protected $settings;

    /**
     * Defines hooks and filters for admin page.
     */
    public function __construct() {
        add_action( 'admin_menu', array( &$this, 'menu' ) );
        add_action( 'admin_init', array( &$this, 'init' ) );
        add_action( 'current_screen', array( &$this, 'addHeaderXss' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( PS_PATH . 'post-snippets.php' ), array(
            $this,
            'actionLinks'
        ) );

        // Newsletter sign-up admin notice
        add_action( 'admin_notices', array( $this, 'admin_notice_newsletter' ) );

        // Get started admin notice
        add_action( 'admin_notices', array( $this, 'admin_notice_get_started' ) );

        add_action( 'wp_ajax_update_post_snippets_order', array( $this, 'update_snippets_order' ) );
        add_action( 'wp_ajax_update_post_snippet_title', array( $this, 'update_post_snippet_title' ) );
    }


    // -------------------------------------------------------------------------
    // Setup
    // -------------------------------------------------------------------------

    /**
     * Register the administration page.
     *
     * @return void
     */
    public function menu() {
        $capability = 'manage_options';
        if ( defined( 'POST_SNIPPETS_ALLOW_EDIT_POSTS' )
             and current_user_can( 'edit_posts' )
        ) {
            $allowed    = true;
            $capability = 'edit_posts';
        }

        if ( current_user_can( 'manage_options' ) or isset( $allowed ) ) {
            $optionPage = add_options_page(
                'Post Snippets',
                'Post Snippets',
                $capability,
                \PostSnippets::FILE,
                array( &$this, 'optionsPage' )
            );
            new Help( $optionPage );
        } else {
            $optionPage = add_options_page(
                'Post Snippets',
                'Post Snippets',
                'edit_posts',
                \PostSnippets::FILE,
                array( &$this, 'overviewPage' )
            );
        }

        add_action(
            'admin_print_scripts-' . $optionPage,
            array( &$this, 'scripts' )
        );
    }

    /**
     * Initialize assets for the administration page.
     *
     * @return void
     */
    public function init() {
        wp_register_script( 'post-snippets', plugins_url( '/assets/post-snippets.js', \PostSnippets::FILE ), array( 'jquery' ), PS_VERSION, true );
        if ( postsnippets_fs()->is__premium_only() ) {
            wp_register_script( 'post-snippets-pro', plugins_url( '/assets/post-snippets-pro.js', \PostSnippets::FILE ), array(
                'jquery',
                'post-snippets'
            ), PS_VERSION, true );
        }

        $this->registerSettings();
    }

    /**
     * Enqueue scripts to be loaded.
     *
     * @return void
     */
    public function scripts() {
        // Localize the strings in the script
        $translation_array = array(
            'invalid_shortcode' => __( 'Invalid shortcode name', 'post-snippets' )
        );
        wp_localize_script( 'post-snippets', 'post_snippets', $translation_array );

        // Add CSS for Pro features page
        $features_style_url = plugins_url( '/assets/features.css', \PostSnippets::FILE );
        wp_register_style( 'post-snippets-features', $features_style_url, array(), PS_VERSION );
        wp_enqueue_style( 'post-snippets-features' );

        // Add CSS for newsletter opt-in
        $features_style_url = plugins_url( '/assets/newsletter.css', \PostSnippets::FILE );
        wp_register_style( 'post-snippets-newsletter', $features_style_url, array(), PS_VERSION );
        wp_enqueue_style( 'post-snippets-newsletter' );

        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'underscore' );

        if ( postsnippets_fs()->is__premium_only() ) {
            wp_enqueue_script( 'post-snippets-pro' );
        }

        wp_enqueue_script( 'post-snippets' );
    }

    /**
     * Add X-XSS-Protection header.
     *
     * Newer versions of Chrome does not allow form tags to be submitted in the
     * forms. This header disables that functionlity on the Post Snippets admin
     * screen only.
     */
    public function addHeaderXss( $current_screen ) {
        if ( $current_screen->base == 'settings_page_post-snippets/post-snippets' ) {
            header( 'X-XSS-Protection: 0' );
        }
    }

    /**
     * Quick link to the Post Snippets Settings page from the Plugins page.
     *
     * @param array $links Array of all plugin links
     *
     * @return array $links Array with all the plugin's action links
     */
    public function actionLinks( $links ) {

        $links[] = '<a href="' . PS_MAIN_PAGE_URL . '">' . __( 'Settings', 'post-snippets' ) . '</a>';

        return $links;
    }


    // -------------------------------------------------------------------------
    // Handle form submissions
    // -------------------------------------------------------------------------

    /**
     * Add New Snippet.
     */
    private function add() {
        if ( isset( $_POST['add-snippet'] )
             && isset( $_POST['update_snippets_nonce'] )
             && wp_verify_nonce( $_POST['update_snippets_nonce'], 'update_snippets' )
        ) {
            $snippets = get_option( \PostSnippets::OPTION_KEY );
            if ( empty( $snippets ) ) {
                $snippets = array();
            }

            array_push(
                $snippets,
                array(
                    'title'       => 'Untitled',
                    'vars'        => '',
                    'description' => '',
                    'shortcode'   => false,
                    'php'         => false,
                    'wptexturize' => false,
                    'snippet'     => ''
                )
            );

            update_option( \PostSnippets::OPTION_KEY, $snippets );
            $this->message(
                __(
                    'A snippet named Untitled has been added.',
                    'post-snippets'
                )
            );
        }
    }

    /**
     * Delete Snippet/s.
     */
    private function delete() {
        if ( isset( $_POST['delete-snippets'] )
             && isset( $_POST['update_snippets_nonce'] )
             && wp_verify_nonce( $_POST['update_snippets_nonce'], 'update_snippets' )
        ) {
            $snippets = get_option( \PostSnippets::OPTION_KEY );

            if ( empty( $snippets ) || ! isset( $_POST['checked'] ) ) {
                $this->message(
                    __( 'Nothing selected to delete.', 'post-snippets' )
                );

                return;
            }

            $delete      = $_POST['checked'];
            $newsnippets = array();
            foreach ( $snippets as $key => $snippet ) {
                if ( in_array( $key, $delete ) == false ) {
                    array_push( $newsnippets, $snippet );
                }
            }

            update_option( \PostSnippets::OPTION_KEY, $newsnippets );
            $this->message(
                __(
                    'Selected snippets have been deleted.',
                    'post-snippets'
                )
            );
        }
    }

    /**
     * Update Snippet/s.
     */
    private function update() {
        if ( isset( $_POST['update-snippets'] )
             && isset( $_POST['update_snippets_nonce'] )
             && wp_verify_nonce( $_POST['update_snippets_nonce'], 'update_snippets' )
        ) {
            $default            = array(
                'title'       => '',
                'snippet'     => '',
                'description' => '',
                'vars'        => '',
                'shortcode'   => '0',
                'php'         => '0',
                'wptexturize' => '0',
            );
            $formatted_snippets = array();
            if ( ! empty( $_POST['snippets'] ) ) {
                $snippets = map_deep( $_POST['snippets'], 'trim' );
                $i = 0;

                foreach ( $snippets as $snippet ){
                    if ( empty( $snippets ) ) {
                        continue;
                    }
                    $snippet = wp_parse_args( $snippet, $default );

                    $snippet['title'] = str_replace( ' ', '', $snippet['title'] );

                    if ( defined( 'POST_SNIPPETS_DISABLE_PHP' ) ) {
                        $snippet['php'] = '0';
                    }
                    $snippet['snippet']     = wp_specialchars_decode( trim( stripslashes( $snippet['snippet'] ) ), ENT_NOQUOTES );
                    $snippet['description'] = wp_specialchars_decode( trim( stripslashes( $snippet['description'] ) ), ENT_NOQUOTES );

                    $formatted_snippets[ $i ] = $snippet;
                    $i++;
                }

            }

            update_option( \PostSnippets::OPTION_KEY, $formatted_snippets );
            $this->message( __( 'Snippets have been updated.', 'post-snippets' ) );
        }
    }

    /**
     * Update User Option.
     *
     * Sets the per user option for the read-only overview page.
     *
     * @since   Post Snippets 1.9.7
     */
    private function setUserOptions() {
        if ( isset( $_POST['post_snippets_user_nonce'] )
             && wp_verify_nonce( $_POST['post_snippets_user_nonce'], 'post_snippets_user_options' )
        ) {
            $id     = get_current_user_id();
            $render = isset( $_POST['render'] ) ? true : false;
            update_user_meta( $id, \PostSnippets::USER_META_KEY, $render );
        }
    }

    /**
     * Get User Option.
     *
     * Gets the per user option for the read-only overview page.
     *
     * @since   Post Snippets 1.9.7
     * @return  boolean If overview should be rendered on output or not
     */
    private function getUserOptions() {
        $id      = get_current_user_id();
        $options = get_user_meta( $id, \PostSnippets::USER_META_KEY, true );

        return $options;
    }


    // -------------------------------------------------------------------------
    // HTML generation for option pages
    // -------------------------------------------------------------------------

    /**
     * Display Flashing Message.
     *
     * @param   string $message Message to display to the user.
     */
    private function message( $message ) {
        if ( $message ) {
            echo "<div class='updated'><p><strong>{$message}</strong></p></div>";
        }
    }

    /**
     * Creates the snippets administration page.
     *
     * For users with manage_options capability (admin, super admin).
     *
     * @since   Post Snippets 1.8.8
     */
    public function optionsPage() {
        // Handle Form Submits
        $this->add();
        $this->delete();
        $this->update();

        // Header
        echo '
        <!-- Create a header in the default WordPress \'wrap\' container -->
        <div class="wrap">
            <div id="icon-plugins" class="icon32"></div>
            <h2>Post Snippets</h2>
        ';

        // Tabs
        $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'snippets';
        $base_url   = '?page=' . PS_DIRECTORY . '/post-snippets.php&amp;tab=';
        $tabs       = array(
            'snippets' => __( 'Manage Snippets', 'post-snippets' ),
            'options'  => __( 'Options', 'post-snippets' ),
            'tools'    => __( 'Import/Export', 'post-snippets' ),
        );

        if ( postsnippets_fs()->is_not_paying() ) {
            $tabs['features'] = __( 'Pro features', 'post-snippets' );
        }

        echo '<h2 class="nav-tab-wrapper">';
        foreach ( $tabs as $tab => $title ) {
            $active = ( $active_tab == $tab ) ? ' nav-tab-active' : '';
            echo "<a href='{$base_url}{$tab}' class='nav-tab {$active} tab-{$tab}'>{$title}</a>";
        }
        echo '</h2>';

        // Tab content
        if ( $active_tab == 'snippets' ) {
            $this->tabSnippets();
        } elseif ( $active_tab == 'options' ) {
            $this->tabOptions();
        } elseif ( $active_tab == 'tools' ) {
            $this->tabTools();
        } else {
            $this->tabFeatures();
        }

        // Close it
        echo '</div>';
    }

    /**
     * Tab to Manage Snippets.
     *
     * @since   Post Snippets 2.0
     */
    private function tabSnippets() {
        echo '<p class="description post-snippets-documentation-note">';
        _e( 'Click \'Help\' in the top right for the documentation!', 'post-snippets' );
        echo '</p>';

        $data = array();
        echo View::render( 'admin_snippets', $data );
    }

    /**
     * Tab to set options for the plugin.
     *
     * @return void
     */
    private function tabOptions() {
        echo '<p class="description post-snippets-documentation-note">';
        _e( 'Click \'Help\' in the top right for the documentation!', 'post-snippets' );
        echo '</p>';

        $data = array();
        echo View::render( 'admin_options', $data );
    }

    /**
     * Tab for Import/Export
     *
     * @since   Post Snippets 2.0
     */
    private function tabTools() {
        echo '<p class="description post-snippets-documentation-note">';
        _e( 'Click \'Help\' in the top right for the documentation!', 'post-snippets' );
        echo '</p>';

        $ie = new ImportExport();

        // Create header and export html form
        printf( "<h3>%s</h3>", __( 'Import/Export', 'post-snippets' ) );
        printf( "<h4>%s</h4>", __( 'Export', 'post-snippets' ) );
        echo '<form method="post">';
        echo '<p>';
        _e( 'Export your snippets for backup or to import them on another site.', 'post-snippets' );
        echo '</p>';
        printf( "<input type='submit' class='button' name='postsnippets_export' value='%s' />", __( 'Export Snippets', 'post-snippets' ) );
        echo '</form>';

        // Export logic, and import html form and logic
        $ie->exportSnippets();
        echo $ie->importSnippets();
    }

    /**
     * Tab for Pro features
     *
     * @since   Post Snippets 2.5.4
     */
    private function tabFeatures() {
        $features = new Features();

        echo $features->showFeatures();

    }


    /**
     * Creates a read-only overview page.
     *
     * For users with edit_posts capability but without manage_options
     * capability.
     *
     * @since   Post Snippets 1.9.7
     */
    public function overviewPage() {
        // Header
        echo '<div class="wrap">';
        echo '<h2>Post Snippets</h2>';
        echo '<p>';
        _e( 'This is an overview of all snippets defined for this site. These snippets are inserted into posts from the post editor using the Post Snippets button. You can choose to see the snippets here as-is or as they are actually rendered on the website. Enabling rendered snippets for this overview might look strange if the snippet have dependencies on variables, CSS or other parameters only available on the frontend. If that is the case it is recommended to keep this option disabled.', 'post-snippets' );
        echo '</p>';

        // Form
        $this->setUserOptions();
        $render = $this->getUserOptions();

        echo '<form method="post" action="">';
        wp_nonce_field( 'post_snippets_user_options', 'post_snippets_user_nonce' );

        $this->checkbox( __( 'Display rendered snippets', 'post-snippets' ), 'render', $render );
        $this->submit( 'update-post-snippets-user', __( 'Update', 'post-snippets' ) );
        echo '</form>';

        // Snippet List
        $snippets = get_option( \PostSnippets::OPTION_KEY );
        if ( ! empty( $snippets ) ) {
            foreach ( $snippets as $key => $snippet ) {
                echo "<hr style='border: none;border-top:1px dashed #aaa; margin:24px 0;' />";

                echo "<h3>{$snippet['title']}";
                if ( $snippet['description'] ) {
                    echo "<span class='description'> {$snippet['description']}</span>";
                }
                echo "</h3>";

                if ( $snippet['vars'] ) {
                    printf( "<strong>%s:</strong> {$snippet['vars']}<br/>", __( 'Variables', 'post-snippets' ) );
                }

                // echo "<strong>Variables:</strong> {$snippet['vars']}<br/>";

                $options = array();
                if ( $snippet['shortcode'] ) {
                    array_push( $options, 'Shortcode' );
                }
                if ( $snippet['php'] ) {
                    array_push( $options, 'PHP' );
                }
                if ( $snippet['wptexturize'] ) {
                    array_push( $options, 'wptexturize' );
                }
                if ( $options ) {
                    printf( "<strong>%s:</strong> %s<br/>", __( 'Options', 'post-snippets' ), implode( ', ', $options ) );
                }

                printf( "<br/><strong>%s:</strong><br/>", __( 'Snippet', 'post-snippets' ) );
                if ( $render ) {
                    echo do_shortcode( $snippet['snippet'] );
                } else {
                    echo "<code>";
                    echo nl2br( htmlspecialchars( $snippet['snippet'], ENT_NOQUOTES ) );
                    echo "</code>";
                }
            }
        }
        // Close
        echo '</div>';
    }


    // -------------------------------------------------------------------------
    // Register and callbacks for the options tab
    // -------------------------------------------------------------------------

    /**
     * Register settings for the options tab.
     *
     * @return void
     */
    protected function registerSettings() {
        $this->settings = get_option( \PostSnippets::SETTINGS );

        register_setting(
            \PostSnippets::SETTINGS,
            \PostSnippets::SETTINGS
        );

        add_settings_section(
            'general_section',
            __( 'General', 'post-snippets' ),
            null,
            'post-snippets'
        );

        add_settings_field(
            'exclude_from_custom_editors',
            __( 'Exclude from Custom Editors', 'post-snippets' ),
            array( $this, 'cbExcludeFromCustomEditors' ),
            'post-snippets',
            'general_section',
            array(
                'id'          => 'exclude_from_custom_editors',
                'label_for'   => 'exclude_from_custom_editors',
                'description' => __( 'Checking this only includes Post Snippets on standard WordPress post editing screens.', 'post-snippets' )
            )
        );
    }

    /**
     * Callback for HTML generator for exlusion of custom editors.
     *
     * @param  array $args
     *
     * @return  void
     */
    public function cbExcludeFromCustomEditors( $args ) {
        $checked = isset( $this->settings[ $args['id'] ] ) ?
            $this->settings[ $args['id'] ] :
            false;

        echo "<input type='checkbox' id='{$args['id']}' ";
        echo "name='" . \PostSnippets::SETTINGS . "[{$args['id']}]' value='1' ";
        if ( $checked ) {
            echo 'checked ';
        }
        echo " />";

        echo "<span class='description'>{$args['description']}</span>";
    }


    // -------------------------------------------------------------------------
    // HTML and Form element methods for Snippets form
    // -------------------------------------------------------------------------

    /**
     * Checkbox.
     *
     * Renders the HTML for an input checkbox.
     *
     * @param   string  $label The label rendered to screen
     * @param   string  $name The unique name and id to identify the input
     * @param   boolean $checked If the input is checked or not
     *
     * @return  void
     */
    public static function checkbox( $label, $name, $checked ) {
        echo "<label for=\"{$name}\">";
        printf( '<input type="checkbox" name="%1$s" id="%1$s" value="true"', $name );
        if ( $checked ) {
            echo ' checked';
        }
        echo ' />';
        echo " {$label}</label><br/>";
    }

    /**
     * Submit.
     *
     * Renders the HTML for a submit button.
     *
     * @since   Post Snippets 1.9.7
     *
     * @param   string  $name The name that identifies the button on submit
     * @param   string  $label The label rendered on the button
     * @param   string  $class Optional. Button class. Default: button-primary
     * @param   boolean $wrap Optional. Wrap in a submit div. Default: true
     *
     * @return  void
     */
    public static function submit( $name, $label, $class = 'button-primary', $wrap = true ) {
        $btn = sprintf( '<input type="submit" name="%s" value="%s" class="%s" />&nbsp;&nbsp;&nbsp;', $name, $label, $class );

        if ( $wrap ) {
            $btn = "<div class=\"submit\">{$btn}</div>";
        }

        echo $btn;
    }

    /**
     *
     * Show newsletter opt-in, only in Post Snippets.
     * Not on Pro features tab/page.
     * Not when user selected to Hide opt-in.
     *
     * @since   2.5.4
     */
    public function admin_notice_newsletter() {

        // Hide newsletter opt-in if option is true
        if ( get_option( 'ps_hide_admin_notice_newsletter' ) == true ) {
            return;
        }

        // Set option if "hide" button click detected (custom querystring value set to 1).
        if ( ! empty( $_REQUEST['ps-dismiss-newsletter-nag'] ) ) {
            update_option( 'ps_hide_admin_notice_newsletter', true );

            return;
        }

        // Show newsletter notice.
        if ( strpos( get_current_screen()->id, '/post-snippets' ) !== false ) {
            $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'snippets';
            if ( $active_tab != 'features' ) {
                include_once( PS_PATH . '/views/admin_notice_newsletter.php' );
            }

        }
    }


    /**
     *
     * Show 'Get started' admin notice', everywhere.
     * Not when user already clicked or dismissed.
     *
     * @since   2.5.4
     */
    public function admin_notice_get_started() {

        // Hide newsletter opt-in if option is true
        if ( get_option( 'ps_hide_admin_notice_get_started' ) == true ) {
            return;
        }

        // Set option if "hide" button click detected (custom query string value set to 1).
        if ( ! empty( $_REQUEST['ps-dismiss-get-started-nag'] ) ) {
            update_option( 'ps_hide_admin_notice_get_started', true );

            return;
        }

        // Show newsletter notice.
        if ( strpos( get_current_screen()->id, '/post-snippets' ) == false ) {
            $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'snippets';
            if ( $active_tab != 'features' ) {
                include_once( PS_PATH . '/views/admin_notice_get_started.php' );
            }

        }
    }

    /**
     * Save Updated sorting
     */
    public function update_snippets_order() {
        if ( empty( $_POST['order'] ) || ! is_array( $_POST['order'] ) ) {
            wp_send_json_error('order data not received');
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error('permission denied');
        }
        $orders   = array_map( 'intval', $_POST['order'] );
        $snippets = get_option( 'post_snippets_options', array() );
        if ( empty( $snippets ) ) {
            wp_send_json_error('snippets empty');
        }
        $updated_order = array();
        foreach ( $orders as $order ) {
            if ( isset( $snippets[ $order ] ) ) {
                $updated_order[] = $snippets[ $order ];
            }
        }
        update_option( 'post_snippets_options', $updated_order );
        wp_send_json_success('success');
    }

    /**
     * Save Updated title
     */
    public function update_post_snippet_title() {
        if ( ! isset( $_POST['key'] ) || empty( $_POST['title'] ) ) {
            wp_send_json_error();
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error();
        }
        $key      = intval( $_POST['key'] );
        $title    = sanitize_title( $_POST['title'] );
        $snippets = get_option( 'post_snippets_options', array() );
        if ( empty( $snippets ) ) {
            wp_send_json_error();
        }
        if ( isset( $snippets[ $key ] ) ) {
            $snippets[ $key ]['title'] = $title;
            update_option( 'post_snippets_options', $snippets );
        }
        wp_send_json_success();
    }

}