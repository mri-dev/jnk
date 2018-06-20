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
              'orderby' => 'rand'
            )
        );

        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );
        $output = '<div class="'.self::SCTAG.'-holder style-'.$attr['style'].'"><div class="programs">';

        $arg = array();
        $arg['post_type'] = 'programok';
        $arg['orderby'] = $attr['orderby'];
        $arg['posts_per_page'] = (int)$attr['limit'];

        $programs = get_posts($arg);

        $t = new ShortcodeTemplates(__CLASS__.'/'.$attr['style']);

        foreach ( $programs as $program ) {
          $output .= $t->load_template( array( 'program' => $program ) );
        }
        $output .= '</div></div>';
        $output .= (new ShortcodeTemplates(__CLASS__.'/js'))->load_template();


        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

}

new TematikusKategoriaSc();

?>
