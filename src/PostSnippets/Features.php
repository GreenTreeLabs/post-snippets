<?php

namespace PostSnippets;

/**
 * Post Snippets Features.
 *
 *
 * @author   GreenTreeLabs <info at postsnippets dot com>
 * @link     https://www.postsnippets.com
 */

class Features {

	public function showFeatures() {

		// Get amount of snippets
		$snippet_count = count( get_option( 'post_snippets_options', array() ) );

		// Delete any existing prices
		delete_option( 'ps_pro_features_price' );

		// Now set the final price
		$price = '39.99';

		// Setup features
		$features = array (
			array (
				'title'          => __( 'Advanced Import/Export', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'More control over import/export, export per tag/group and export to CSV/Excel.', 'post-snippets' ),
				'form-action-id' => 'n3f2y6',
				'form-id'        => '6154019'
			),
			array (
				'title'          => __( 'Advanced Search/Filter', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Search for snippets on name etc. and easily find the snippet you are looking for.', 'post-snippets' ),
				'form-action-id' => 'f0b7s1',
				'form-id'        => '6154049'
			),
			array (
				'title'          => __( 'Sync Snippets', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Sync snippets from one site to another, even in WordPress Multisite.', 'post-snippets' ),
				'form-action-id' => 'z7z9r9',
				'form-id'        => '6154055'
			),
			array (
				'title'          => __( 'Access Manager', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Determine who can view, edit and insert snippets on a per snippet basis.', 'post-snippets' ),
				'form-action-id' => 'c0i4e6',
				'form-id'        => '6154061'
			),
			array (
				'title'          => __( 'Duplicate Snippets', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Duplicate an existing snippet as new, and start editing it, with just one click.', 'post-snippets' ),
				'form-action-id' => 'm6i2a4',
				'form-id'        => '6154067'
			),
			array (
				'title'          => __( 'Dedicated Edit View', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Edit individual snippets on dedicated pages, with all the space you need.', 'post-snippets' ),
				'form-action-id' => 'm1i4x7',
				'form-id'        => '6485580'
			),
			array (
				'title'          => __( 'Rich Text Editor', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Create beautiful snippet content with formatting and images.', 'post-snippets' ),
				'form-action-id' => 'h4i6i5',
				'form-id'        => '6154073'
			),
			array (
				'title'          => __( 'Conditional Logic', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Show snippet content only on certain posts/pages, dates or for certain users.', 'post-snippets' ),
				'form-action-id' => 'x3h3o6',
				'form-id'        => '6154085'
			),
			array (
				'title'          => __( 'Tags', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Add one or multiple tags to snippets and easily find a group of tagged snippets.', 'post-snippets' ),
				'form-action-id' => 'p3a2u2',
				'form-id'        => '6154079'
			),
			array (
				'title'          => __( 'Groups', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Organize snippets into groups to keep similar snippets together.', 'post-snippets' ),
				'form-action-id' => 'p4c2b9',
				'form-id'        => '6154171'
			),
			array (
				'title'          => __( 'Drag & Drop Sorting', 'post-snippets' ),
				'image'          => '',
				'description'    => __( 'Drag & Drop snippets into a any order and automatically save that order.', 'post-snippets' ),
				'form-action-id' => 'x6x1b1',
				'form-id'        => '6154177'
			),
		);

	$html = '';

ob_start();

?>

        <div class="wrap">
            <div id="pt-features">
                <div id="pt-features-content">

                    <div class="ps_features_wrap">
                        <p class="ps_features_wrap_intro">
							<?php echo __( 'Vote for new features in <strong>Post Snippets Pro</strong>!', 'post-snippets' ); ?>
                        </p>

                        <p class="ps_features_wrap_intro">
		                    <?php echo sprintf( __( 'It\'s the professional version of Post Snippets, starting at $%s per year (excl. taxes). You get three votes. The Pro version makes development and support for both versions sustainable, so you get a <strong>higher quality</strong> plugin.', 'post-snippets' ), $price ); ?>
                        </p>

                        <p class="ps_features_wrap_intro">
		                    <?php _e( 'By voting you are automatically subscribed to the Post Snippets newsletter, and you can unsubscribe at anytime.', 'post-snippets' ); ?>
                        </p>

						<p class="ps_features_wrap_intro"><?php _e( 'Other suggestions? Send an email to <a href="mailto:diego@greentreelabs.net">diego@greentreelabs.net</a>.', 'post-snippets' ); ?>
                        </p>

                        <p class="ps-votes-left" style="display: none;">
		                    <?php _e( 'You have 3 votes left!', 'post-snippets' ); ?>
                        </p>

                        <ul class="products" style="display: none;">

							<?php
							shuffle( $features );
							foreach ( $features as $feature ) : ?>

                                <li class="product">

									<?php if ( ! empty( $feature['image'] ) ) { ?>
                                        <img
                                                src=" <?php echo PS_URL . 'admin/feature_logos/' . str_replace( ' ', '', strtolower( $feature['title'] ) ) . '.png'; ?>"/>
									<?php } else { ?>
                                        <h2><?php echo $feature['title'] ?></h2>
									<?php } ?>

                                    <p><?php echo $feature['description'] ?></p>

									<?php //include( PS_PATH . 'admin/views/admin-features-interest-form.php' ); ?>

                                    <div id="mlb2-<?php echo $feature['form-id'] ?>"
                                         class="ml-subscribe-form ml-subscribe-form-<?php echo $feature['form-id'] ?>">

                                        <div class="subscribe-form ml-block-success" style="display:none">
                                            <div class="form-section mb0">
                                                <p class="ps-features-success-message"><?php _e( 'Thanks, I\'m deciding what features to build first, your vote helps!', 'post-snippets' ); ?></p>
                                            </div>
                                        </div>

                                        <form class="ml-block-form" action="//app.mailerlite.com/webforms/submit/<?php echo $feature['form-action-id'] ?>"
                                              data-id="177069" data-code="c7d6k5" method="POST" target="_blank">
                                            <div class="subscribe-form horizontal">
                                                <div class="form-section horizontal" style="display: inline">
                                                    <div class="form-group ml-field-email ml-validate-required ml-validate-email" style="display: inline">
                                                        <input style="display: none" type="text" name="fields[email]" class="form-control"
                                                               placeholder="Email*" value="<?php echo wp_get_current_user()->user_email; ?>">
                                                    </div>
                                                    <div class="form-group ml-field-ps_price ml-validate-required" style="display: inline">
                                                        <input style="display: none" type="text" name="fields[ps_price]" class="form-control" placeholder="PS Price*" value="<?php echo $price ?>" spellcheck="false">
                                                    </div>
                                                    <div class="form-group ml-field-ps_count ml-validate-required" style="display: inline">
                                                        <input style="display: none" type="text" name="fields[ps_count]" class="form-control" placeholder="PS Count*" value="<?php echo $snippet_count ?>" spellcheck="false">
                                                    </div>
                                                </div>
                                                <div class="form-section horizontal test" style="display: inline;">
                                                    <button type="submit" class="primary ps-vote-button">
						                                <?php echo sprintf( __( 'I need this in Pro - $%s', 'post-snippets' ), $price); ?>
                                                    </button>

                                                    <p class="ps-voted-note" style="display: none;">
		                                                <?php _e( 'You\'ve already voted 3 times!', 'post-snippets' ); ?>
                                                    </p>

                                                    <button disabled="disabled" style="display: none;" type="button" class="loading">
                                                        <img src="//static.mailerlite.com/images/rolling.gif" width="20" height="20"
                                                             style="width: 20px; height: 20px;">
                                                    </button>

                                                </div>

                                                <div class="clearfix" style="clear: both;"></div>
                                                <input type="hidden" name="ml-submit" value="1"/>
                                            </div>
                                        </form>

                                        <script>
                                            function ml_webform_success_<?php echo $feature['form-id'] ?>() {
                                                jQuery('.ml-subscribe-form-<?php echo $feature['form-id'] ?> .ml-block-success').show();
                                                jQuery('.ml-subscribe-form-<?php echo $feature['form-id'] ?> .ml-block-form, .subscribe-message').hide();
                                            }
                                        </script>

                                    </div>

                                </li>

							<?php endforeach; ?>

                        </ul>

                        <p class="ps-voted-note-large" style="display: none;" >
		                    <?php _e( 'You\'ve voted three times! Thank you!', 'post-snippets' ); ?>
                        </p>
                    </div>

                </div>
                <!-- .pt-features-content -->
            </div>
            <!-- .pt-features -->
        </div><!-- .wrap -->


        <!-- START: Post Snippets Feature voting -->
        <script type="text/javascript">

            //localStorage.removeItem('PostSnippetsVotesV3');
            //localStorage.setItem('PostSnippetsVotesV3', '0');

            jQuery(document).ready(function ($) {

                var psFeatures = $(this);
                var votes = 3;

                // If there are 0votex left in storage, don't show features, do show Thank you message
                if (localStorage.getItem('PostSnippetsVotesV3') == '0') {
                    $(psFeatures).find(".products").each(function (index, element) {
                        $(element).hide();
                        $(".ps-voted-note-large").show();
                    });
                } else {
                    $(".products").show();
                }

                // When vote button clicked:
                $("button").click(function () {

                    // Decrease remaining of votes by one
                    votes--;

                    // If votes are two or one, show warning with remaining votes
                    if (votes == 2 || votes == 1) {
                        $(".ps-votes-left").text('You have ' + votes + ' votes left!').fadeIn();
                    }

                    // If votes are zero:
                    if (votes == 0) {

                        // Store votes in localStorage
                        localStorage.setItem('PostSnippetsVotesV3', votes);

                        // Remove features, do show Thank you message
                        $(psFeatures).find(".products").each(function (index, element) {
                            $(element).fadeOut();
                            $(".ps-votes-left").fadeOut();
                            $(".ps-voted-note-large").fadeIn();
                        });

                    }

                });

            });

        </script>
        <!-- END: Post Snippets Feature voting -->

        <script type="text/javascript"
                src="//static.mailerlite.com/js/w/webforms.min.js?vb01ce49eaf30b563212cfd1f3d202142"></script>

		<?php

$html .= ob_get_contents();
ob_end_clean();

return $html;
}
}