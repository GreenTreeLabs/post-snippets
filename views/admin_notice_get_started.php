<?php

/**
 * Show a newsletter opt-in in Post Snippets settings.
 *
 * @package    PS
 * @subpackage Views
 * @author     GreenTreeLabs <diego@greentreelabs.net>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get amount of snippets
$snippet_count = count( get_option( 'post_snippets_options', array() ) );

?>

<style>
    #pt-admin-notice .button-primary,
    #pt-admin-notice .button-secondary {
        margin-left: 15px;
    }
</style>

<div id="ps-admin-notice" class="updated">
    <p>
		<?php _e( 'You\'ve just installed Post Snippets, find it under \'Settings\'.', 'post-snippets' ); ?>
        &nbsp;&nbsp;
        <a href="<?php echo PS_MAIN_PAGE_URL . '&ps-dismiss-get-started-nag=1'; ?>" class="button-primary"
           style="vertical-align: baseline;"><?php _e( 'Go to Post Snippets', 'post-snippets' ); ?></a>
        <a href="<?php echo esc_url( add_query_arg( 'ps-dismiss-get-started-nag', 1 ) ); ?>"
           class="button-secondary"><?php _e( 'Hide this', 'post-snippets' ); ?></a>
    </p>
</div>



