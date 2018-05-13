<!-- START: Post Snippets UI Dialog -->
<?php // Setup the dialog divs ?>
<div class="hidden">
    <div id="post-snippets-dialog" title="Post Snippets">
        <?php // Init the tabs div ?>
        <div id="post-snippets-tabs">

                <?php

                if ( empty($snippets)) {
                    echo sprintf(__('No snippets found, to create them go to &nbsp;&nbsp; %sPost Snippets &rarr;%s', 'post-snippets'), '<a href="'. admin_url( 'options-general.php?page=post-snippets') .'" class="button-primary">', '</a>');
                } else {
                    ?>
                <ul>
                    <?php
	                // Create a tab for each available snippet
	                foreach ( $snippets as $key => $snippet ) {
		                ?>
                        <li><a href="#ps-tabs-<?php echo $key;
			                ?>"><?php echo $snippet['title'];
				                ?></a></li>
		                <?php
	                }
	                ?>
                </ul>
              <?php
                }

            // Create a panel with form fields for each available snippet
            foreach ($snippets as $key => $snippet) {
                ?>
                <div id="ps-tabs-<?php echo $key;
                ?>">
                <?php
                // Print a snippet description is available
                if (isset($snippet['description'])) {
                    ?>
                    <p class="howto"><?php echo $snippet['description'];
                    ?></p>
                <?php

                }

                // Get all variables defined for the snippet and output them as
                // input fields
                $var_arr = explode(',', $snippet['vars']);
                if (!empty($var_arr[0])) {
                    foreach ($var_arr as $key_2 => $var) {
                        // Default value exists?
                        $def_pos = strpos($var, '=');
                        if ($def_pos !== false) {
                            $split = explode('=', $var);
                            $var = $split[0];
                            $def = $split[1];
                        } else {
                            $def = '';
                        }
                        ?>
                        <label for="var_<?php echo $key.'_'.$key_2;
                        ?>"><?php echo $var;
                        ?>:</label>
                        <input type="text" id="var_<?php echo $key.'_'.$key_2;
                        ?>" name="var_<?php echo $key.'_'.$key_2;
                        ?>" value="<?php echo $def;
                        ?>" style="width: 190px" />
                        <br/>
                    <?php

                    }
                } else {
                    // If no variables and no description available, output a text
                    // to inform the user that it's an insert snippet only.
                    if (empty($snippet['description'])) {
                        ?>
                        <p class="howto"><?php _e('This snippet is insert only, no variables defined.', 'post-snippets');
                        ?></p>
                    <?php

                    }
                }
                ?>
                </div><!-- #ps-tabs-<?php echo $key;
                ?> -->
            <?php

            }
        // Close the tabs and dialog divs ?>
        </div><!-- #post-snippets-tabs -->
    </div><!-- #post-snippets-dialog -->
</div><!-- .hidden -->
<!-- END: Post Snippets UI Dialog -->
