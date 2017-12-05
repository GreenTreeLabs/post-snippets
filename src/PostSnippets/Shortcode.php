<?php
namespace PostSnippets;

/**
 * Shortcode Handling.
 *
 */
class Shortcode
{
    public function __construct()
    {
        $this->create();
    }

    /**
     * Create the functions for shortcodes dynamically and register them
     */
    public function create()
    {
        $snippets = get_option(\PostSnippets::OPTION_KEY);
        if (!empty($snippets)) {
            foreach ($snippets as $snippet) {
                // If shortcode is enabled for the snippet, and a snippet has been entered, register it as a shortcode.
                if ($snippet['shortcode'] && !empty($snippet['snippet'])) {
                    $vars = explode(",", $snippet['vars']);
                    $vars_str = "";
                    foreach ($vars as $var) {
                        $attribute = explode('=', $var);
                        $default_value = (count($attribute) > 1) ? $attribute[1] : '';
                        $vars_str .= "\"{$attribute[0]}\" => \"{$default_value}\",";
                    }

                    // Get the wptexturize setting
                    $texturize = isset($snippet["wptexturize"]) ? $snippet["wptexturize"] : false;

                    add_shortcode( $snippet['title'], function ( $atts, $content = null ) use ( $vars_str, $snippet, $texturize ) {
                        $shortcode_symbols = [ $vars_str ];
                        extract( shortcode_atts( $shortcode_symbols, $atts ) );

                        $attributes = compact( array_keys( $shortcode_symbols ) );

                        // Add enclosed content if available to the attributes array
                        if ( $content != null ) {
                            $attributes["content"] = $content;
                        }

                        $snippet = addslashes( $snippet["snippet"] );
                        // Disables auto conversion from & to &amp; as that should be done in snippet, not code (destroys php etc).
                        // $snippet = str_replace("&", "&amp;", $snippet);

                        foreach ( $attributes as $key => $val ) {
                            $snippet = str_replace( '{' . $key . '}', $val, $snippet );
                        }

                        // There might be the case that a snippet contains
                        // the post snippets reserved variable {content} to
                        // capture the content in enclosed shortcodes, but
                        // the shortcode is used without enclosing it. To
                        // avoid outputting {content} as part of the string
                        // lets remove possible occurences.
                        $snippet = str_replace( '{content}', '', $snippet );

                        // Handle PHP shortcodes
                        $php = $snippet["php"];
                        if ( $php == true ) {
                            $snippet = \PostSnippets\Shortcode::phpEval( $snippet );
                        }

                        // Strip escaping and execute nested shortcodes
                        $snippet = do_shortcode( stripslashes( $snippet ) );

                        // WPTexturize the Snippet
                        $texturize = $texturize;
                        if ( $texturize == true ) {
                            $snippet = wptexturize( $snippet );
                        }

                        return $snippet;
                    } );
                }
            }
        }
    }

    /**
     * Evaluate a snippet as PHP code.
     *
     * @since   Post Snippets 1.9
     * @param   string  $content    The snippet to evaluate
     * @return  string              The result of the evaluation
     */
    public static function phpEval($content)
    {
        if (defined('POST_SNIPPETS_DISABLE_PHP')) {
            return $content;
        }

        $content = stripslashes($content);

        ob_start();
        eval($content);
        $content = ob_get_clean();

        return addslashes($content);
    }
}
