<form method="post" action="" class="post-snippets-wrap">
    <?php 
wp_nonce_field( 'update_snippets', 'update_snippets_nonce' );
?>
    <div class="post-snippets-buttons-top">
    <?php 
\PostSnippets\Admin::submit(
    'update-snippets',
    __( 'Update Snippets', 'post-snippets' ),
    'button-primary',
    false
);
\PostSnippets\Admin::submit(
    'add-snippet',
    __( 'Add New Snippet', 'post-snippets' ),
    'button-secondary',
    false
);
\PostSnippets\Admin::submit(
    'delete-snippets',
    __( 'Delete Selected', 'post-snippets' ),
    'button-secondary',
    false
);
?>
	<a href="#" class="button" data-ps-download><i class="wp-ps-cloud-download"></i> Download snippets</a>
    </div>

	<div>

	</div>

    <table class="widefat fixed mt-20 post-snippets-table" cellspacing="0">
        <thead>
        <tr>
            <th scope="col" class="check-column"><input type="checkbox" /></th>
            <th scope="col" class="text-right expand-collapse">
                <a href="#" class="expand-all">
                    <?php 
_e( 'Expand All', 'post-snippets' );
?>
                </a>
                <a href="#" class="collapse-all">
                    <?php 
_e( 'Collapse All', 'post-snippets' );
?>
                </a>
            </th>
        </tr>
        </thead>
    </table>

    <div class="post-snippets post-snippets-list post-snippets-list-empty" style="display: none;">
        <p>
            <?php 
echo  __( 'You\'ve just installed Post Snippets, awesome!', 'post-snippets' ) ;
?><br />
            <?php 
echo  __( 'Click "Add New Snippet" to create your first snippet or read the documentation at the top right under "Help".', 'post-snippets' ) ;
?>
        </p>
    </div>
	<?php 
wp_nonce_field( 'delete_snippet', 'delete_snippet' );
?>
	<?php 
wp_nonce_field( 'sync_up', 'sync_up' );
?>
	<?php 
wp_nonce_field( 'sync_down', 'sync_down' );
?>
    <?php 
$snippets = get_option( \PostSnippets::OPTION_KEY );

if ( !empty($snippets) ) {
    ?>

        <div class="post-snippets post-snippets-list">
            <?php 
    foreach ( $snippets as $key => $snippet ) {
        ?>
                <div class="post-snippets-item ui-state-highlight" data-order="<?php 
        echo  $key ;
        ?>" id="key-<?php 
        echo  $key ;
        ?>">
                    <div class="post-snippets-toolbar">
                        <div class="text-left">
                            <input type='checkbox' name='checked[]' value='<?php 
        echo  $key ;
        ?>'/>
                            <input type='text' class="post-snippet-title" name='<?php 
        echo  "snippets[{$key}][title]" ;
        ?>' value='<?php 
        echo  $snippet['title'] ;
        ?>'/>
							<span id="snippet-<?php 
        echo  $key ;
        ?>-title" class="post-snippet-title"><?php 
        echo  $snippet['title'] ;
        ?></span>
							<span class='ps-description'><?php 
        if ( !empty($snippet['description']) ) {
            echo  " (" . esc_html( $snippet['description'] ) . ")" ;
        }
        ?></span>
                            <a href="#" class="edit-title">
                                <i class="dashicons dashicons-edit"></i>
                            </a>
                            <a href="#" class="save-title">
                                <i class="dashicons dashicons-yes"></i>
                            </a>
                        </div>
                        <div class="text-right post-snippets-toolbar-right">

                            <?php 
        ?>


                            <a href="#" class="toggle-post-snippets-data" title="Expand/Collapse">
                                <i class="dashicons dashicons-arrow-down"></i>
                                <i class="dashicons dashicons-arrow-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="post-snippets-data">
                        <div class="post-snippets-data-cell">
                            <div>
                                <textarea id="snippet-<?php 
        echo  $key ;
        ?>" class="snippet" name="<?php 
        echo  "snippets[{$key}][snippet]" ;
        ?>" class="large-text" style='width: 100%;' rows="5"><?php 
        echo  htmlspecialchars( $snippet['snippet'], ENT_NOQUOTES ) ;
        ?></textarea>

                                <?php 
        ?>

                                <?php 
        _e( 'Description', 'post-snippets' );
        ?>:
                                <input type='text' style='width: 100%;' name="<?php 
        echo  "snippets[{$key}][description]" ;
        ?>" value='<?php 
        if ( isset( $snippet['description'] ) ) {
            echo  esc_html( $snippet['description'] ) ;
        }
        ?>'/><br/>
                            </div>
                        </div>
                        <div class="post-snippets-data-cell">
                            <strong>Variables:</strong><br/>
                            <input type='text' name="<?php 
        echo  "snippets[{$key}][vars]" ;
        ?>" value='<?php 
        echo  $snippet['vars'] ;
        ?>'/>
                            <br/>
                            <br/>

                            <label for="<?php 
        echo  "snippets[{$key}][shortcode]" ;
        ?>">
                                <input type="checkbox" name="<?php 
        echo  "snippets[{$key}][shortcode]" ;
        ?>" id="<?php 
        echo  "snippets[{$key}][shortcode]" ;
        ?>" value="1" <?php 
        checked( $snippet['shortcode'], '1', true );
        ?>>
                                Shortcode
                            </label>

                            <br/><strong><?php 
        _e( 'Shortcode Options:', 'post-snippets' );
        ?></strong><br/>
                            <?php 
        
        if ( !defined( 'POST_SNIPPETS_DISABLE_PHP' ) ) {
            ?>
                                <label for="<?php 
            echo  "snippets[{$key}][php]" ;
            ?>">
                                    <input type="checkbox" name="<?php 
            echo  "snippets[{$key}][php]" ;
            ?>" id="<?php 
            echo  "snippets[{$key}][php]" ;
            ?>" value="1" <?php 
            checked( $snippet['php'], '1', true );
            ?>>
                                    PHP Code
                                </label>
                                <br/>
                            <?php 
        }
        
        ?>

                            <label for="<?php 
        echo  "snippets[{$key}][wptexturize]" ;
        ?>">
                                <input type="checkbox" name="<?php 
        echo  "snippets[{$key}][wptexturize]" ;
        ?>" id="<?php 
        echo  "snippets[{$key}][wptexturize]" ;
        ?>" value="1" <?php 
        checked( $snippet['wptexturize'], '1', true );
        ?>>
                                wptexturize
                            </label>
                        </div>
                    </div>
                </div>
            <?php 
    }
    ?>
        </div>
    <?php 
}

?>


<div id="delete-confirm" title="<?php 
_e( 'Confirm', 'post-snippets' );
?>" style="display:none;">
  <p><span class="dashicons dashicons-warning" style="float:left; margin-right:12px;"></span>
  <?php 
_e( 'Do you wan to delete snippet', 'post-snippets' );
?>: <strong></strong>?
</div>

<div id="sync-up-confirm" title="<?php 
_e( 'Confirm', 'post-snippets' );
?>" style="display:none;">
  <p><span class="dashicons dashicons-warning" style="float:left; margin-right:12px;"></span>
  <?php 
_e( 'Do you want to upload the snippet', 'post-snippets' );
?>: <strong></strong> <?php 
_e( 'in your snippets cloud?', 'post-snippets' );
?>
</div>

<div id="sync-up-success" title="<?php 
_e( 'Success', 'post-snippets' );
?>" style="display:none;">
  <p><span class="dashicons dashicons-thumbs-up" style="float:left; margin-right:12px;"></span>
  <?php 
_e( 'Snippet successfully saved!', 'post-snippets' );
?>
</div>

<div id="sync-success" title="<?php 
_e( 'Success', 'post-snippets' );
?>" style="display:none;">
  <p><span class="dashicons dashicons-thumbs-up" style="float:left; margin-right:12px;"></span>
  <?php 
_e( 'Snippet successfully downloaded!', 'post-snippets' );
?>
</div>

<div id="sync-up-error" title="<?php 
_e( 'Error', 'post-snippets' );
?>" style="display:none;">
  <p><span class="dashicons dashicons-warning" style="float:left; margin-right:12px;"></span>
  <p data-error></p>
</div>

<div id="sync-update-confirm" title="<?php 
_e( 'Confirm', 'post-snippets' );
?>" style="display:none;">
  <p><span class="dashicons dashicons-warning" style="float:left; margin-right:12px;"></span>
  <?php 
_e( 'A snippet with title <strong data-title></strong> already exists in your online library:', 'post-snippets' );
?>
  <div class='snippet'>
	  <p><b>Title</b>: <span data-title></span></p>
	  <p><b>Description</b>: <span data-description></span></p>
	  <p><b>Variables</b>: <span data-vars></span></p>
	  <p><b>Snippet</b>: <textarea readonly></textarea></p>
	  <p><b>Options</b>: <span data-options></span></p>
  </div>
</div>

<div id="sync-down-list" title="<?php 
_e( 'Donwload your snippets', 'post-snippets' );
?>">
	<p class="message"></p>
	<table class="wp-list-table widefat striped">
		<thead>
			<tr>
				<td class="manage-column column-cb"><input type='checkbox' data-toggle-all></td>
				<th class=""><?php 
_e( 'Title', 'post-snippets' );
?></th>
				<th><?php 
_e( 'Description', 'post-snippets' );
?></th>
				<th><?php 
_e( 'Variables', 'post-snippets' );
?></th>
				<th><?php 
_e( 'Snippet', 'post-snippets' );
?></th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
</div>

<div id="sp-loading" style="display:none;">
	<span></span>
</div>

	<div class="post-snippets-buttons-bottom">
	<?php 
\PostSnippets\Admin::submit(
    'update-snippets',
    __( 'Update Snippets', 'post-snippets' ),
    'button-primary',
    false
);
\PostSnippets\Admin::submit(
    'add-snippet',
    __( 'Add New Snippet', 'post-snippets' ),
    'button-secondary',
    false
);
\PostSnippets\Admin::submit(
    'delete-snippets',
    __( 'Delete Selected', 'post-snippets' ),
    'button-secondary',
    false
);
?>
	</div>
</form>
