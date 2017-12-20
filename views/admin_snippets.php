<form method="post" action="" class="post-snippets-wrap">
    <?php wp_nonce_field( 'update_snippets', 'update_snippets_nonce' ); ?>

    <?php
    \PostSnippets\Admin::submit( 'update-snippets', __( 'Update Snippets', 'post-snippets' ), 'button-primary', false );
    \PostSnippets\Admin::submit( 'add-snippet', __( 'Add New Snippet', 'post-snippets' ), 'button-secondary', false );
    \PostSnippets\Admin::submit( 'delete-snippets', __( 'Delete Selected', 'post-snippets' ), 'button-secondary', false );
    ?>
    <br>
    <table class="widefat fixed mt-20 post-snippets-table" cellspacing="0">
        <thead>
        <tr>
            <th scope="col" class="check-column"><input type="checkbox" /></th>
<!--            <th scope="col" style="width: 180px;">--><?php //_e('Title', 'post-snippets'); ?><!--</th>-->
<!--            <th scope="col" style="width: 180px;">--><?php //_e('Variables', 'post-snippets'); ?><!--</th>-->
<!--            <th scope="col">--><?php //_e('Snippet', 'post-snippets'); ?><!--</th>-->
        </tr>
        </thead>
    </table>
    <?php
    $snippets = get_option( \PostSnippets::OPTION_KEY );
    if ( ! empty( $snippets ) ):?>
        <div class="post-snippets">
            <?php foreach ( $snippets as $key => $snippet ): ?>
                <div class="post-snippets-item open" data-order="<?php echo $key; ?>" id="key-<?php echo $key; ?>">
                        <div class="post-snippets-toolbar">
                            <div class="text-left">
                                <input type='checkbox'  name='checked[]' value='<?php echo $key; ?>'/>
                                <input type='text' class="post-snippet-title" name='<?php echo $key; ?>_title' value='<?php echo $snippet['title'];?>'/>
                                <span class="post-snippet-title"><?php echo $snippet['title'];?></span>
                                <a href="#" class="edit-title">
                                    <i class="dashicons dashicons-edit"></i>
                                </a>
                                <a href="#" class="save-title">
                                    <i class="dashicons dashicons-yes"></i>
                                </a>
                            </div>
                            <div class="text-right">
                                <a href="#" class="handle"> <i class="dashicons dashicons-move"></i></a>
                                <a href="#" class="toggle-post-snippets-data">
                                    <i class="dashicons dashicons-arrow-down"></i>
                                    <i class="dashicons dashicons-arrow-up"></i>
                                </a>
                            </div>
                        </div>
                        <div class="post-snippets-data">
                            <div class="post-snippets-data-cell">
                                <div>
                                    <textarea name="<?php echo $key;?>_snippet" class="large-text" style='width: 100%;' rows="5"><?php echo htmlspecialchars( $snippet['snippet'], ENT_NOQUOTES ); ?></textarea>
                                    <?php _e( 'Description', 'post-snippets' ) ?>:
                                    <input type='text' style='width: 100%;' name='<?php echo $key;
                                    ?>_description' value='<?php if ( isset( $snippet['description'] ) ) {
                                        echo esc_html( $snippet['description'] );
                                    }
                                    ?>'/><br/>
                                </div>
                            </div>
                            <div class="post-snippets-data-cell">
                                <strong>Variables:</strong><br/>
                                <input type='text' name='<?php echo $key;?>_vars' value='<?php echo $snippet['vars'];?>'/>
                                <br/>
                                <br/>
                                <?php
                                \PostSnippets\Admin::checkbox( __( 'Shortcode', 'post-snippets' ), $key . '_shortcode', $snippet['shortcode'] );
                                echo '<br/><strong>Shortcode Options:</strong><br/>';
                                if ( ! defined( 'POST_SNIPPETS_DISABLE_PHP' ) ) {
                                    \PostSnippets\Admin::checkbox(
                                        __( 'PHP Code', 'post-snippets' ),
                                        $key . '_php',
                                        $snippet['php']
                                    );
                                }
                                $wptexturize = isset( $snippet['wptexturize'] ) ? $snippet['wptexturize'] : false;
                                \PostSnippets\Admin::checkbox( 'wptexturize', $key . '_wptexturize', $wptexturize );
                                ?>
                            </div>
                        </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php
\PostSnippets\Admin::submit( 'update-snippets', __( 'Update Snippets', 'post-snippets' ), 'button-primary', false );
\PostSnippets\Admin::submit( 'add-snippet', __( 'Add New Snippet', 'post-snippets' ), 'button-secondary', false );
\PostSnippets\Admin::submit( 'delete-snippets', __( 'Delete Selected', 'post-snippets' ), 'button-secondary', false );
echo '</form>';
