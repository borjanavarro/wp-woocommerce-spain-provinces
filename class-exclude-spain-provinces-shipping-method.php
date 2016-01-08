<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Exclude Spain provinces shipping method
 *
 * @class 		WC_Exclude_Spain_Provinces_Shipping_Method
 * @version		1.0.0
 * @package		WooCommerce-Custom-Methods
 * @author 		Borja Navarro
 * @url 			https://es.linkedin.com/in/borjanavarrowd
 */
class WC_Exclude_Spain_Provinces_Shipping_Method extends WC_Shipping_Method {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id                 = 'exclude_provinces_method';
		$this->method_title       = __( 'Exclude Spain provinces', 'woocommerce' );
		$this->method_description = __( 'This shipping method allows to select specific countries and exclude specific Spain provinces', 'woocommerce' );

    $this->enabled            = $this->get_option( 'enabled' );
		$this->title              = $this->get_option( 'title' );

		$this->init();

	}

  function init() {
			// Load the settings API
			$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
			$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

			// Save settings in admin if you have any defined
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}

	/**
	 * Initialise settings form fields
	 */
	public function init_form_fields() {
		$this->form_fields = include( 'includes/settings-exclude-provinces.php' );
	}

	/**
	 * is_available function.
	 *
	 * @param array $package
	 * @return bool
	 */
	public function is_available( $package ) {

		//Checking plugin is enabled
		if ( "no" === $this->enabled ) {
			return false;
		}

		//Checking country is available
		$countries = $this->get_option( 'countries' );
		$availability = $this->get_option( 'availability' );

		if ( 'specific' === $availability ) {
			if ( is_array( $countries ) && ! in_array( $package['destination']['country'], $countries ) ) {
				return false;
			}
		}

		//Checking province is not excluded
		$user_state = $package['destination']['state'];
		$provinces = $this->get_option( 'provinces' );

		if ( $package['destination']['country'] === 'ES'){
			if ( is_array( $provinces ) && in_array( $user_state, $provinces ) ) {
					return false;
			}
		}

		return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package );
	}

	public function calculate_shipping($package){

		// send the final rate to the user.
    $this->add_rate( array(
      'id'   => $this->id,
      'label' => $this->title,
      'cost'   => $this->get_option( 'cost' )
    ));
	}
}
