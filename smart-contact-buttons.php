<?php
/* 
Plugin Name: 	Smart Contact Button
Plugin URI: 	https://profiles.wordpress.org/kituchat/
Description: 	Các nút liên hệ và gọi điện giúp tăng tương tác với khách hàng
Tags: 			call now button, buttons, phone, call, contact, liên hệ, nút liên hệ
Author: 		Kí Tự Chất
Author URI: 	https://kituchat.com
Version: 		1.0
License: 		GPL2
Text Domain:    kituchat
*/
	add_action('admin_menu', 'ktc_contactSetup');
	
	if (!function_exists('ktc_contactSetup')) { 
		function ktc_contactSetup(){
				add_menu_page( 'Smart Contact Buttons', 'Smart Contact Buttons', 'manage_options', 'ktc-contact-button', 'ktc_setting' );
		}
	}

	if (!function_exists('ktc_setting')) { 
		function ktc_setting() {
		?>
		<div class="wrap">
		<h1>Smart Contact Buttons: Setting</h1>

		<form method="post" action="options.php">
			<?php settings_fields( 'plugin_options' ); ?>
			<?php do_settings_sections( 'plugin_options' ); ?>
			<h2>Ẩn các chữ chú thích kế bên nút hay không?</h2>
			<table class="form-table">
				<tr valign="top">
				<th scope="row">Ẩn chữ chú thích kế bên nút</th>
				<td><input type="checkbox" name="hide_text" <?php if(get_option('hide_text') != "" ) echo 'checked'; ?> value="1" /></td>
				</tr>
			</table>
			<hr />
			<h2>Cài đặt nút gọi</h2>
			<table class="form-table">
				<tr valign="top">
				<th scope="row">Số điện thoại</th>
				<td><input type="text" name="settingPhoneNumber" value="<?php echo esc_attr( get_option('settingPhoneNumber') ); ?>" /></td>
				</tr>
				<tr valign="top">
				<th scope="row">Chữ hiện ra trên nút gọi</th>
				<td><input type="text" name="settingTextOfPhoneNumber" value="<?php echo esc_attr( get_option('settingTextOfPhoneNumber') ); ?>" /></td>
				</tr>
			</table>
			<hr />
			<h2>Cài đặt Messenger</h2>
			<table class="form-table">
				<tr valign="top">
				<th scope="row">Page ID</th>
				<td><input type="text" name="settingPageId" value="<?php echo esc_attr( get_option('settingPageId') ); ?>" /></td>
				</tr>
			</table>
			<hr />
			<h2>Cài đặt Zalo</h2>
			<table class="form-table">
				<tr valign="top">
				<th scope="row">Số điện thoại Zalo</th>
				<td><input type="text" name="settingZaloNumber" value="<?php echo esc_attr( get_option('settingZaloNumber') ); ?>" /></td>
				</tr>
			</table>
			<hr />
			<h2>Cài đặt Google Maps</h2>
			<table class="form-table">
				<tr valign="top">
				<th scope="row">Link Google Map</th>
				<td><input type="text" name="settingGooglemap" value="<?php echo esc_attr( get_option('settingGooglemap') ); ?>" /></td>
				</tr>
			</table>
			
			<?php submit_button(); ?>

		</form>
		</div>
		<?php } 
	}

	add_action('admin_init', 'mevivu_plugin_admin_init');
	if (!function_exists('mevivu_plugin_admin_init')) { 
		function mevivu_plugin_admin_init(){
			register_setting( 'plugin_options', 'settingPhoneNumber', 'ktc_validate_num' );
			register_setting( 'plugin_options', 'settingTextOfPhoneNumber');
			register_setting( 'plugin_options', 'settingPageId');
			register_setting( 'plugin_options', 'settingGooglemap');
			register_setting( 'plugin_options', 'hide_text');
			register_setting( 'plugin_options', 'settingZaloNumber', 'ktc_validate_num' );
		}
	}
	if (!function_exists('ktc_validate_num')) { 
		function ktc_validate_num($input) {
			if( !preg_match( '/[^a-zA-Z]/', $input ) ){ 
				add_settings_error(
					'plugin_options',
					esc_attr( 'plugin_options' ), 
					__( 'Number must be a positive integer', 'wordpress' ),
					'error'
				);
				$input = get_option( 'plugin_options' );
			}
		
			return $input;
			
		}
	}
	if (!function_exists('ktc_validate_text')) { 
		// validate our options
		function ktc_validate_text($input) {
			if( !preg_match( '/[^0-9]/', $input ) ){ 
				add_settings_error(
					'plugin_options',
					esc_attr( 'plugin_options' ),
					__( 'Number must be a positive integer', 'wordpress' ),
					'error'
				);
				$input = get_option( 'plugin_options' );
			}
		
			return $input;
			
		}
	}

	add_action('template_redirect', 'showFrontend'); 
	if (!function_exists('showFrontend')) { 
		function showFrontend(){
			wp_register_style( 'callButton',  plugin_dir_url( __FILE__ ) . 'css/call.css' );
			wp_enqueue_style( 'callButton' );
			add_action('wp_footer', 'showFooterContent');
		}
	}
	if (!function_exists('showFooterContent')) { 
		function showFooterContent() {
			if(get_option('settingPhoneNumber') != "") {
				echo '<div class="smart-contact-buttons">
					<div onclick="window.location.href= \'tel:'.esc_attr( get_option('settingPhoneNumber') ).'\'" class="scb-hotline">
					<div class="scb-hotline-ring">
					<div class="scb-hotline-ring-circle"></div>
					<div class="scb-hotline-ring-circle-fill"></div>
					<div class="scb-hotline-ring-img-circle">
					<a href="tel:'.esc_attr( get_option('settingPhoneNumber') ).'" class="pps-btn-img">
						<img src="'.plugin_dir_url( __FILE__ ) .'phone.png" alt="Gọi điện thoại" width="50">
					</a>
					</div>
				</div>
				<a href="tel:'.esc_attr( get_option('settingPhoneNumber') ).'">
				<div class="hotline-bar">
						<a href="tel:'.esc_attr( get_option('settingPhoneNumber') ).'">
						<span class="text-hotline">'.esc_attr( get_option('settingTextOfPhoneNumber') ).'</span>
						</a>
				</div>
				</a>
			</div>
			</div>';
			}
			
		}
	}

	add_action('template_redirect', 'showButtons'); 
	if (!function_exists('showButtons')) { 
		function showButtons(){
			wp_register_script( 'callScript', plugin_dir_url( __FILE__ ) . 'main.js','','1.1', true );
			wp_enqueue_script( 'callScript' );
				
			wp_register_style( 'floatingbutton',  plugin_dir_url( __FILE__ ) . 'css/style.css' );
			wp_enqueue_style( 'floatingbutton' );
			
			add_action('wp_footer', 'call_buttons');
		}
	}
	if (!function_exists('call_buttons')) {
		function call_buttons() {
			
		if(get_option('hide_text') != ""){
			echo '<style>.scb-right.inner-fabs.show .fab::before {display: none;} </style>';
		}  	
			
			echo '<div class="scb-right">
		<div class="inner-fabs">';
		if(get_option('settingPageId') != "") {
			echo '<a target="blank" href="https://m.me/'.esc_attr( get_option('settingPageId') ).'" class="fab roundCool" id="activity-fab" data-tooltip="Nhắn tin Messenger">
			<img class="inner-fab-icon"  src="'.plugin_dir_url( __FILE__ ) .'messenger.png" alt="icons8-exercise-96" border="0">
		  </a>';
		}
		if(get_option('settingGooglemap') != ""){
			echo '<a target="blank" href="'.wp_specialchars_decode( get_option('settingGooglemap') ).'" class="fab roundCool" id="challenges-fab" data-tooltip="Chỉ đường bản đồ">
			<img class="inner-fab-icon" src="'.plugin_dir_url( __FILE__ ) .'google-maps.png" alt="challenges-icon" border="0">
		  </a>';
		}
		if(get_option('settingZaloNumber') != ""){
			echo '<a target="blank" href="https://zalo.me/'.esc_attr( get_option('settingZaloNumber') ).'" class="fab roundCool" id="chat-fab" data-tooltip="Nhắn tin Zalo">
			<img class="inner-fab-icon" src="'.plugin_dir_url( __FILE__ ) .'zalo.png" alt="chat-active-icon" border="0">
		  </a>';
		}  
				  
		  
		  
		echo '</div>
		<div class="fab roundCool call-animation" id="main-fab">
		 <img class="img-circle" src="'.plugin_dir_url( __FILE__ ) .'chat.png" alt="" width="135"/>
		</div>
		</div>';
		}
	
	}
	
	
	