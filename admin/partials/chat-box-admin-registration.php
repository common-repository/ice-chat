<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Chat_Box
 * @subpackage Chat_Box/admin/partials
 */
 
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container">
	<div class="row">
		<div class="col-md-6">			
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
		</div>
		<div class="col-md-6">
		<?php wp_nonce_field( 'admin_reset', 'reset_nonce' ); ?>
		<button id="chatreset" class="button button-primary" value="reset">Reset</button>
		<img class="ajax-submitted" style="display:none;" src="<?php echo  esc_url( plugins_url( 'img/ajax-loader.gif', dirname(__FILE__) ) );?>" >
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">			

			<h2 class="nav-tab-wrapper">Registration</h2>
			<p>If you have already registered. Go for Login.</p>
			<?php settings_errors($this->plugin_name); ?>
			<?php
			//Grab all options
				$options = get_option($this->plugin_name);	
			?>
			<?php if(!empty($options['status']) && $options['status'] === 'error'): ?>
				<div class="userway_code_error">
					<?php foreach($options['errors'] as $err) { ?>
					<p><strong>Error:</strong> <?= $err ?></p>
					<?php } ?>
				</div>
			<?php endif; ?>
			
			<?php if(!empty($options['status']) && $options['status'] === 'Success'): ?>
				<p>Thank you, your registration is completed and you will received an email for further details.</p>				
			<?php endif; ?>
			
			<?php if(isset($_GET['error']) && $_GET['error'] === 1): ?>
				<div class="userway_code_error">
					<p><strong>Error:</strong> Registration Process not done properly. Please try Again.</p>					
				</div>
			<?php endif; ?>

			<form id="icechat_registration" class="needs-validation" method="post" name="cleanup_options" action="options.php" novalidate>

			<?php
				settings_fields( $this->plugin_name );
				do_settings_sections( $this->plugin_name );
			?>
			<?php wp_nonce_field( 'admin_registration', 'registration_nonce' ); ?>
			<fieldset>
				<label for="<?php echo $this->plugin_name;?>-firstname">
					<span><?php esc_attr_e('First Name', $this->plugin_name);?></span>
				</label>
				<fieldset>
					<input type="hidden" name="formtype" value="<?php echo $this->plugin_name;?>_registration"/>
					<input type="text" class="form-control regular-text" id="<?php echo $this->plugin_name;?>-firstname" name="<?php echo $this->plugin_name;?>[firstName]" value="<?php if(isset($options['firstName']) && !empty($options['firstName'])) echo $options['firstName'];?>" <?php if(!empty($options['status']) && $options['status'] === 'Success'){echo "disabled";} ?> required>
				</fieldset>
				<div class="invalid-feedback">
				  Please choose a username.
				</div>
			</fieldset>
			
			<fieldset>
				<label for="<?php echo $this->plugin_name;?>-lastname">
					<span><?php esc_attr_e('Last Name', $this->plugin_name);?></span>
				</label>
				<fieldset>
					<input type="text" class="form-control regular-text" id="<?php echo $this->plugin_name;?>-lastname" name="<?php echo $this->plugin_name;?>[lastName]" value="<?php if(isset($options['lastName']) && !empty($options['lastName'])) echo $options['lastName'];?>" <?php if(!empty($options['status']) && $options['status'] === 'Success'){echo "disabled";} ?> required>
				</fieldset>
			</fieldset>
			
			<fieldset>
				<label for="<?php echo $this->plugin_name;?>-email">
					<span><?php esc_attr_e('Email', $this->plugin_name);?></span>
				</label>
				<fieldset>
					<input type="email" class="form-control regular-text" id="<?php echo $this->plugin_name;?>-email" name="<?php echo $this->plugin_name;?>[userEmailid]" value="<?php if(isset($options['userEmailid']) && !empty($options['userEmailid'])) echo $options['userEmailid'];?>" <?php if(!empty($options['status']) && $options['status'] === 'Success'){echo "disabled";} ?> required>
				</fieldset>
			</fieldset>
			
			<fieldset>
				<label for="<?php echo $this->plugin_name;?>-phonenumber">
					<span><?php esc_attr_e('Phone Number', $this->plugin_name);?></span>
				</label>
				<fieldset>
					<input type="text" class="form-control regular-text" id="<?php echo $this->plugin_name;?>-phonenumber" name="<?php echo $this->plugin_name;?>[phoneNumber]" value="<?php if(isset($options['phoneNumber']) && !empty($options['phoneNumber'])) echo $options['phoneNumber'];?>" <?php if(!empty($options['status']) && $options['status'] === 'Success'){echo "disabled";} ?> required>
				</fieldset>
			</fieldset>
			
			<fieldset>
				<label for="<?php echo $this->plugin_name;?>-websiteurl">
					<span><?php esc_attr_e('Website URL', $this->plugin_name);?></span>
				</label>
				<fieldset>
					<input type="text" class="form-control regular-text" id="<?php echo $this->plugin_name;?>-websiteurl" name="<?php echo $this->plugin_name;?>[webSiteUrl]" value="<?php if(isset($options['webSiteUrl']) && !empty($options['webSiteUrl'])) echo esc_url($options['webSiteUrl']);?>" <?php if(!empty($options['status']) && $options['status'] === 'Success'){echo "disabled";} ?> required>
				</fieldset>
			</fieldset>
			
			<fieldset>
				<label for="<?php echo $this->plugin_name;?>-organization">
					<span><?php esc_attr_e('Organization Name', $this->plugin_name);?></span>
				</label>
				<fieldset>
					<input type="text" class="form-control regular-text" id="<?php echo $this->plugin_name;?>-organization" name="<?php echo $this->plugin_name;?>[OrgName]" value="<?php if(isset($options['OrgName']) && !empty($options['OrgName'])) echo $options['OrgName'];?>" <?php if(!empty($options['status']) && $options['status'] === 'Success'){echo "disabled";} ?> required>
				</fieldset>
			</fieldset>
					<?php if(empty($options['status']) || $options['status'] != 'Success'){submit_button('Save all changes', 'primary','submit', TRUE); } ?>
					<img class="submitted" style="display:none;" src="<?php echo  esc_url( plugins_url( 'img/ajax-loader.gif', dirname(__FILE__) ) );?>" >					
			</form>

		</div>

<script>
jQuery("#chatreset").click(function(){
	jQuery('.ajax-submitted').show();
	jQuery.ajax({
		url: ajaxurl, // this is the object instantiated in wp_localize_script function
		type: 'POST',
		data:{ 
			_ajax_nonce: postreset_object.ajax_nonce,
			action: 'resetaction'
		},
		success: function( data ){
			jQuery('.ajax-submitted').hide();
			location.reload();
		}
	});
});

jQuery('#icechat_registration').submit(function() {
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var regfirstname = jQuery('#cbf-firstname').val();
	var reglastname = jQuery('#cbf-lastname').val();
	var regemail = jQuery('#cbf-email').val();
	var regphonenumber = jQuery('#cbf-phonenumber').val();
	var regwebsiteurl = jQuery('#cbf-websiteurl').val();
	var regorganization = jQuery('#cbf-organization').val();
	if(regfirstname == '' || reglastname == '' || regemail == '' || regphonenumber == '' || regwebsiteurl == '' || regorganization == '' || !regex.test(regemail)){
		return false;
	}
    jQuery('#submit').hide();
    jQuery('.submitted').show();
});
</script>