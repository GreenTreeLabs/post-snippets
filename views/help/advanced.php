<h2><?php _e('Advanced', 'post-snippets'); ?> (<em><?php _e('for developers', 'post-snippets'); ?></em>)</h2>

<p>
You can add constants to wp-config.php or the theme’s functions.php file to control some aspects of the plugin. Available constants to set are:
</p>

<pre><code>// Allow users with edit_posts capability access to the Post Snippets admin.
define('POST_SNIPPETS_ALLOW_EDIT_POSTS', true);

// Disable PHP Execution in snippets, and removes the options from admin.
define('POST_SNIPPETS_DISABLE_PHP', true);
</code></pre>

<p>
<?php _e('You can retrieve a Post Snippet directly from PHP, in a theme for instance, by using the PostSnippets::getSnippet() method.', 'post-snippets'); ?>
</p>

<h2><?php _e('Usage', 'post-snippets'); ?></h2>
<p>
<code>
&lt;?php $my_snippet = PostSnippets::getSnippet( $snippet_name, $snippet_vars ); ?&gt;
</code></p>

<h2><?php _e('Parameters', 'post-snippets'); ?></h2>
<p>
<strong>$snippet_name</strong><br/>
<?php _e('(string) (required) The name of the snippet to retrieve.', 'post-snippets'); ?>
<br/><br/>
<strong>$snippet_vars</strong><br/>
<?php _e('(string) The variables to pass to the snippet, formatted as a query string.', 'post-snippets'); ?>
</p>


<h2><?php _e('Example', 'post-snippets'); ?></h2>

<pre><code>// Use querystring for variables
$mySnippet = PostSnippets::getSnippet('internal-link', 'title=Awesome&url=2011/02/awesome/');
echo $mySnippet;

// Use array for variables
$mySnippet = PostSnippets::getSnippet('internal-link', array('title' => 'Awesome', 'url' => '2011/02/awesome/');
echo $mySnippet;</code></pre>
