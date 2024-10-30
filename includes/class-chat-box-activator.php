<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Chat_Box
 * @subpackage Chat_Box/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Chat_Box
 * @subpackage Chat_Box/includes
 * @author     Etech
 */
class Cbf_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
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

}
