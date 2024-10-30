<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://lostwebdesigns.com
 * @since      1.0.0
 *
 * @package    Chat_Box
 * @subpackage Chat_Box/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
		<div class="col-md-6">
			<h2 class="nav-tab-wrapper">Login</h2>

			<form id="icechat_login" class="needs-validation" method="post" name="cleanup_options" action="options.php" novalidate>

			<?php
				$options = get_option($this->plugin_name);	
			?>


			<?php
				settings_fields( $this->plugin_name );
				do_settings_sections( $this->plugin_name );
			?>
			
			<?php wp_nonce_field( 'admin_registration', 'registration_nonce' ); ?>

			<fieldset>
				<label for="<?php echo $this->plugin_name;?>-email">
					<span><?php esc_attr_e('Email', $this->plugin_name);?></span>
				</label>
				<fieldset>
					<input type="hidden" name="formtype" value="<?php echo $this->plugin_name;?>_loginaction"/>
					<input type="hidden" name="formname" value="login"/>
					<input type="email" class="form-control regular-text" id="<?php echo $this->plugin_name;?>-loginemail" name="<?php echo $this->plugin_name;?>[userLoginEmailid]" value="<?php if(!empty($options['status']) && $options['status'] === 'Success'){if(isset($options['userEmailid']) && !empty($options['userEmailid'])) echo $options['userEmailid'];}?> <?php if(isset($options['userLoginEmailid']) && !empty($options['userLoginEmailid'])) echo $options['userLoginEmailid'];?>" <?php if(!empty($options['status']) && $options['status'] === 'Success'){echo "disabled";} ?> required>
				</fieldset>
			</fieldset>
			<?php if(empty($options['status']) || $options['status'] != 'Success') {submit_button('Login', 'primary','login', TRUE); }?>			
			<img class="login-submitted" style="display:none;" src="<?php echo  esc_url( plugins_url( 'img/ajax-loader.gif', dirname(__FILE__) ) );?>" >
			</form>
			
		</div>
	</div>
</div>
<script>
jQuery('#icechat_login').submit(function() {
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var loginemail = jQuery('#cbf-loginemail').val();
	if(loginemail == '' || !regex.test(loginemail)){
		return false;
	}
    jQuery('#login').hide();
    jQuery('.login-submitted').show();
});
</script>