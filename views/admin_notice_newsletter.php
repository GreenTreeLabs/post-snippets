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

<div id="ps-newsletter-notice" class="updated">

	<div id="mlb2-6493346" class="ml-subscribe-form ml-subscribe-form-6493346">

		<div class="subscribe-form ml-block-success" style="display:none">
			<div class="form-section mb0">
				<p>
					<?php _e( 'Thank you for subscribing to the Post Snippets newsletter!', 'post-snippets' ); ?>
				</p>
			</div>
		</div>

        <!-- FOR NEW PLUGIN - UPDATE BELOW ID IN ACTION URL -->
		<form class="ml-block-form" action="//app.mailerlite.com/webforms/submit/a3m1f7" data-id="177069"
		      data-code="c7d6k5" method="POST" target="_blank">
			<div class="subscribe-form horizontal">
				<div class="form-section horizontal" style="display: inline">
					<div class="form-group ml-field-email ml-validate-required ml-validate-email"
					     style="display: inline">
						<span
							class="subscribe-message"><?php _e( 'Subscribe for Post Snippets updates:', 'post-snippets' ); ?></span>
						<input style="display: inline" type="text" name="fields[email]" class="form-control"
						       placeholder="Email*" value="<?php echo wp_get_current_user()->user_email; ?>">
					</div>
                    <div class="form-group ml-field-ps_count ml-validate-required" style="display: none">
                        <input style="display: none" type="text" name="fields[ps_count]" class="form-control" placeholder="PS Count*" value="<?php echo $snippet_count ?>" spellcheck="false" autocapitalize="off" autocorrect="off">
                    </div>

				</div>
				<div class="form-section horizontal" style="display: inline">
					<button type="submit" class="primary">
						<?php _e( 'Subscribe now!', 'post-snippets' ); ?>
					</button>

					<button disabled="disabled" style="display: none;" type="button" class="loading primary">
						<img src="//static.mailerlite.com/images/rolling.gif" width="20" height="20"
						     style="padding-top: 3px;">
					</button>

				</div>

				<a href="<?php echo esc_url( add_query_arg( 'ps-dismiss-newsletter-nag', 1 ) ); ?>"
				   class="button-secondary"><?php _e( 'Hide this', 'post-snippets' ); ?></a>

				<div class="clearfix" style="clear: both;"></div>
				<input type="hidden" name="ml-submit" value="1"/>
			</div>
		</form>

		<script>
			function ml_webform_success_6493346() {
				jQuery('.ml-subscribe-form-6493346 .ml-block-success').show();
				jQuery('.ml-subscribe-form-6493346 .ml-block-form, .subscribe-message').hide();
                window.location.search += '&ps-dismiss-newsletter-nag=1';
			}
		</script>

	</div>
</div>
<script type="text/javascript"
        src="//static.mailerlite.com/js/w/webforms.min.js?v3772b61f1ec61c541c401d4eadfdd02f"></script>



