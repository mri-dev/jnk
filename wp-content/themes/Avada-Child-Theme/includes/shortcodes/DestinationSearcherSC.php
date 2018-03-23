<?php
class DestinationSearcherSc
{
    const SCTAG = 'destination-searcher';

    public function __construct()
    {
        add_action( 'init', array( &$this, 'register_shortcode' ) );
    }

    public function register_shortcode() {
        add_shortcode( self::SCTAG, array( &$this, 'do_shortcode' ) );
    }

    public function do_shortcode( $attr, $content = null )
    {

    	  /* Set up the default arguments. */
        $defaults = apply_filters(
            self::SCTAG.'_defaults',
            array(
              'class' => ''
            )
        );

        $t = new ShortcodeTemplates(__CLASS__.'/v1');

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        $output = '<div class="'.self::SCTAG.'-holder '.$attr['class'].'"'. ( ($attr['class'] == 'manual-insert') ? 'style="background-image:url(\''.IFROOT.'/images/searcher-bg-vw.jpg\');"':'') .'>';
        $output .= $t->load_template($attr);
        $output .= '</div>';


        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

}

new DestinationSearcherSc();

?>
