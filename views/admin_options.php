<form method="post" action="options.php">
<?php
    settings_fields(\PostSnippets::SETTINGS);
    do_settings_sections('post-snippets');
    submit_button();
?>
</form>
