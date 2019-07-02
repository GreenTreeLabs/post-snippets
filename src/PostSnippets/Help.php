<?php
namespace PostSnippets;

/**
 * Handles the plugin help screen.
 *
 */
class Help
{
    /**
     * Define actions.
     *
     * @param  string
     * @return void
     */
    public function __construct($optionPage)
    {
        add_action('load-'.$optionPage, array(&$this, 'tabs'));

        add_action('load-post.php', array(&$this, 'postEditor'));
        add_action('load-post-new.php', array(&$this, 'postEditor'));
    }

    /**
     * Load the post editor tab in the admin_head filter.
     *
     * We load the tab that will integrate in the post editor help menu via the
     * admin_head hook, as we are not otherwise able to make it get ordered
     * below the native help tabs.
     *
     * @return void.
     */
    public function postEditor()
    {
        add_action('admin_head', array(&$this, 'postEditorTabs'));
    }

    /**
     * Setup the help tabs and sidebar.
     *
     * @return void
     */
    public function tabs()
    {
        $screen = get_current_screen();
        $screen->set_help_sidebar($this->content('help/sidebar'));
        $screen->add_help_tab(
            array(
            'id'      => 'usage-plugin-help',
            'title'   => __('Usage', 'post-snippets'),
            'content' => $this->content('help/usage')
            )
        );
        $screen->add_help_tab(
            array(
            'id'      => 'post-plugin-help',
            'title'   => __('Post Editor', 'post-snippets'),
            'content' => $this->content('help/post')
            )
        );
        if (!defined('POST_SNIPPETS_DISABLE_PHP')) {
            $screen->add_help_tab(
                array(
                'id'      => 'php-plugin-help',
                'title'   => __('PHP', 'post-snippets'),
                'content' => $this->content('help/php')
                )
            );
        }
        $screen->add_help_tab(
            array(
            'id'      => 'advanced-plugin-help',
            'title'   => __('Advanced', 'post-snippets'),
            'content' => $this->content('help/advanced')
            )
        );
        $screen->add_help_tab(
            array(
            'id'      => 'filters-plugin-help',
            'title'   => __('Filters', 'post-snippets'),
            'content' => $this->content('help/filters')
            )
        );
    }

    /**
     * Setup the help tab for the post editor.
     *
     * @return void
     */
    public function postEditorTabs()
    {
        $screen = get_current_screen();

        $screen->add_help_tab(
            array(
            'id'      => 'postsnippets-plugin-help',
            'title'   => 'Post Snippets',
            'content' => $this->content('help/post')
            )
        );
    }

    /**
     * Get the content for a help tab
     *
     * @param  string  $tab
     * @return string
     */
    private function content($tab)
    {
        return View::render($tab);
    }
}
