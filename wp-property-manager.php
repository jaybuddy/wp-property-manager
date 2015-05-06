<?php
/**
 * Plugin Name: WP Property Manager
 * Plugin URI: http://www.wp-property-manager.com
 * Description: WP Property Manager is a plugin that adds functionality for Property Management websites.
 * Version: 0.8
 * Author: Jay Pedersen
 * Author URI: http://jaybuddy.me
 * Requires at least: 3.8
 * Tested up to: 4.0
 *
 * Text Domain: wppm
 *
 * @package WP Property Manager
 * @author Jay Pedersen
 */
include( dirname( __FILE__ ).'/admin/admin-init.php' );


class wpPropertyManager {

	var $plugin_name = 'wp-property-manager';

	function __construct() {

		$this->include_classes();
		
		$wppmCore = new wppmCore();
		//$wppmShare = new wppmShare();
	
	}

	function include_classes() {
		include_once( 'modules/wppm-core/wppm-core.php' );
		include_once( 'modules/wppm-share/wppm-share.php' );
	}

}

$wpPropertyManager = new wpPropertyManager();


?>