<?php

/*
 Plugin Name: WP Property Manager
 Plugin URI: http://www.jaybuddy.com/
 Description: WP Property Manager is a plugin that adds functionality for Property Management websites.
 Version: 0.8
 Author: Jay Pedersen
 Author URI: http://www.jaybuddy.com/
 */


class wpPropertyManager {

	var $optionsPage = 'wppm-options';
	var $extra_fields = array(
		'address'   		=> '',
		'city'				=> '',
		'state'				=> '',
		'zip'				=> '',
		'community'			=> '',
		'code'				=> '',
		'unit_type'			=> '',
		'rent'				=> '',
		'deposit'			=> '',
		'agreement_type'	=> '',
		'date_available'	=> '',
		'smoking'			=> '',
		'tagline'			=> '',
		'description'		=> '',
		'bedrooms'			=> '',
		'bathrooms'			=> '',
		'sqft'				=> '',
		'window_coverings'	=> '',
		'floor_coverings'	=> '',
		'countertops'		=> '',
		'heating_type'		=> '',
		'ac_type'			=> '',
		'parking'			=> '',
		'pet_policy'		=> '',
		'fireplace'			=> '',
		'washerdryer'		=> '',
		'patio'				=> '',
		'balcony'			=> '',
		'pool'				=> '',
		'spa'				=> '',
		'walkin_closets'	=> '',
		'custom_paint'		=> '',
		'water'				=> '',
		'sewer'				=> '',
		'trash'				=> '',
		'gas'				=> '',
		'electric'			=> '',
		'phone'				=> '',
		'cable'				=> '',
		'internet'			=> '',
		'other_utilities'	=> '',
		'lat'				=> '',
		'lon'				=> '',
		'sv_rotation'		=> '',
		'sv_pitch'			=> '', 
		'sv_zoom'			=> '',
		'ui_show_gm'		=> '',
		'ui_show_gsv'		=> '',
		'photo-gal'			=> '',
		'status'			=> '',
		'last_avail_date'	=> ''
	);

	function __construct() {
		add_action( 'admin_menu', array( $this, 'adminPage' ) );
		add_action( 'init', array( $this, 'initPostType' ) );
		add_action( 'init', array( $this, 'add_image_sizes' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_unit_meta_boxes' ) );
		add_action( 'init', array( $this, 'load_wppm_scripts') );
		add_action( 'init', array( $this, 'load_wppm_stylesheet' ) );
		add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

		add_filter( 'manage_edit-units_columns', array( $this, 'edit_units_columns' ) );
		add_filter( 'manage_edit-units_sortable_columns', array( $this, 'units_sortable_columns' ) );

		add_action( 'manage_units_posts_custom_column', array( $this, 'manage_units_columns' ) );
		add_action( 'load-edit.php', array( $this, 'edit_units_load' ) );	

		add_filter( 'template_include', array( $this, 'load_templates' ) );
	}

	function load_wppm_scripts() {
		wp_enqueue_script("jquery");
		wp_enqueue_script( 'google-maps-api', 'http://maps.google.com/maps/api/js?sensor=false', array(), '3', false );
		wp_enqueue_script( 'bs-validate-js', plugin_dir_url( __FILE__ ).'assets/js/min/bootstrapValidator.min.js', array(), '3', false );
	}

	function load_wppm_stylesheet() {
		wp_enqueue_style( 'wppm-styes', plugin_dir_url( __FILE__ ).'wppm.css', array(), '1');
		wp_enqueue_style( 'fa-styes', plugin_dir_url( __FILE__ ).'assets/font-awesome/css/font-awesome.min.css', array(), '1');
		wp_enqueue_style( 'bs-validate-css', plugin_dir_url( __FILE__ ).'assets/css/min/bootstrapValidator.min.css', array(), '.52');
	}
	
	function add_image_sizes() {
		add_image_size( 'tiles-featured', 225, 225, true );
		add_image_size( 'mini-tiles', 80, 55, true );
	}

	function load_templates( $template ){

        if ( is_singular( 'units' ) ) {
        	if ( $overridden_template = locate_template( 'single-units.php' ) ) {
			   $template = $overridden_template;
			 } else {
			   $template = plugin_dir_path(__file__) . 'templates/single-units.php';
			 }
			 
        } elseif ( is_archive( 'units' ) ) {
        	if ( $overridden_template = locate_template( 'archive-units.php' ) ) {
			   $template = $overridden_template;
			 } else {
			   $template = plugin_dir_path(__file__) . 'templates/archive-units.php';
			 }
        }
        load_template( $template );
	}

	function adminPage() {
		add_options_page( 'WP Property Manager', 'Property Manager', 'manage_options', 'wppm-options', array( $this, 'loadOptionsPage' ) );
	}

	function loadOptionsPage() {
		require_once ('wppm-options.php');
	}

	function initPostType() {

		$labels = array(
			'name'                => _x( 'Unit', 'Post Type General Name', 'wppm' ),
			'singular_name'       => _x( 'Unit', 'Post Type Singular Name', 'wppm' ),
			'menu_name'           => __( 'Units', 'wppm' ),
			'parent_item_colon'   => __( 'Parent Item:', 'wppm' ),
			'all_items'           => __( 'All Units', 'wppm' ),
			'view_item'           => __( 'View Unit', 'wppm' ),
			'add_new_item'        => __( 'Add New Unit', 'wppm' ),
			'add_new'             => __( 'Add New', 'wppm' ),
			'edit_item'           => __( 'Edit Unit', 'wppm' ),
			'update_item'         => __( 'Update Unit', 'wppm' ),
			'search_items'        => __( 'Search Units', 'wppm' ),
			'not_found'           => __( 'Not found', 'wppm' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'wppm' ),
		);
		$args = array(
			'label'               => __( 'units', 'wppm' ),
			'description'         => __( 'Units', 'wppm' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			//'menu_icon'           => '',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'units', $args );
		flush_rewrite_rules();
	}
	
	function edit_units_columns( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Address' ),
			'rent' => __( 'Rent' ),
			'status' => __( 'Status' ),
			'date' => __( 'Date' )
		);

		return $columns;
	}

	function manage_units_columns( $column ) {
		global $post;

		switch( $column ) {

			case 'rent' :

				$rent = get_post_meta( $post->ID, 'rent', true );
				if (!empty($rent)) {
					printf( __( '$%s' ), $rent );
				} else {
					printf( '<i class="fa fa-exclamation-circle"></i>' );
				}
				

				break;

			case 'status' :

				$status = get_post_meta( $post->ID, 'status', true );
				printf( $status );

				break;

			default :
				break;
		}
	}

	function units_sortable_columns( $columns ) {

		$columns['status'] = 'status';

		return $columns;
	}

	function edit_units_load() {
		add_filter( 'request', array( $this, 'sort_units' ) );
	}

	function sort_units( $vars ) {

		if ( isset( $vars['post_type'] ) && 'units' == $vars['post_type'] ) {

			if ( isset( $vars['orderby'] ) && 'status' == $vars['orderby'] ) {

				$vars = array_merge(
					$vars,
					array(
						'meta_key' => 'status',
						'orderby' => 'meta_value'
					)
				);
			}
		}

		return $vars;
	}

	function add_meta_boxes() {
		if( function_exists( 'add_meta_box' ) ) {
			add_meta_box( 'unit-info', __('Unit Information'), array( $this, 'display_unitinfo_meta'), 'units', 'normal', 'high');
			add_meta_box( 'maps', __('Maps'), array( $this, 'display_maps_meta'), 'units', 'normal', 'high');
			add_meta_box( 'gallery', __('Gallery'), array( $this, 'display_gallery_meta'), 'units', 'normal', 'low');
			add_meta_box( 'unit-status', __('Availability'), array( $this, 'display_status_meta'), 'units', 'side', 'high');
		}
	}
	
	function display_unitinfo_meta( $post ) {
		include('metaboxes/unit-info.php');
	}

	function display_maps_meta( $post ) {
		include('metaboxes/maps.php');
	}

	function display_gallery_meta( $post ) {
		include('metaboxes/gallery.php');
	}

	function display_status_meta( $post ) {
		include('metaboxes/unit-status.php');
	}

	function save_unit_meta_boxes( $post_id ) {
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		//Check to make sure we are on a "Units" page so we dont geocode on every page save//
		if (isset($_POST['unitsMetaBoxUpdate']) && $_POST['unitsMetaBoxUpdate'] == 'yesPlease') {

			$post_data = array_merge($this->extra_fields, $_POST);
			
			foreach( $this->extra_fields as $key => $value ) {
				update_post_meta( $post_id, $key, $post_data[$key]);
			}
			
			$address = $post_data['address']." ".$post_data['city'].", ".$post_data['state']." ".$post_data['zip'];

			$geoCode = $this->geoCode( $address, $post_data );

			update_post_meta( $post_id, 'lat', $geoCode['lat']);
			update_post_meta( $post_id, 'lon', $geoCode['lon']);

			//Lets see if user is making the unit available for rent.//
			//1) Check previous status
			$prevStatus = $_POST['previousStatus'];
			//2) Check if posted value is avail && previousStatus is rented

			if ($prevStatus == 'Rented' && $_POST['status'] == 'For Rent') {
				update_post_meta( $post_id, 'last_avail_date', strtotime('now'));
			} else {
				update_post_meta( $post_id, 'last_avail_date', $_POST['last_avail_date']);
			}
			
		}
	}

	function geoCode( $address, $post_data ) {

		if ( !is_null($address) ) {

			if ( strtolower( str_replace( " ", "", $post_data['o_address']) ) == strtolower( str_replace( " ", "", $address) ) ) {
				//The Address has not changed, just send bak the orig. lat and lon.//
				//die(print_r($post_data['lat'].' '.$post_data['lon']))
				return array('lat' => $post_data['lat'], 'lon' => $post_data['lon']);
			} else {
				//The address changed, so lets re-geocoge it and send back new coords.//
				$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false';
				$data = wp_remote_get( $url );
				
				$j = json_decode($data['body']);
				return array( 'lat' => $j->results[0]->geometry->location->lat, 'lon' => $j->results[0]->geometry->location->lng);
			}
		
		} else {
			return false;
		}
	}

	function updated_messages( $messages ) {
		global $post_ID, $post;
		
		$messages['units'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __('Unit updated.'),
			2 => __('Unit updated.'),
			3 => __('Custom field deleted.'),
			4 => __('Unit updated.'),
			5 => isset($_GET['revision']) ? sprintf( __('Unit restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __('Unit published.'),
			7 => __('Unit saved.'),
			8 => __('Unit submitted. <a target="_blank" href="%s">Preview Unit</a>'),
			9 => sprintf( __('Unit scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
			10 => __('Unit draft updated.')
		);
		
		return $messages;
	}

	

}

$wpPropertyManager = new wpPropertyManager();


?>