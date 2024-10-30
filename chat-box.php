<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Chat-Box
 *
 * @wordpress-plugin
 * Plugin Name:       ICE Chat WordPress Plugin
 * Description:       Allow Chat on Wordpress based Websites..
 * Version:           1.0.0
 * Author:            Etech Global Services
 * Text Domain:       chat-box
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-chat-box-activator.php
 */
function activate_chat_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chat-box-activator.php';
	Cbf_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-chat-box-deactivator.php
 */
function deactivate_chat_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chat-box-deactivator.php';
	Cbf_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_chat_box' );
register_deactivation_hook( __FILE__, 'deactivate_chat_box' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-chat-box.php';

add_action( 'wp_ajax_nopriv_resetaction', 'resetaction_ajax_function' );
add_action( 'wp_ajax_resetaction', 'resetaction_ajax_function' );

function resetaction_ajax_function() {
	check_ajax_referer( 'reset_nonce', '_ajax_nonce' );
	$default = array(
		'firstName' => '',
		'lastName' => '',
		'userEmailid' => '',
		'phoneNumber' => '',
		'webSiteUrl' => '',
		'OrgName' => '',
	);
	update_option('cbf',$default);
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_chat_box() {

	$plugin = new Cbf();
	$plugin->run();

}
run_chat_box();
