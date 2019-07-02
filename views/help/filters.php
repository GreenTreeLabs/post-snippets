<h2><?php _e('Filters', 'post-snippets'); ?> (<em><?php _e('for developers', 'post-snippets'); ?></em>)</h2>

<p><?php _e('The following filters are available for hooking into the plugin:', 'post-snippets'); ?></p>

<ul>
  <li><strong>post_snippets_import</strong>&nbsp;&nbsp;&nbsp;<em>serialized array</em>&nbsp;&nbsp;&nbsp;<?php _e('Modify snippets and related data before the imported file populates the snippets.', 'post-snippets'); ?></li>
  <li><strong>post_snippets_export</strong>&nbsp;&nbsp;&nbsp;<em>serialized array</em>&nbsp;&nbsp;&nbsp;<?php _e('Modify snippets and related data before the export file is created.', 'post-snippets'); ?></li>
  <li><strong>post_snippets_snippets_list</strong>&nbsp;&nbsp;&nbsp;<em>array</em>&nbsp;&nbsp;&nbsp;<?php _e('Modify the array of snippets that are used as the snippet list for the jQuery UI dialog in the edit post screen.', 'post-snippets'); ?></li>
</ul>

<h3><?php _e('Examples', 'post-snippets'); ?></h3>

<strong>post_snippets_export</strong>
<pre><code>// Filter Exported Snippets
function ps_export( $snippets )
{
  $snippets = unserialize( $snippets );
  foreach ( $snippets as &$snippet ) {
    // Do something here. Example below search and replaces in snippets
    $snippet['snippet'] = str_replace('search','replace', $snippet['snippet']);
  }
  return serialize( $snippets );
}
add_filter( 'post_snippets_export', 'ps_export' );</code></pre>

<strong>post_snippets_import</strong>
<pre><code>// Filter Imported Snippets
function ps_import( $snippets )
{
  $snippets = unserialize( $snippets );
  foreach ( $snippets as &$snippet ) {
    // Do something here. Example below search and replaces in variables
    $snippet['vars'] = str_replace('search', 'replace', $snippet['vars']);
  }
  return serialize( $snippets );
}
add_filter( 'post_snippets_import', 'ps_import' );</code></pre>
