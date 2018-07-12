<?php
class TematikusKategoriaSc
{
    const SCTAG = 'tematikus-kategoria';

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
              'style' => 'boxed',
              'limit' => 5,
              'orderby' => 'rand',
              'utazas_kategoria' => false
            )
        );

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );
        $output = '<div class="'.self::SCTAG.'-holder style-'.$attr['style'].'"><div class="programs">';

        $searcher = new Searcher();
        $arg = array();
        $arg['limit'] = (int)$attr['limit'];
        $arg['orderby'] = $attr['orderby'];

        if ( $attr['utazas_kategoria'] !== false ) {
          $arg['filters']['type'] = $attr['utazas_kategoria'];
        }

        $t = new ShortcodeTemplates(__CLASS__.'/'.$attr['style']);

        $list = $searcher->Listing( $arg );

        foreach ( $list as $program ) {
          $output .= $t->load_template( array( 'travel' => $program ) );
        }
        $output .= '</div></div>';
        $output .= (new ShortcodeTemplates(__CLASS__.'/js'))->load_template();


        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

}

new TematikusKategoriaSc();

?>
