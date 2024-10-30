<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Chat_Box
 * @subpackage Chat_Box/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Chat_Box
 * @subpackage Chat_Box/admin
 * @author     Etech
 */
class Cbf_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Chat_Box_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Chat_Box_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chat-box-admin.css', array(), $this->version, 'all' );
		wp_register_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all');
		wp_enqueue_style('bootstrap-css');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Chat_Box_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Chat_Box_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chat-box-admin.js', array( 'jquery' ), $this->version, false );
		wp_register_script('bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array('jquery'), $this->version, true);
		wp_localize_script( 'bootstrap-js', 'postreset_object', array('ajax_url' => admin_url( 'admin-ajax.php' ) ,
		'ajax_nonce' => wp_create_nonce('reset_nonce'),));
		wp_enqueue_script('bootstrap-js');
		

	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		add_options_page( 'ChatBox and Base Options Functions Setup', 'Chat Box', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
		);
	}

	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		    */
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
		include_once( 'partials/chat-box-admin-registration.php' );
		include_once( 'partials/chat-box-admin-login.php' );
	}

	/**
	*  Save the plugin options
	*
	*
	* @since    1.0.0
	*/
	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array($this, 'validate') );
	}


	/**
	 * Validate all options fields
	 *
	 * @since    1.0.0
	 */
	 
	public function getGUID(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid =substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12);
			return $uuid;
		}
	}
	
	public function callAPI($method, $url, $data){
		try{
			$response = wp_remote_post( $url, $data );
			return $response['body'];
		}catch(exception $e){
			echo $e->getMessage();
			exit;
		}
	}
	
	public function validate($input) {
		$errors = [];
		$valid = array();	
		
		//verify Nonce in form submit submit
		
		if ( ! isset( $_POST['registration_nonce'] ) || ! wp_verify_nonce( $_POST['registration_nonce'], 'admin_registration' ) ){
			$errors[] = '';
			$valid['errors'] = $errors;
			$valid['status'] = 'error';
			return $valid;
		}
		
		if(isset($_POST['formname']) && $_POST['formname'] == 'login'){
			$sanitizedLogin_email = sanitize_email($_POST[$this->plugin_name]['userLoginEmailid']);
			//login Process
			if (empty(trim($sanitizedLogin_email)) || filter_var(trim($sanitizedLogin_email), FILTER_VALIDATE_EMAIL) === false) {
					$errors[] = 'Email is not valid';
			}else{
					$valid['userLoginEmailid'] = (isset($sanitizedLogin_email) && !empty($sanitizedLogin_email)) ? $sanitizedLogin_email : 0;
			}
			if (count($errors) > 0) {
				$valid['errors'] = $errors;
				$valid['status'] = 'error';
			}else{				
				$guid = $this->getGUID();
				// User Guid Api
				$guidstring = array();
				$guidstring['userGuid'] = $guid;		
				$args = array(
						'body' => $guidstring,
						'timeout' => '5',
						'redirection' => '5',
						'httpversion' => '1.0',
						'blocking' => true,
						'headers' => array(),
						'cookies' => array()
				);
				$data_string = http_build_query($guidstring);
				$make_call = $this->callAPI('POST', 'https://enterice.com/ICEInternalAPI/api/Plugin/GenerateTokenForVisitor?userGuid="'.$guid.'"', $args);
				$response = $make_call;
				
				if(is_string($response) && $response != ""){				
					$input['Token'] = str_replace('"', '', $response);
					$input['userEmailid'] = $valid['userLoginEmailid'];
					$login_param = http_build_query($input);
					$login_string = json_encode($input);
					$url = 'https://enterice.com/ICEInternalAPI/Api/Plugin/OrganizationExists';
					$args = array(
							'body' => $input,
							'timeout' => '5',
							'redirection' => '5',
							'httpversion' => '1.0',
							'blocking' => true,
							'headers' => array(),
							'cookies' => array()
					);
					$login_result = $this->callAPI('POST', $url, $args);
					
					if(is_string($login_result) && $login_result != ""){
						$datastring = json_decode($login_result);
						if($datastring->status == 1){
							$chatboxstring = $datastring->data;
							$valid['chatboxstring'] = $chatboxstring;
							$valid['status'] = 'Success';
						}else{
							$errors[] = 'Email address is not register with Ice Chat. Please register and try again.';
						}				
					}else{
						$errors[] = 'Login Process not done properly. Please try Again.';
					}					
				} else{
					$errors[] = 'Token Not generated Properly. Please try Again.';
				}			
			}		
			if (count($errors) > 0) {
				$valid['errors'] = $errors;
				$valid['status'] = 'error';				
			}
			return $valid;
		}else{			
			// Registration Process
			$sanitize_firstName = sanitize_text_field($input['firstName']);
			$sanitize_lastName = sanitize_text_field($input['lastName']);
			$sanitize_OrgName = sanitize_text_field($input['OrgName']);
			
			if (empty(trim($input['webSiteUrl'])) || filter_var(trim($input['webSiteUrl']), FILTER_VALIDATE_URL) === false) {
			$errors[] = 'Please enter a valid website address';
			}else{
				$valid['webSiteUrl'] = (isset($input['webSiteUrl']) && !empty($input['webSiteUrl'])) ? $input['webSiteUrl'] : 0;
			}

			if (empty(trim($input['userEmailid'])) || filter_var(trim($input['userEmailid']), FILTER_VALIDATE_EMAIL) === false) {
				$errors[] = 'Email is not valid';
			}else{
				$valid['userEmailid'] = (isset($input['userEmailid']) && !empty($input['userEmailid'])) ? $input['userEmailid'] : 0;
			}

			if (empty(trim($sanitize_firstName))) {
				$errors[] = 'First name is empty';
			}else{
				$valid['firstName'] = (isset($sanitize_firstName) && !empty($sanitize_firstName)) ? $sanitize_firstName: 0;
			}

			if (empty(trim($sanitize_lastName))) {
				$errors[] = 'Last name is empty';
			}else{
				$valid['lastName'] = (isset($sanitize_lastName) && !empty($sanitize_lastName)) ? $sanitize_lastName : 0;
			}
			
			if (empty(trim($input['phoneNumber']))) {
				$errors[] = 'Phone Number is empty';
			}
			//elseif(!preg_match('/^([0-9]*)$/', $input['phoneNumber']) && !preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $input['phoneNumber'])){
			elseif(!preg_match('/^[0-9*#() .+-]+$/', $input['phoneNumber']) || strlen($input['phoneNumber']) < 10){
				$errors[] = 'Valid Phone Number Required.';
			}else{
				$valid['phoneNumber'] = (isset($input['phoneNumber']) && !empty($input['phoneNumber'])) ? $input['phoneNumber'] : 0;
			}
			
			if (empty(trim($sanitize_OrgName))) {
				$errors[] = 'Organization Name is empty';
			}else{
				$valid['OrgName'] = (isset($sanitize_OrgName) && !empty($sanitize_OrgName)) ? $sanitize_OrgName : 0;
			}

			if (count($errors) > 0) {
				$valid['errors'] = $errors;
				$valid['status'] = 'error';
				return $valid;
			}
						
			$guid = $this->getGUID();
			
			// User Guid Api
			$guidstring = array();
			$guidstring['userGuid'] = $guid;		
			$data_string = http_build_query($guidstring);
			
			$args = array(
							'body' => $guidstring,
							'timeout' => '5',
							'redirection' => '5',
							'httpversion' => '1.0',
							'blocking' => true,
							'headers' => array(),
							'cookies' => array()
					);
			$make_call = $this->callAPI('POST', 'https://enterice.com/ICEInternalAPI/api/Plugin/GenerateTokenForVisitor?userGuid="'.$guid.'"', $args);

			$response = $make_call;
			if(is_string($response) && $response != ""){
				
				$input['Token'] = str_replace('"', '', $response);
				$valid['Token'] = (isset($input['Token']) && !empty($input['Token'])) ? $input['Token'] : 0;
				$string = json_encode($input);
				$registration_string = htmlspecialchars(http_build_query($input));
				
					$access = array();					
					$input['Token'] = str_replace('"', '', $response);
					$access['Token'] = $input['Token'];
					$access['userEmailid'] = $valid['userEmailid'];
					$url = 'https://enterice.com/ICEInternalAPI/Api/Plugin/OrganizationExists';
					
					$args = array(
							'body' => $access,
							'timeout' => '5',
							'redirection' => '5',
							'httpversion' => '1.0',
							'blocking' => true,
							'headers' => array(),
							'cookies' => array()
					);
					$login_result = $this->callAPI('POST', $url, $args);
					
					if(is_string($login_result) && $login_result != ""){
						$datastring = json_decode($login_result);
						if($datastring->status == 1){
							$errors[] = 'You have already registered with Ice Chat, please use email address to Login.';
						}else{
						
							// Registration Api
							$headers = array( 
								'Content-type' => 'application/x-www-form-urlencoded',
							);
							$args = array(
								'method' => 'POST',
								'timeout' => 5,
								'redirection' => 5,
								'httpversion' => '1.0',
								'blocking' => true,
								'headers' => $headers,
								'body' => $input,								
								'cookies' => array()
							);
							
							
							$registration_call = $this->callAPI('POST', 'https://enterice.com/ICEInternalAPI/Api/Plugin/OrganizationRegistration', $args);
							
							if(is_string($registration_call) && $registration_call != ""){
									$datastring = json_decode($registration_call);
									if($datastring != "" && $datastring->status == 1){
										$chatboxstring = $datastring->data;
										$valid['chatboxstring'] = $chatboxstring;
										$valid['status'] = 'Success';
									}else{
										$errors[] = 'Registration Process not done properly. Please try Again.';
									}				
								}else{
									$errors[] = 'Registration Process not done properly. Please try Again.';
								}	
						}				
					}else{
						$errors[] = 'Please try Again';
					}					
			} else{
				$errors[] = 'Token Not generated Properly. Please try Again.';
			}
			if (count($errors) > 0) {
				$valid['errors'] = $errors;
				$valid['status'] = 'error';			
			}		
			return $valid;
		}
	}
}
