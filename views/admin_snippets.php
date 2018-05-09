<form method="post" action="" class="post-snippets-wrap">
    <?php wp_nonce_field( 'update_snippets', 'update_snippets_nonce' ); ?>
    <div class="post-snippets-buttons-top">
    <?php
    \PostSnippets\Admin::submit( 'update-snippets', __( 'Update Snippets', 'post-snippets' ), 'button-primary', false );
    \PostSnippets\Admin::submit( 'add-snippet', __( 'Add New Snippet', 'post-snippets' ), 'button-secondary', false );
    \PostSnippets\Admin::submit( 'delete-snippets', __( 'Delete Selected', 'post-snippets' ), 'button-secondary', false );
    ?>
    </div>

    <table class="widefat fixed mt-20 post-snippets-table" cellspacing="0">
        <thead>
        <tr>
            <th scope="col" class="check-column"><input type="checkbox" /></th>
            <th scope="col" class="text-right expand-collapse">
                <a href="#" class="expand-all">
                    <?php _e('Expand All', 'post-snippets'); ?>
                </a>
                <a href="#" class="collapse-all">
                    <?php _e('Collapse All', 'post-snippets'); ?>
                </a>
            </th>
        </tr>
        </thead>
    </table>

    <div class="post-snippets post-snippets-list post-snippets-list-empty" style="display: none;">
        <p>
            <?php echo __('You\'ve just installed Post Snippets, awesome!', 'post-snippets'); ?><br />
            <?php echo __('Click "Add New Snippet" to create your first snippet or read the documentation at the top right under "Help".', 'post-snippets'); ?>
        </p>
    </div>

    <?php
    $snippets = get_option( \PostSnippets::OPTION_KEY );
    if ( ! empty( $snippets ) ):?>

        <div class="post-snippets post-snippets-list">
            <?php foreach ( $snippets as $key => $snippet ): ?>
                <div class="post-snippets-item ui-state-highlight" data-order="<?php echo $key; ?>" id="key-<?php echo $key; ?>">
                    <div class="post-snippets-toolbar">
                        <div class="text-left">
                            <input type='checkbox'  name='checked[]' value='<?php echo $key; ?>'/>
                            <input type='text' class="post-snippet-title" name='<?php echo "snippets[{$key}][title]"; ?>' value='<?php echo $snippet['title'];?>'/>
                            <span class="post-snippet-title"><?php echo $snippet['title'];?></span>
                            <a href="#" class="edit-title">
                                <i class="dashicons dashicons-edit"></i>
                            </a>
                            <a href="#" class="save-title">
                                <i class="dashicons dashicons-yes"></i>
                            </a>
                        </div>
                        <div class="text-right post-snippets-toolbar-right">
                            <?php
                            if ( postsnippets_fs()->is__premium_only() ) : ?>
                                <a href="#" title="Duplicate" class="snippet-duplicate">
                                    <i class="dashicons dashicons-admin-page"></i>
                                </a>
                                <a href="#" class="handle" title="Move">
                                    <i class="dashicons dashicons-move"></i></a>
                            <?php endif ?>
                            <a href="#" class="toggle-post-snippets-data" title="Expand/Collapse">
                                <i class="dashicons dashicons-arrow-down"></i>
                                <i class="dashicons dashicons-arrow-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="post-snippets-data">
                        <div class="post-snippets-data-cell">
                            <div>
                                <textarea class="snippet" name="<?php echo "snippets[{$key}][snippet]"; ?>" class="large-text" style='width: 100%;' rows="5"><?php echo htmlspecialchars( $snippet['snippet'], ENT_NOQUOTES ); ?></textarea>
                                <?php _e( 'Description', 'post-snippets' ) ?>:
                                <input type='text' style='width: 100%;' name="<?php echo "snippets[{$key}][description]"; ?>" value='<?php if ( isset( $snippet['description'] ) ) {
                                    echo esc_html( $snippet['description'] );
                                }
                                ?>'/><br/>
                            </div>
                        </div>
                        <div class="post-snippets-data-cell">
                            <strong>Variables:</strong><br/>
                            <input type='text' name="<?php echo "snippets[{$key}][vars]"; ?>" value='<?php echo $snippet['vars'];?>'/>
                            <br/>
                            <br/>

                            <label for="<?php echo "snippets[{$key}][shortcode]"; ?>">
                                <input type="checkbox" name="<?php echo "snippets[{$key}][shortcode]"; ?>" id="<?php echo "snippets[{$key}][shortcode]"; ?>" value="1" <?php checked($snippet['shortcode'], '1' , true);?>>
                                Shortcode
                            </label>

                            <br/><strong><?php _e('Shortcode Options:', 'post-snippets');?></strong><br/>
                            <?php if ( ! defined( 'POST_SNIPPETS_DISABLE_PHP' ) ) :?>
                                <label for="<?php echo "snippets[{$key}][php]"; ?>">
                                    <input type="checkbox" name="<?php echo "snippets[{$key}][php]"; ?>" id="<?php echo "snippets[{$key}][php]"; ?>" value="1" <?php checked($snippet['php'], '1' , true);?>>
                                    PHP Code
                                </label>
                                <br/>
                            <?php endif; ?>

                            <label for="<?php echo "snippets[{$key}][wptexturize]"; ?>">
                                <input type="checkbox" name="<?php echo "snippets[{$key}][wptexturize]"; ?>" id="<?php echo "snippets[{$key}][wptexturize]"; ?>" value="1" <?php checked($snippet['wptexturize'], '1' , true);?>>
                                wptexturize
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<div class="post-snippets-buttons-bottom">
<?php
\PostSnippets\Admin::submit( 'update-snippets', __( 'Update Snippets', 'post-snippets' ), 'button-primary', false );
\PostSnippets\Admin::submit( 'add-snippet', __( 'Add New Snippet', 'post-snippets' ), 'button-secondary', false );
\PostSnippets\Admin::submit( 'delete-snippets', __( 'Delete Selected', 'post-snippets' ), 'button-secondary', false );
echo '</div>';
echo '</form>';
