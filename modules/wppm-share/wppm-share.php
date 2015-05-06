<?php

class wppmShare extends wpPropertyManager {
	
	function __construct() {

		// Load frontend styles/scripts
		add_action( 'init', array( $this, 'load_scripts') );
		add_action( 'init', array( $this, 'load_styles' ) );

		// Utilize admin-ajax to send share requests
		add_action( 'wp_ajax_share_property', array( $this, 'share_property' ) );
		add_action( 'wp_ajax_nopriv_share_property', array( $this, 'share_property' ) );
	}

	/**
	* Properly enqueues Javascript files into WP.
	* 
	* @since 0.8
	* @access public
	* 
	*/
	function load_scripts() {
		
		wp_enqueue_script( 'wppm-email', plugin_dir_url( __FILE__ ).'assets/js/min/email-to-friend-min.js', array(), '0.8', false );
		wp_localize_script( 'wppm-email', 'share_property', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	* Properly enqueues stylesheets files into WP.
	*
	* @since 0.8
	* @access public
	* 
	*/
	function load_styles() {
		
		wp_enqueue_style( 'wppm-email-styes', plugin_dir_url( __FILE__ ).'assets/css/wppm.css', array(), '1');
	
	}

	//Need comment block
	function share_property() {
		global $redux_options;

		$data = $_POST['data'];
		$property_link = '<a href="'.$data['permalink'].'">'.$data['permalink'].'</a>';
		$template = $redux_options['opt-eaf-template'];
		$from_email = $redux_options['opt-eaf-from'];

		if ( !empty( $from_email ) && !empty( $template ) ) {
			// Prep the template //
			$template = str_replace( '{{property-link}}', $property_link, $template );
			$template = str_replace( '{{your-name}}', $data['your_name'], $template );
			$template = str_replace( '{{your-email}}', $data['your_email'], $template );
			$template = str_replace( '{{friends-name}}', $data['friends_name'], $template );
			$template = str_replace( '{{friends-email}}', $data['friends_email'], $template );

			if (isset( $data['sent'] ) && wp_verify_nonce( $data['sent'], 'send_to_friend' ) ) {
	          	if ( !empty( $data['friends_email'] ) && !empty( $data['friends_name'] )&& !empty( $data['your_name'] ) && !empty( $data['your_email'] ) && !empty( $data['permalink'] ) ) {
	          		
	          		$to = $data['friends_email'];
	          		$subject = $redux_options['opt-eaf-subject'];
	          		$message = '<html><body>'.$template.'</body></html>';
					
	          		$headers = 'From: '.$from_email."\r\n";
	          		$headers .= 'Reply-To: '.$from_email."\r\n";
	          		$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	          		mail( $to, $subject, $message, $headers );

	          		$response = array(
	          			'success' => true,
	          			'message' => 'An email has been sent to '.$data['friends_name']
	          		);
	          		echo json_encode( $response );

	          	} else {
	          		$response = array(
	          			'success' => false,
	          			'message' => 'Not all form fields were completed, please try again.'
	          		);
	          		echo json_encode( $response );
	          	}
	          	die();
	     	}

		} else {
			$response = array(
      			'success' => false,
      			'message' => 'There was a configuration problem with the "From" email address. This is likely because it is not set in the admin settings'
      		);
      		echo json_encode( $response );
      		die();
		}

	}
}