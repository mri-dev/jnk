<?php

class Avada_Layout {

	public $sidebars = array();

	/**
	 * The class constructor
	 */
	public function __construct() {
		add_action( 'wp', array( $this, 'add_sidebar' ), 20 );
		// add_action( 'wp', array( $this, 'get_content_width' ), 20 ); WIP ITEM FOR #746
	}

	/**
	 * Add sidebar(s) to the pages
	 *
	 * @return void
	 */
	public function add_sidebar() {
		// Get the sidebars and assign to public variable
		$this->sidebars = $this->get_sidebar_settings( $this->sidebar_options() );

		// Set styling to content and sidebar divs
		$this->layout_structure_styling( $this->sidebars );

		// Append sidebar to after content div
		if ( Avada()->template->has_sidebar() && ! Avada()->template->double_sidebars() ) {
			add_action( 'fusion_after_content', array( $this, 'append_sidebar_single' ) );
		} elseif ( Avada()->template->double_sidebars() ) {
			add_action( 'fusion_after_content', array( $this, 'append_sidebar_double' ) );
		} elseif ( ! Avada()->template->has_sidebar() && ( is_page_template( 'side-navigation.php') || is_singular( 'tribe_events' ) ) ) {
			add_action( 'fusion_after_content', array( $this, 'append_sidebar_single' ) );
		}

	}

	/**
	 * Get sidebar settings based on the page type
	 *
	 * @return array
	 */
	public function sidebar_options() {
		if ( is_home() ) {
			$sidebars = array(
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'blog_archive_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'blog_archive_sidebar_2' ),
				'position'  => Avada()->settings->get( 'blog_sidebar_position' ),
			);
		} elseif ( is_bbpress() ) {
			$sidebars = array(
				'global'    => Avada()->settings->get( 'bbpress_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'ppbress_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'ppbress_sidebar_2' ),
				'position'  => Avada()->settings->get( 'bbpress_sidebar_position' ),
			);

			if ( bbp_is_forum_archive() || bbp_is_topic_archive() || bbp_is_user_home() || bbp_is_search() ) {
				$sidebars = array(
					'global'    => '1',
					'sidebar_1' => Avada()->settings->get( 'ppbress_sidebar' ),
					'sidebar_2' => Avada()->settings->get( 'ppbress_sidebar_2' ),
					'position'  => Avada()->settings->get( 'bbpress_sidebar_position' ),
				);
			}
		} elseif ( is_buddypress() ) {
			$sidebars = array(
				'global'    => Avada()->settings->get( 'bbpress_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'ppbress_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'ppbress_sidebar_2' ),
				'position'  => Avada()->settings->get( 'bbpress_sidebar_position' ),
			);
		} elseif ( class_exists( 'WooCommerce' ) && ( is_product() || is_shop() ) ) {
			$sidebars = array(
				'global'    => Avada()->settings->get( 'woo_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'woo_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'woo_sidebar_2' ),
				'position'  => Avada()->settings->get( 'woo_sidebar_position' ),
			);
		} elseif ( class_exists( 'WooCommerce' ) && ( is_product_category() || is_product_tag() || is_tax( 'product_brand' ) || is_tax( 'images_collections' ) ) ) {
			$sidebars = array(
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'woocommerce_archive_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'woocommerce_archive_sidebar_2' ),
				'position'  => Avada()->settings->get( 'woo_sidebar_position' ),
			);
		} elseif ( is_page() ) {
			$sidebars = array(
				'global'    => Avada()->settings->get( 'pages_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'pages_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'pages_sidebar_2' ),
				'position'  => Avada()->settings->get( 'default_sidebar_pos' ),
			);
		} elseif ( is_single() ) {
			$sidebars = array(
				'global'    => Avada()->settings->get( 'posts_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'posts_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'posts_sidebar_2' ),
				'position'  => Avada()->settings->get( 'blog_sidebar_position' ),
			);

			if ( is_singular( 'avada_portfolio' ) ) {
				$sidebars = array(
					'global'    => Avada()->settings->get( 'portfolio_global_sidebar' ),
					'sidebar_1' => Avada()->settings->get( 'portfolio_sidebar' ),
					'sidebar_2' => Avada()->settings->get( 'portfolio_sidebar_2' ),
					'position'  => Avada()->settings->get( 'portfolio_sidebar_position' ),
				);
			} else if ( is_singular( 'tribe_events' ) || is_singular( 'tribe_organizer' ) || is_singular( 'tribe_venue' ) ) {
				$sidebars = array(
					'global'    => Avada()->settings->get( 'ec_global_sidebar' ),
					'sidebar_1' => Avada()->settings->get( 'ec_sidebar' ),
					'sidebar_2' => Avada()->settings->get( 'ec_sidebar_2' ),
					'position'  => Avada()->settings->get( 'ec_sidebar_pos' ),
				);
			}

			if ( is_singular( 'tribe_organizer' ) || is_singular( 'tribe_venue' ) ) {
				$sidebars['global'] = 1;
			}
		} elseif ( is_archive() ) {
			$sidebars = array(
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'blog_archive_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'blog_archive_sidebar_2' ),
				'position'  => Avada()->settings->get( 'blog_sidebar_position' ),
			);

			if ( is_post_type_archive( 'avada_portfolio' ) || is_tax( 'portfolio_category' ) || is_tax( 'portfolio_skills' ) || is_tax( 'portfolio_tags' ) ) {
				$sidebars = array(
					'global'    => '1',
					'sidebar_1' => Avada()->settings->get( 'portfolio_archive_sidebar' ),
					'sidebar_2' => Avada()->settings->get( 'portfolio_archive_sidebar_2' ),
					'position'  => Avada()->settings->get( 'portfolio_sidebar_position' ),
				);
			}
		} elseif ( is_search() ) {
			$sidebars = array(
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'search_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'search_sidebar_2' ),
				'position'  => Avada()->settings->get( 'search_sidebar_position' ),
			);
		} else {
			$sidebars = array(
				'global'    => Avada()->settings->get( 'pages_global_sidebar' ),
				'sidebar_1' => Avada()->settings->get( 'pages_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'pages_sidebar_2' ),
				'position'  => Avada()->settings->get( 'default_sidebar_pos' ),
			);
		}

		if ( is_events_archive() ) {
			$sidebars = array(
				'global'    => '1',
				'sidebar_1' => Avada()->settings->get( 'ec_sidebar' ),
				'sidebar_2' => Avada()->settings->get( 'ec_sidebar_2' ),
				'position'  => Avada()->settings->get( 'ec_sidebar_pos' ),
			);
		}

		// Remove sidebars from the certain woocommerce pages
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_cart() || is_checkout() || is_account_page() || ( get_option( 'woocommerce_thanks_page_id' ) && is_page( get_option( 'woocommerce_thanks_page_id' ) ) ) ) {
				$sidebars = array();
			}
		}

		return $sidebars;
	}

	/**
	 * Get the sidebars
	 *
	 * @param array $sidebar_options
	 *
	 * @return array
	 */
	public function get_sidebar_settings( $sidebar_options = array() ) {
		// Post options
		$sidebar_1                    = get_post_meta( Avada::c_pageID(), 'sbg_selected_sidebar_replacement', true );
		$sidebar_2                    = get_post_meta( Avada::c_pageID(), 'sbg_selected_sidebar_2_replacement', true );
		$sidebar_position_post_option = strtolower( get_post_meta( Avada::c_pageID(), 'pyre_sidebar_position', true ) );
		$sidebar_position_metadata    = metadata_exists( 'post', Avada::c_pageID(), 'pyre_sidebar_position' );

		if ( is_array( $sidebar_1 ) && '0' === $sidebar_1[0] ) {
			$sidebar_1 = array( 'Blog Sidebar' );
		}

		if ( is_array( $sidebar_2 ) && '0' === $sidebar_2[0] ) {
			$sidebar_2 = array( 'Blog Sidebar' );
		}

		// Theme options
		$sidebar_position_theme_option = array_key_exists( 'position', $sidebar_options ) ? strtolower( $sidebar_options['position'] ) : '';

		// Set default sidebar position
		$sidebar_position = $sidebar_position_post_option;

		// Get sidebars and position from theme options if it's being forced globally
		if ( array_key_exists( 'global', $sidebar_options ) && $sidebar_options['global'] ) {
			$sidebar_1 = array( ( 'None' != $sidebar_options['sidebar_1'] ) ? $sidebar_options['sidebar_1'] : '' );
			$sidebar_2 = array( ( 'None' != $sidebar_options['sidebar_2'] ) ? $sidebar_options['sidebar_2'] : '' );

			$sidebar_position = $sidebar_position_theme_option;
		}

		// If sidebar position is default OR no entry in database exists
		if ( 'default' == $sidebar_position || ! $sidebar_position_metadata ) {
			$sidebar_position = $sidebar_position_theme_option;
		}

		// Reverse sidebar position if double sidebars are used and position is right
		if ( Avada()->template->double_sidebars() && 'right' == $sidebar_position ) {
			$sidebar_1_placeholder = $sidebar_1;
			$sidebar_2_placeholder = $sidebar_2;

			// Reverse the sidebars
			$sidebar_1 = $sidebar_2_placeholder;
			$sidebar_2 = $sidebar_1_placeholder;
		}

		$return = array( 'position' => $sidebar_position );

		if ( $sidebar_1 ) {
			$return['sidebar_1'] = $sidebar_1[0];
		}

		if ( $sidebar_2 ) {
			$return['sidebar_2'] = $sidebar_2[0];
		}

		return $return;
	}

	/**
	 * Apply inline styling and classes to the structure
	 *
	 * @param array $sidebars
	 *
	 * @return void
	 */
	public function layout_structure_styling( $sidebars ) {

		// Add sidebar class
		add_filter( 'fusion_sidebar_1_class', array( $this, 'sidebar_class' ) );
		add_filter( 'fusion_sidebar_2_class', array( $this, 'sidebar_class' ) );

		// Check for sidebar location and apply styling to the content or sidebar div
		if ( ! Avada()->template->has_sidebar() && ! ( is_page_template( 'side-navigation.php') || is_singular( 'tribe_events' ) ) ) {
			add_filter( 'fusion_content_style', array( $this, 'full_width_content_style' ) );

			if ( is_archive() || is_home() ) {
				add_filter( 'fusion_content_class', array( $this, 'full_width_content_class' ) );
			}
		} elseif ( 'left' == $sidebars['position'] ) {
			add_filter( 'fusion_content_style', array( $this, 'float_right_style' ) );
			add_filter( 'fusion_sidebar_1_style', array( $this, 'float_left_style' ) );
			add_filter( 'fusion_sidebar_1_class', array( $this, 'side_nav_left_class' ) );
		} elseif ( 'right' == $sidebars['position'] ) {
			add_filter( 'fusion_content_style', array( $this, 'float_left_style' ) );
			add_filter( 'fusion_sidebar_1_style', array( $this, 'float_right_style' ) );
			add_filter( 'fusion_sidebar_1_class', array( $this, 'side_nav_right_class' ) );
		}

		// Page has a single sidebar
		// if ( Avada()->template->has_sidebar() && ! Avada()->template->double_sidebars() ) {}

		// Page has double sidebars
		if ( Avada()->template->double_sidebars() ) {
			add_filter( 'fusion_content_style', array( $this, 'float_left_style' ) );
			add_filter( 'fusion_sidebar_1_style', array( $this, 'float_left_style' ) );
			add_filter( 'fusion_sidebar_2_style', array( $this, 'float_left_style' ) );

			if ( 'right' == $sidebars['position'] ) {
				add_filter( 'fusion_sidebar_2_class', array( $this, 'side_nav_right_class' ) );
			}
		}

	}

	/**
	 * Append single sidebar to a page
	 *
	 * @return void
	 */
	public function append_sidebar_single() {
		get_template_part( 'templates/sidebar', '1' );
	}

	/**
	 * Append double sidebar to a page
	 *
	 * @return void
	 */
	public function append_sidebar_double() {
		get_template_part( 'templates/sidebar', '1' );
		get_template_part( 'templates/sidebar', '2' );
	}

	/**
	 * Join the elements
	 *
	 * @param string $filter_id
	 * @param string $sanitize
	 * @param string $join_separator
	 *
	 * @return string
	 */
	public function join( $filter_id = null, $sanitize = 'esc_attr', $join_separator = ' ' ) {

		// Get the elements using a filter
		$elements = apply_filters( 'fusion_' . $filter_id, array() );

		// Make sure each element is properly sanitized
		$elements = array_map( $sanitize, $elements );

		// Make sure there are no duplicate items
		$elements = array_unique( $elements );

		// Combine the elements of the array and return the combined string
		return join( $join_separator, $elements );

	}

	/**
	 * Filter to add inline styling
	 *
	 * @return void
	 */
	public function add_style( $filter ) {
		echo 'style="' . $this->join( $filter ) . '"';
	}

	/**
	 * Filter to add class
	 *
	 * @return void
	 */
	public function add_class( $filter ) {
		echo 'class="' . $this->join( $filter ) . '"';
	}

	/**
	 * Full width page inline styling
	 *
	 * @return array
	 */
	public function full_width_content_style( $styles ) {
		$styles[] = 'width: 100%;';
		return $styles;
	}

	/**
	 * Full width class
	 *
	 * @return array
	 */
	public function full_width_content_class( $classes ) {
		$classes[] = 'full-width';
		return $classes;
	}

	/**
	 * Float right styling
	 *
	 * @return array
	 */
	public function float_right_style( $styles ) {
		$styles[] = 'float: right;';
		return $styles;
	}

	/**
	 * Float left styling
	 *
	 * @return array
	 */
	public function float_left_style( $styles ) {
		$styles[] = 'float: left;';
		return $styles;
	}

	/**
	 * Add sidebar class to the sidebars
	 *
	 * @return array
	 */
	public function sidebar_class( $classes ) {
		$classes[] = 'sidebar fusion-widget-area fusion-content-widget-area';
		return $classes;
	}

	/**
	 * Add side nav right class when sidebar position is right
	 *
	 * @return array
	 */
	public function side_nav_right_class( $classes ) {
		if ( is_page_template( 'side-navigation.php' ) ) {
			$classes[] = 'side-nav-right';
		}
		return $classes;
	}

	/**
	 * Add side nav left class when sidebar position is left
	 *
	 * @return array
	 */
	public function side_nav_left_class( $classes ) {
		if ( is_page_template( 'side-navigation.php' ) ) {
			$classes[] = 'side-nav-left';
		}
		return $classes;
	}
	
	/**
	 * Checks is the current page is a 100% width page.
	 *
	 * @param integer $page_id     A custom page ID.
	 * @return bool
	 */
	public function is_hundred_percent_template( $page_id = false ) {
		if ( ! $page_id ) {
			$page_id = $c_pageID = Avada()->c_pageID();
		}
		
		$page_template = '';
	
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			$custom_fields = get_post_custom_values( '_wp_page_template', $c_pageID );
			$page_template = ( is_array( $custom_fields ) && ! empty( $custom_fields ) ) ? $custom_fields[0] : '';
		}

		if ( 'tribe_events' == get_post_type( $c_pageID ) && '100-width.php' == tribe_get_option( 'tribeEventsTemplate', 'default' ) ) {
			$page_template = '100-width.php';
		}

		if (
			'100%' == Avada()->settings->get( 'site_width' ) ||
			is_page_template( '100-width.php' ) ||
			is_page_template( 'blank.php' ) ||
			'100-width.php' == $page_template ||
			( ( '1' == fusion_get_option( 'portfolio_width_100', 'portfolio_width_100', $c_pageID ) || 'yes' == fusion_get_option( 'portfolio_width_100', 'portfolio_width_100', $c_pageID ) ) && is_singular( 'avada_portfolio' ) ) ||
			( ( '1' == fusion_get_option( 'blog_width_100', 'portfolio_width_100', $c_pageID ) || 'yes' == fusion_get_option( 'blog_width_100', 'portfolio_width_100', $c_pageID ) ) && is_singular( 'post' ) ) ||
			( 'yes' == fusion_get_page_option( 'portfolio_width_100', $c_pageID ) && ! is_singular( array( 'post', 'avada_portfolio' ) ) ) ||
			( avada_is_portfolio_template() && 'yes' == get_post_meta( $c_pageID, 'pyre_portfolio_width_100', true ) )
		) {
			return true;
		} else {
			return false;
		}
	}
	
	public function is_current_wrapper_hundred_percent() {
		if ( $this->is_hundred_percent_template() ) {
			global $fusion_fwc_type;

			if ( ! isset( $fusion_fwc_type ) ||
				 ( isset( $fusion_fwc_type ) && ( '' == $fusion_fwc_type || 'fullwidth' == $fusion_fwc_type ) )
			) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		} 
		
	}
		
	/**
	 * Get column width of the current page.
	 *
	 * @param integer|string $site_width     A custom site width.
	 * @return integer
	 */
	public function get_content_width( $site_width = 0 ) {
		/**
		 * The content width
		 */
		$options = get_option( Avada::get_option_name() );
		$page_padding = 0;
		
		if ( ! $site_width ) {
			$site_width = ( isset( $options['site_width'] ) ) ? $options['site_width'] : '1100px';
			
			if ( $this->is_current_wrapper_hundred_percent() ) {

				$site_width = '100%';				

				// Get 100% Width Left/Right Padding
				$page_padding = ( isset( $options['hundredp_padding'] ) ) ? $options['hundredp_padding'] : '0';
				
				// 100% Width Left/Right Padding is using %
				if ( false !== strpos( $page_padding, '%' ) ) {
					$page_padding = Avada_Helper::percent_to_pixels( $page_padding );
				}
				// 100% Width Left/Right Padding is using ems
				elseif ( false !== strpos( $page_padding, 'em' ) ) {
					$page_padding = Avada_Helper::ems_to_pixels( $page_padding );
				}
				
			}
			
		}
		
		if ( intval( $site_width ) ) {
			// Site width is using %
			if ( false !== strpos( $site_width, '%' ) ) {
				$site_width = Avada_Helper::percent_to_pixels( $site_width );
				
				// Subtract side header width from remaining content width
				$side_header_width = ( 'Top' == Avada()->settings->get( 'header_position' ) ) ? 0 : intval( Avada()->settings->get( 'side_header_width' ) );
				$site_width -= $side_header_width;
			}
			// Site width is using ems
			elseif ( false !== strpos( $site_width, 'em' ) ) {
				$site_width = Avada_Helper::ems_to_pixels( $site_width );
			}
		} else {
			// fallback to 1100px
			$site_width = 1100;
		}
		
		$site_width -= 2 * $page_padding;
		
		/**
		 * Sidebars width
		 */
		$sidebar_1_width = 0;
		$sidebar_2_width = 0;
		if ( Avada()->template->has_sidebar() && ! Avada()->template->double_sidebars() ) {
			if ( 'tribe_events' == get_post_type() ) {
				$sidebar_1_width = Avada()->settings->get( 'ec_sidebar_width' );
			} else {
				$sidebar_1_width = Avada()->settings->get( 'sidebar_width' );
			}
		} elseif ( Avada()->template->double_sidebars() ) {
			if ( 'tribe_events' == get_post_type() ) {
				$sidebar_1_width = Avada()->settings->get( 'ec_sidebar_2_1_width' );
				$sidebar_2_width = Avada()->settings->get( 'ec_sidebar_2_2_width' );
			} else {
				$sidebar_1_width = Avada()->settings->get( 'sidebar_2_1_width' );
				$sidebar_2_width = Avada()->settings->get( 'sidebar_2_2_width' );
			}
		} elseif ( ! Avada()->template->has_sidebar() && ( is_page_tem