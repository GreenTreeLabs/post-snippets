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
                    $default_atts = array();
                    foreach ($vars as $var) {
                        $attribute = explode('=', $var);
                        $default_value = (count($attribute) > 1) ? $attribute[1] : '';
                        $default_atts[$attribute[0]] = $default_value;
                    }
                    // Get the wptexturize setting
                    $texturize = isset($snippet["wptexturize"]) ? $snippet["wptexturize"] : false;
                    add_shortcode(
                        $snippet['title'],
                        function ( $atts, $content = null ) use ( $snippet, $texturize, $default_atts ) {
                            extract( shortcode_atts( $default_atts, $atts ) );
                            $attributes = compact( array_keys( $default_atts ) );
                            // Add enclosed content if available to the attributes array
                            if ( $content != null ) {
                                $attributes["content"] = $content;
                            }
                            $generated_content = $snippet["snippet"];
                            // Disables auto conversion from & to &amp; as that should be done in snippet, not code (destroys php etc).
                            // $snippet = str_replace("&", "&amp;", $snippet);
                            foreach ( $attributes as $key => $val ) {
                                $generated_content = str_replace( "{" . $key . "}", $val, $generated_content );
                            }
                            // There might be the case that a snippet contains
                            // the post snippets reserved variable {content} to
                            // capture the content in enclosed shortcodes, but
                            // the shortcode is used without enclosing it. To
                            // avoid outputting {content} as part of the string
                            // lets remove possible occurences.
                            $generated_content = str_replace( "{content}", "", $generated_content );

                            // Handle PHP shortcodes
                            $php = $snippet["php"];
                            if ( $php == true ) {
                                $generated_content = \PostSnippets\Shortcode::phpEval( $generated_content );
                            }
                            // Strip escaping and execute nested shortcodes
                            $generated_content = do_shortcode( stripslashes( $generated_content ) );
                            // WPTexturize the Snippet
                            if ( !empty($snippet['wptexturize']) && ( $snippet['wptexturize'] == true ) ) {
                                $generated_content = wptexturize( $generated_content );
                            }
                            return $generated_content;
                        }
                    );
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