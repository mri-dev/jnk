<?php
class DestinationSC
{
    const SCTAG = 'destinations';

    public function __construct()
    {
        add_action( 'init', array( &$this, 'register_shortcode' ) );
    }

    public function register_shortcode() {
        add_shortcode( self::SCTAG, array( &$this, 'do_shortcode' ) );
    }

    public function do_shortcode( $attr, $content = null )
    {
      global $wpdb;

      /* Set up the default arguments. */
      $defaults = apply_filters(
          self::SCTAG.'_defaults',
          array(
            'limit' => 6
          )
      );
      /* Parse the arguments. */
      $attr = shortcode_atts( $defaults, $attr );

      // View data term
      $pq = $wpdb->get_results("SELECT post_id, sum(viewcount) as vc FROM `travel_term_view` WHERE datediff(now(),dategroup) <= 30 GROUP BY post_id ORDER BY vc DESC LIMIT 0,".$attr['limit']);

      $top_post_ids = array();
      $dest_terms = array();

      if($pq){
        foreach ((array)$pq as $p) {
          $top_post_ids[] = $p->post_id;
        }
      }

      if (!empty($top_post_ids)) {
        foreach ((array)$top_post_ids as $tpi) {
            $topdest = wp_get_post_terms( $tpi, 'utazas_uticel');
            if($topdest) {
              foreach ($topdest as $td) {
                if($td->parent == 0) continue;
                $dest_terms[] = $td->term_id;
              }
            }
        }
      }

      $dest = get_terms(array(
        'taxonomy' => 'utazas_uticel',
        'number' => $attr['limit'],
        'hide_empty' => true,
        'orderby' => 'include',
        'include' => $dest_terms
      ));

      $attr['dest'] = $dest;

      $output = '<div class="'.self::SCTAG.'-holder">';
      $output .= (new ShortcodeTemplates('Destinations'))->load_template( $attr );
      $output .= '</div>';

      /* Return the output of the tooltip. */
      return apply_filters( self::SCTAG, $output );
    }
}

new DestinationSC();

?>
