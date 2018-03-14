<?php
class PostListSc
{
    const SCTAG = 'postlist';

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

          )
      );

      /* Parse the arguments. */
      $attr = shortcode_atts( $defaults, $attr );
      $output = '<div class="'.self::SCTAG.'-holder style-home">';

      $posts = get_posts(array(
        'posts_per_page' => 4,
        'orderby' => 'date',
        'order' => 'DESC'
      ));

      $t = new ShortcodeTemplates(__CLASS__.'/home');
      $attr['posts'] = $posts;
      $output .= $t->load_template($attr);
      $output .= '</div>';


      /* Return the output of the tooltip. */
      return apply_filters( self::SCTAG, $output );
  }

}

new PostListSc();

?>
