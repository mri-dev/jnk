<?php
class SearcherSc
{
    const SCTAG = 'searcher';

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
            'utazas_kategoria' => false,
            'cities' => false,
            'kiemelt' => 0,
            'limit' => 30,
            'orderby' => false
          )
      );
      /* Parse the arguments. */
      $attr = shortcode_atts( $defaults, $attr );

      $searcher = new Searcher();
      $arg = array();

      if ( $attr['kiemelt'] == 1 ) {
        $arg['filters']['kiemelt'] = 1;
      }

      if ( $attr['orderby'] ) {
        $arg['orderby'] = $attr['orderby'];
      }

      if ( $attr['utazas_kategoria'] !== false ) {
        $arg['filters']['type'] = $attr['utazas_kategoria'];
      }

      if ( $attr['cities'] !== false ) {
        $arg['filters']['cities'] = $attr['cities'];
      }

      $arg['limit'] = (int)$attr['limit'];

    	$list = $searcher->Listing( $arg );

      $output = '<div class="'.self::SCTAG.'-holder style-home"><div class="travels">';

      $t = new ShortcodeTemplates(__CLASS__.'/standard');

      foreach ( $list as $travel )
      {
        $attr['travel'] = $travel;
        $output .= $t->load_template($attr);
      }
      $output .= (new ShortcodeTemplates(__CLASS__.'/js'))->load_template();

      $output .= '</div></div>';


      /* Return the output of the tooltip. */
      return apply_filters( self::SCTAG, $output );
  }

}

new SearcherSc();

?>
