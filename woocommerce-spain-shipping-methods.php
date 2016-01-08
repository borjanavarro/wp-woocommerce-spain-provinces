<?php
/*
Plugin Name: WooCommerce Custom Shipping Methods
Plugin URI: https://es.linkedin.com/in/borjanavarrowd
Description: WooCommerce custom methods
Version: 1.0.0
Author: Borja Navarro
*/

/**
 * Check if WooCommerce is active
 */

$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

if ( in_array( 'woocommerce/woocommerce.php', $active_plugins) ) {

 add_filter( 'woocommerce_shipping_methods', 'add_custom_shipping_method' );
 function add_custom_shipping_method( $methods ) {
   $methods[] = 'WC_Include_Spain_Provinces_Shipping_Method';
   $methods[] = 'WC_Exclude_Spain_Provinces_Shipping_Method';
   return $methods;
 }

 add_action( 'woocommerce_shipping_init', 'custom_shipping_method_init' );
 function custom_shipping_method_init(){
   require_once 'class-include-spain-provinces-shipping-method.php';
   require_once 'class-exclude-spain-provinces-shipping-method.php';
 }
}
