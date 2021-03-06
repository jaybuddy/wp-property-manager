<?php
/**
*
*	wppmCore
*	Core class that handles all of the Custom Post Type generation
*	and actions.
*	
*	@package WP Property Manager
* 	@author Jay Pedersen
*	@since 0.8
*
*	Requires: wpPropertyManager 
*
*/

class wppmCore extends wpPropertyManager {

	/**
	* List of extra fields we are saving as post meta in the DB
	*
	* @var array
	*/
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
		'last_avail_date'	=> '',
		'application'		=> '',
	);

	function __construct() {
		
		// Initialize the units post type
		add_action( 'init', array( $this, 'init_post_type' ) );

		// Setup the custom columns for the CPT
		add_filter( 'manage_edit-units_columns', array( $this, 'edit_units_columns' ) );
		add_action( 'manage_units_posts_custom_column', array( $this, 'manage_units_columns' ) );
		add_filter( 'manage_edit-units_sortable_columns', array( $this, 'units_sortable_columns' ) );
		add_filter( 'request', array( $this, 'sort_units' ) );

		// Setup metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_unit_meta_boxes' ) );

		// Add custom messages
		add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

		// Load default templates
		add_filter( 'template_include', array( $this, 'load_templates' ) );

		// Load frontend styles/scripts
		add_action( 'init', array( $this, 'load_scripts') );
		add_action( 'init', array( $this, 'load_styles' ) );

		// Load backend styles/scripts
		add_action('admin_print_scripts', array( $this, 'load_admin_scripts' ) );
		add_action('admin_print_styles', array( $this, 'load_admin_styles' ) );
	}

	/**
	* Initializes the "Units" custom post type in WP. We also add the two additional image sizes for our templates.
	* 
	* @since 0.8
	* @access public
	* 
	*/
	function init_post_type() {

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
		
		add_image_size( 'tiles-featured', 225, 225, true );
		add_image_size( 'mini-tiles', 80, 55, true );
	}

	/**
	* Changes the columns to be displayed for the Units post type. 
	* 
	* @since 0.8
	* @access public
	* @param array $column 	The column name
	* 
	*/
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
	
	/**
	* Takes the passed column name and grabs the column value for each unit.
	* 
	* @since 0.8
	* @access public
	* @param string $column 	The column name
	* 
	*/
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
	
	/**
	* Set which columns are sortable
	* 
	* @since 0.8
	* @access public
	* @param array $column 	Array of column names
	* @return array $columns 	Array of column names that are sortable
	* 
	*/
	function units_sortable_columns( $columns ) {

		$columns['status'] = 'status';

		return $columns;
	}

	/**
	* Set which columns are sortable
	* 
	* @since 0.8
	* @access public
	* @param array $vars 	Array of column names
	* @return array $vars 	Array of column names that are sortable
	* 
	*/
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

	/**
	* Add meta boxes for the add/edit unit form. 
	* 
	* @since 0.8
	* @access public
	* 
	*/
	function add_meta_boxes() {
		if( function_exists( 'add_meta_box' ) ) {
			add_meta_box( 'unit-info', __('Unit Information'), array( $this, '_display_unitinfo_meta'), 'units', 'normal', 'high');
			add_meta_box( 'maps', __('Maps'), array( $this, '_display_maps_meta'), 'units', 'normal', 'high');
			add_meta_box( 'gallery', __('Gallery'), array( $this, '_display_gallery_meta'), 'units', 'normal', 'low');
			add_meta_box( 'unit-status', __('Availability'), array( $this, '_display_status_meta'), 'units', 'side', 'high');
		}
	}
	
	/**
	* Includes the unit info metabox code. 
	* 
	* @since 0.8
	* @access private
	* @param array $post 	Post data for the form inputs
	* 
	*/
	function _display_unitinfo_meta( $post ) {
		include( 'includes/metaboxes/unit-info.php' );
	}

	/**
	* Includes the unit maps metabox code. 
	* 
	* @since 0.8
	* @access private
	* @param array $post 	Post data for the form inputs
	* 
	*/
	function _display_maps_meta( $post ) {
		include( 'includes/metaboxes/maps.php' );
	}

	/**
	* Includes the unit gallery metabox code. 
	* 
	* @since 0.8
	* @access private
	* @param array $post 	Post data for the form inputs
	* 
	*/
	function _display_gallery_meta( $post ) {
		include( 'includes/metaboxes/gallery.php' );
	}

	/**
	* Includes the unit status metabox code. 
	* 
	* @since 0.8
	* @access private
	* @param array $post 	Post data for the form inputs
	* 
	*/
	function _display_status_meta( $post ) {
		include( 'includes/metaboxes/unit-status.php' );
	}

	/**
	* Handles saving data from unit metaboxes.
	* 
	* @since 0.8
	* @access public
	* @param int $post_id 	ID of the post that the post meta belongs to.
	* 
	*/
	function save_unit_meta_boxes( $post_id ) {
		/*
		* If we arent doing an autosave we merge all the extra fields with their corresponding post data.
		* Next we check to make sure we are saving a unit vs a post or page so we dont make the geocoding 
		* request when we dont need to. Once we geocode the address, we save the long/lat in the DB. Next we 
		* check to see if we are chaging the property from rented to available and if so, we set that date time
		* so we can calculate the listing age later on.
		*/
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		//Check to make sure we are on a "Units" page so we dont geocode on every page save//
		if (isset($_POST['unitsMetaBoxUpdate']) && $_POST['unitsMetaBoxUpdate'] == 'yesPlease') {

			$post_data = array_merge($this->extra_fields, $_POST);
			
			foreach( $this->extra_fields as $key => $value ) {
				update_post_meta( $post_id, $key, $post_data[$key]);
			}
			
			$address = $post_data['address']." ".$post_data['city'].", ".$post_data['state']." ".$post_data['zip'];

			$geoCode = $this->_geo_code( $address, $post_data );

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

	/**
	* Handles the geocoding of the address when the property is saved. However, prior to doing so, 
	* it checks to see if the address has changed. If it hasnt changed, there is no need to re-geocode. 
	* 
	* @since 0.8
	* @access private
	* @param string $address 	Current address prior to saving.
	* @param array $post_data 	Array of post data from the form.
	* @return array 	the lng and lat coordinates for the address. 
	*
	*/
	function _geo_code( $address, $post_data ) {

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

	/** 
	* Sets the update messages for the units custom post type.
	* 
	* @since 0.8
	* @access public
	* @param array $messages 	Array of messages.
	* @return array $messages	The updated messages.
	*
	*/
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

	/**
	* Check if the user has overwritten the default templates and loads them is they have. If not, we default to the
	* templates included in the plugin.
	* 
	* @since 0.8
	* @access public
	* @param string $template 	Name of template.
	* @return string $template	The name of the template file we are loading
	* 
	*/
	function load_templates( $template ) {

        if ( is_singular( 'units' ) ) {
        	if ( $overridden_template = locate_template( 'single-units.php' ) ) {
			   $template = $overridden_template;
			 } else {
			   $template = plugin_dir_path( __file__ ) . 'templates/single-units.php';
			 }
        } elseif ( is_archive( 'units' ) ) {
        	if ( $overridden_template = locate_template( 'archive-units.php' ) ) {
			   $template = $overridden_template;
			 } else {
			   $template = plugin_dir_path( __file__ ) . 'templates/archive-units.php';
			 }
        }
        load_template( $template );
	}

	/**
	* Properly enqueues JS files into WP.
	* 
	* @since 0.8
	* @access public
	* 
	*/
	function load_admin_scripts() {

		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('jquery');

		wp_register_script( 'wppm-admin-gallery', plugin_dir_url( __FILE__ ) . 'assets/js/admin-gallery.js', array( 'jquery', 'media-upload', 'thickbox' ) );
		wp_enqueue_script( 'wppm-admin-gallery' );
		
	}

	/**
	* Properly enqueues stylesheets files into WP.
	* 
	* @since 0.8
	* @access public
	* 
	*/
	function load_admin_styles() {

		wp_enqueue_style('thickbox');
		wp_enqueue_style( 'wppm-admin-styles', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), '0.8' );
		
	}

	/**
	* Properly enqueues Javascript files into WP.
	*	1) jquery
	*	2) Bootstrap
	*	3) Google Maps API
	*	4) Bootstrap Validator
	* 
	* @since 0.8
	* @access public
	* 
	*/
	function load_scripts() {
		global $redux_options;
		wp_enqueue_script("jquery");

		$bs = $redux_options['opt-bootstrap'];

		if ( $bs == 1 ) {
			//Load from CDN//
			wp_enqueue_script( 'bootstrap-script', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js', array( 'jquery' ), '3.3.2', true );
		} elseif ( $bs == 2 ) {
			//Load from plugin//
			wp_enqueue_script( 'bootstrap-script', dirname( __FILE__ ).'/assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '3.3.2', true );
		} elseif ( $bs == 3 ) {
			//load from nowhere... They have it loaded elsewhere... lets check to be sure though
		} else {
			//load from plugin. (fallback)//
			wp_enqueue_script( 'bootstrap-script', dirname( __FILE__ ).'/assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '3.3.2', true );
		}
		
		wp_enqueue_script( 'google-maps-api', 'http://maps.google.com/maps/api/js?sensor=false', array(), '3', false );
	}

	/**
	* Properly enqueues stylesheets files into WP.
	*	1) Bootsctap	
	*	2) WP Property Manger Styles
	*	3) Font Awesome
	*	4) Bootstrap Validator
	* 
	* @since 0.8
	* @access public
	* 
	*/
	function load_styles() {
		global $redux_options;

		$bs = $redux_options['opt-bootstrap'];

		if ( $bs == 1 ) {
			//Load from CDN//
			wp_enqueue_style( 'bootstrap-script', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css', array(), '3.3.2' );
		} elseif ( $bs == 2 ) {
			//Load from plugin//
			wp_enqueue_style( 'bootstrap-script', dirname( __FILE__ ).'/assets/bootstrap/css/bootstrap.min.css', array(), '3.3.2' );
		} elseif ( $bs == 3 ) {
			//load from nowhere... They have it loaded elsewhere... lets check to be sure though
		} else {
			//load from plugin. (fallback)//
			wp_enqueue_style( 'bootstrap-script', dirname( __FILE__ ).'/assets/bootstrap/css/bootstrap.min.css', array(), '3.3.2' );
		}

		wp_enqueue_style( 'wppm-styes', plugin_dir_url( __FILE__ ).'assets/css/wppm.css', array(), '1');
		wp_enqueue_style( 'fa-styes', plugin_dir_url( __FILE__ ).'assets/font-awesome/css/font-awesome.min.css', array(), '1');
		wp_enqueue_style( 'bs-validate-css', plugin_dir_url( __FILE__ ).'assets/css/min/bootstrapValidator.min.css', array(), '.52');
	}
	

}