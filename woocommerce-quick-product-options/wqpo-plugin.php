<?php
/**
* Plugin Name: Quick Product Options for WooCommerce
* Plugin URI: https://www.mdoulos.com/
* Description: Adds product options to product edit pages and displays them on the front end.
* Version: 1.0
* Author: MDoulos
* Author URI: http://www.mdoulos.com/
*
* Text Domain: wqpo
**/

/** **/

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/php/wqpo-start.php';


add_action( 'admin_enqueue_scripts', 'enqueue_wqpo_custom_admin_scripts' );
function enqueue_wqpo_custom_admin_scripts() {
    wp_enqueue_style( 'wqpo-admin-css', plugin_dir_url( __FILE__ ) . 'css/wqpo-admin-styles.css' );
	wp_enqueue_script( 'wqpo-admin-js', plugin_dir_url( __FILE__ ) . 'js/wqpo-admin.js', array(), null, true );
}

add_action( 'wp_enqueue_scripts', 'enqueue_wqpo_custom_frontend_styles' );
function enqueue_wqpo_custom_frontend_styles() {
	wp_enqueue_style( 'wqpo-styles', plugin_dir_url( __FILE__ ) . 'css/wqpo-styles.css' );
	wp_enqueue_script( 'wqpo-js', plugin_dir_url( __FILE__ ) . 'js/wqpo-frontend.js', array(), null, true );
}