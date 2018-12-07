<?php
class ContactFormSC
{
    const SCTAG = 'contact-form';
    public $avaiable_types = array('kapcsolat');

    public function __construct()
    {
        add_action( 'init', array( &$this, 'register_shortcode' ) );
    }

    public function register_shortcode() {
        add_shortcode( self::SCTAG, array( &$this, 'do_shortcode' ) );
    }

    public function do_shortcode( $attr, $content = null )
    {
        $button_text = __('Ajánlatot kérek', 'jnk');
        $whatisit = __('Ajánlatkérés', 'jnk');

    	  /* Set up the default arguments. */
        $defaults = apply_filters(
            self::SCTAG.'_defaults',
            array(
              'tipus' => 'kapcsolat',
              'width' => 100
            )
        );
        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );
        $pass_data = array();

        if ($attr['tipus'] == 'kapcsolat') {
          $button_text = __('Üzenet elküldése', 'jnk');
          $whatisit = __('Kapcsolat üzenet', 'jnk');
        }

        $pass_data['button_text'] = $button_text;
        $pass_data['whatisit'] = $whatisit;
        $pass_data = array_merge($pass_data, $attr);

        $output = '<div class="'.self::SCTAG.'-holder type-of-'.$attr['tipus'].'">';

        $output .= (new ShortcodeTemplates('Ajanlatkero'))->load_template( $pass_data );
        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

}

new ContactFormSC();

?>
