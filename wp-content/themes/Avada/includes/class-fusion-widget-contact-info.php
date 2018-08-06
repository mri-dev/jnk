<?php

class Fusion_Widget_Contact_Info extends WP_Widget {

	public function __construct() {

		$widget_ops  = array( 'classname' => 'contact_info', 'description' => '' );
		$control_ops = array( 'id_base' => 'contact_info-widget' );
		parent::__construct( 'contact_info-widget', 'Avada: Contact Info', $widget_ops, $control_ops );

	}

	public function widget( $args, $instance ) {

		extract( $args );

		$title = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>

		<div class="contact-info-container">
			<?php if ( isset( $instance['address'] ) && $instance['address'] ) : ?>
				<div class="address"><div class="h"><?php _e( 'Cím:', TD ); ?></div> <div class="v"><?php echo $instance['address']; ?></div></div>
			<?php endif; ?>

			<?php if ( isset( $instance['phone'] ) && $instance['phone'] ) : ?>
				<div class="phone"><div class="h"><?php _e( 'Phone:', 'Avada' ); ?></div> <div class="v"><?php echo $instance['phone']; ?></div></div>
			<?php endif; ?>

			<?php if ( isset( $instance['mobile'] ) && $instance['mobile'] ) : ?>
				<div class="mobile"><div class="h"><?php _e( 'Mobile:', 'Avada' ); ?></div> <div class="v"><?php echo $instance['mobile']; ?></div></div>
			<?php endif; ?>

			<?php if ( isset( $instance['fax'] ) && $instance['fax'] ) : ?>
				<div class="fax"><div class="h"><?php _e( 'Fax:', 'Avada' ); ?></div> <div class="v"><?php echo $instance['fax']; ?></div></div>
			<?php endif; ?>

			<?php if ( isset( $instance['email'] ) && $instance['email'] ) : ?>
				<div class="email"><div class="h"><?php _e( 'Email:', 'Avada' ); ?></div> <div class="v"><a href="mailto:<?php echo $instance['email']; ?>"><?php if ( $instance['emailtxt'] ) { echo $instance['emailtxt']; } else { echo $instance['email']; } ?></a></div></div>
			<?php endif; ?>

			<?php if ( isset( $instance['web'] ) && $instance['web'] ) : ?>
				<div class="web"><div class="h"><?php _e( 'Web:', 'Avada' ); ?></div> <div class="v"><a href="<?php echo $instance['web']; ?>"><?php if ( isset( $instance['webtxt'] ) && $instance['webtxt'] ) { echo $instance['webtxt']; } else { echo $instance['web']; } ?></a></div></div>
			<?php endif; ?>
		</div>
		<?php

		echo $after_widget;

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']    = $new_instance['title'];
		$instance['address']  = $new_instance['address'];
		$instance['phone']    = $new_instance['phone'];
		$instance['mobile']   = $new_instance['mobile'];
		$instance['fax']      = $new_instance['fax'];
		$instance['email']    = $new_instance['email'];
		$instance['emailtxt'] = $new_instance['emailtxt'];
		$instance['web']      = $new_instance['web'];
		$instance['webtxt']   = $new_instance['webtxt'];

		return $instance;

	}

	public function form( $instance ) {

		$defaults = array(
			'title'    => 'Contact Info',
			'address'  => '',
			'phone'    => '',
			'mobile'   => '',
			'fax'      => '',
			'email'    => '',
			'emailtxt' => '',
			'web'      => '',
			'webtxt'   => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php _e( 'Address:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" value="<?php echo $instance['address']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e( 'Phone:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo $instance['phone']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('mobile'); ?>"><?php _e( 'Mobile:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'mobile' ); ?>" name="<?php echo $this->get_field_name( 'mobile' ); ?>" value="<?php echo $instance['mobile']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fax' ); ?>"><?php _e( 'Fax:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'fax' ); ?>" name="<?php echo $this->get_field_name( 'fax' ); ?>" value="<?php echo $instance['fax']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e( 'Email:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'emailtxt' ); ?>"><?php _e( 'Email Link Text:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'emailtxt' ); ?>" name="<?php echo $this->get_field_name( 'emailtxt' ); ?>" value="<?php echo $instance['emailtxt']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'web' ); ?>"><?php _e( 'Website URL (with HTTP):', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'web' ); ?>" name="<?php echo $this->get_field_name( 'web' ); ?>" value="<?php echo $instance['web']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'webtxt' ); ?>"><?php _e( 'Website URL Text:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'webtxt' ); ?>" name="<?php echo $this->get_field_name( 'webtxt' ); ?>" value="<?php echo $instance['webtxt']; ?>" />
		</p>
		<?php

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
