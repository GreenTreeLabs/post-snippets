
<!-- START: Post Snippets jQuery UI and related functions -->
<script type='text/javascript'>
    <?php
    foreach ($snippetStack as $snippetVarString) {
        echo $snippetVarString;
    } ?>

    jQuery(document).ready(function($){
        <?php
        # Create js variables for all form fields
        foreach ($snippets as $key => $snippet) {
            $var_arr = explode(",", $snippet['vars']);
            if (!empty($var_arr[0])) {
                foreach ($var_arr as $key_2 => $var) {
                    $varname = "var_" . $key . "_" . $key_2;
                    echo "var {$varname} = $( \"#{$varname}\" );\n";
                }
            }
        }
        ?>
        // We do the check to see if post snippets dialog is in the footer.
        // Some plugins, like 'download monitor' uses WordPress admin bootstrap and
        // then bring in admin_head but not admin_footer. So to allow other plugins
        // to do that 'hack', we bail out in thoise cases.
        if ($("#post-snippets-tabs").length>0) {

            if($.isFunction($().tabs)){ // Ensure the tabs function is available
                var tabs = $("#post-snippets-tabs").tabs();
            }

            $(function() {

                if($.isFunction($().dialog)){ // Ensure the dialog function is available

                    $("#post-snippets-dialog").dialog({
                        autoOpen: false,
                        modal: true,
                        dialogClass: 'wp-dialog',
                        buttons: {
                            Cancel: function() {
                                $(this).dialog("close");
                            },
                            "Insert": function() {
                                $(this).dialog("close");
                                <?php
                                global $wp_version;
                                if (version_compare($wp_version, '3.5', '<')) {
                                    ?>
                                    var selected = tabs.tabs('option', 'selected');
                                <?php

                                } else {
                                    ?>
                                    var selected = tabs.tabs('option', 'active');
                                <?php

                                }

                                foreach ($snippets as $key => $snippet) {
                                    ?>
                                    if (selected == <?php echo $key;
                                    ?>) {
                                        insert_snippet = postsnippet_<?php echo $key;
                                    ?>;
                                        <?php
                                        $var_arr = explode(",", $snippet['vars']);
                                    if (!empty($var_arr[0])) {
                                        foreach ($var_arr as $key_2 => $var) {
                                            $varname = "var_" . $key . "_" . $key_2;
                                            ?>
                                                insert_snippet = insert_snippet.replace(/\{<?php echo $methods->stripDefaultVal($var);
                                            ?>\}/g, <?php echo $varname;
                                            ?>.val());
                                        <?php

                                        }
                                    }
                                    ?>
                                }

                            <?php

                            }
                            ?>

                                // Decide what method to use to insert the snippet depending
                                // from what editor the window was opened from
                                if (post_snippets_caller == 'html') {
                                    // HTML editor in WordPress 3.3 and greater
                                    QTags.insertContent(insert_snippet);
                                } else {
                                    // Visual Editor
                                    post_snippets_canvas.execCommand('mceInsertContent', false, insert_snippet);
                                }
                            }
                        },
                        width: 500,
                    });
                }
            });
        }
    });

    // Global variables to keep track on the canvas instance and from what editor
    // that opened the Post Snippets popup.
    var post_snippets_canvas;
    var post_snippets_caller = '';
</script>
<!-- END: Post Snippets jQuery UI and related functions -->
