<?php
	
	/**
	*
	*	setup_single_units_data
	*	Takes the ID of the unit (post) and gets all the unit's meta fields as wall as sets up an 
	*	array of all the images for the unit. See WPPM codex for details on the structure of the response. It also 
	*	handles caching of the data.
	*	@param id int id of the unit
	*	@since 0.8
	*
	*/
	function setup_single_units_data( $id ) {

		$rental = get_post_custom( $id );
		
		//Now lets get the Featured image and append it to the rental array.
		$feat_img = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
		if ( !empty( $feat_img ) ) {
          	$rental['featured_image'] = $feat_img[0];
        } else {
          	$rental['featured_image'] = get_bloginfo("url").'/wp-content/plugins/wp-property-manager/assets/images/blank-photo.png'; 
        }

		//Next lets get all the images in the gallery. Both Full and Thumb sizes.
		$phototext = $rental['photo-gal'][0];
		
		if (empty($phototext)) {
          	echo "Were sorry, we do not have any photos for this unit yet. Check back soon!";
        } else {
          	
          	$all_images = explode(',', $rental['photo-gal'][0]);

          	//Prep full-size and thumbnails//
          	$rental['gallery_images'] = array();
          	$n = 0;

          	foreach ($all_images as $img) {
            	$rental['gallery_images'][$n] = array(
                  'full' => wp_get_attachment_image_src( $img, 'full' ),
                  'thumb' => wp_get_attachment_image_src( $img, 'mini-tiles' )
                );
            	$n++; 
          	}
        }

        return $rental;
	}


	/**
	*
	*	setup_archive_units_data
	*	Grabs all the units that are for rent and orders them newest available.
	*	Returns array of data for rendering in the view.
	*	@since 0.8
	*
	*/
	function setup_archive_units_data() {

		$args = array(
			'post_type' => 'units',
			'numberposts' => -1,
			'meta_query'  => array(
		       	array(
		            'key'       => 'status',
		            'value'     => 'For Rent',  
		        )
		    ),
		    'orderby'  => 'meta_value_num',        
		    'meta_key' => 'last_avail_date',
		    'order'    => 'DESC',
			
		);
		$posts = get_posts($args);

		$post_num = 0;
		foreach ($posts as $post) {
			
			$meta = get_post_custom( $post->ID );

			$feat_img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'tiles-featured' );
			if ( !empty( $feat_img ) ) {
				$thumb = $feat_img[0];
			} else {
				$thumb = get_bloginfo("url").'/wp-content/plugins/wp-property-manager/assets/images/blank-photo.png'; 
			}

			$mini_feat_img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'mini-tiles' );
			if ( !empty( $feat_img ) ) {
				$mini_thumb = $mini_feat_img[0];
			} else {
				$mini_thumb = get_bloginfo("url").'/wp-content/plugins/wp-property-manager/assets/images/blank-photo.png'; 
			}

			$posts[$post_num]->meta = $meta;
			$posts[$post_num]->meta['permalink'] = get_the_permalink($post->ID);
			$posts[$post_num]->meta['thumb'] = $thumb;
			$posts[$post_num]->meta['mini_thumb'] = $mini_thumb;
			$post_num++;
		}
		return $posts;
	}


	/**
	*
	*	the_application
	*	If downloading applications is enabled, this function determines whether the specific unit has a custom 
	*	application or if we should just use the fallback application that is set on the options page.
	*	@since 0.8
	*
	*/
	function the_application( $rental ) {
		global $redux_options;
		if ( $redux_options['opt-enable-app-download'] == '1' ) {

			if ( !empty( $rental['application'][0] ) )  {
				return "<a target='_blank' href='".$rental['application'][0]."'><button type='button' class='btn btn-wppm apply-now hidden-xs pull-right'><i class='fa fa-pencil'></i> Apply Now!</button><button type='button' class='btn btn-wppm apply-now hidden-sm hidden-md hidden-lg pull-right'><i class='fa fa-pencil'></i></button></a>";
			} else {
				return "<a target='_blank' href='".$redux_options['opt-default-application']['url']."'><button type='button' class='btn btn-wppm apply-now hidden-xs pull-right'><i class='fa fa-pencil'></i> Apply Now!</button><button type='button' class='btn btn-wppm apply-now hidden-sm hidden-md hidden-lg pull-right'><i class='fa fa-pencil'></i></button></a>";
			}
		} 	
	}


	/**
	*
	*	the_email_a_friend
	*	If Email A Friend is enabled, this frunction will display the button.
	*	@since 0.8
	*
	*/
	function the_email_a_friend() {
		global $redux_options;
		if ( $redux_options['opt-enable-email-a-friend'] == '1' ) {
			return "<button type='button' class='btn btn-wppm hidden-xs pull-right' data-toggle='modal' data-target='#shareModal'><i class='fa fa-share'></i> Share</button><button type='button' class='btn btn-wppm hidden-sm hidden-md hidden-lg pull-right' data-toggle='modal' data-target='#shareModal'><i class='fa fa-share'></i></button>";
		}
	}


	/**
	*
	*	the_google_map
	*	This function determines whether or not to display the google map on the single template. If so, it will
	*	return the map otherwise it returns an empty string.
	*	@param $perm string A Yes/No string indicating to show the map as defined on the edit unit page. 
	*	@param $rental array An array of rental data used to build the view more link.
	*	@since 0.8
	*
	*/
	function the_google_map( $perm, $rental ) {
		global $redux_options;

		// Create the "View Larger" link
		$link = "https://www.google.com/maps/place/";
		$link .= str_replace( ' ', '+', $rental['address'][0]) . '+';
		$link .= str_replace( ' ', '+', $rental['city'][0]) . '+';
		$link .= str_replace( ' ', '+', $rental['state'][0]) . '+';
		$link .= str_replace( ' ', '+', $rental['zip'][0]);

		// Permissions checking
		if ( $perm == 'No' && $redux_options['enable-google-map'] == 0 ) {
			//global no, local no. return nothing.//
			return '';
		} elseif ( $redux_options['enable-google-map'] == 1 && $perm == 'No' ) {
			//global yes, local no. return nothing.//
			return '';
		} elseif ( $redux_options['enable-google-map'] == 0 && $perm == 'Yes' ) {
			//global no, local yes. return map.//
			return "<section class='map google-map'><div class='panel panel-default'><div class='panel-heading'>Map</div><div class='panel-body'><div id='frontend-map'></div><a target='_BLANK' class='btn btn-primary btn-xs btn-enhance' href='".$link."'><i class='fa fa-globe'></i> View larger</a></div></div></section><script type='text/javascript' src='".get_bloginfo('url')."/wp-content/plugins/wp-property-manager/assets/js/min/gm-min.js'></script>";

		} elseif ( $redux_options['enable-google-map'] == 1 && $perm == 'Yes' ) {
			//global yes, local yes. return map//
			return "<section class='map google-map'><div class='panel panel-default'><div class='panel-heading'>Map</div><div class='panel-body'><div id='frontend-map'></div><a target='_BLANK' class='btn btn-primary btn-xs btn-enhance' href='".$link."'><i class='fa fa-globe'></i> View larger</a></div></div></section><script type='text/javascript' src='".get_bloginfo('url')."/wp-content/plugins/wp-property-manager/assets/js/min/gm-min.js'></script>";
		}        
	}


	/**
	*
	*	the_google_sv
	*	This function determines whether or not to display the google street view on the single template. If so, it will
	*	return the SV otherwise it returns an empty string.
	*	@param $perm string A Yes/No string indicating to show the map as defined on the edit unit page. 
	*	@param $rental array An array of rental data used to build the view more link.
	*	@since 0.8
	*
	*/
	function the_google_sv( $perm, $rental ) {
		global $redux_options;

		// Build the "View Larger" link. 
		// cbll = lat, lon
		// cbp = arrangement (11 or 12), rotation, tilt, zoom, pitch
		$link = "http://maps.google.com/maps?q=&layer=c&cbll=";
		$link .= $rental['lat'][0] . ',' . $rental['lon'][0];
		$link .= "&cbp=";
		$link .= "12," . $rental['sv_rotation'][0] . ',0,' . $rental['sv_zoom'][0] . ',' . $rental['sv_pitch'][0];
		
		if ( $perm == 'No' && $redux_options['enable-sv'] == 0 ) {
			//global no, local no. return nothing.//
			return '';
		} elseif ( $redux_options['enable-sv'] == 1 && $perm == 'No' ) {
			//global yes, local no. return nothing.//
			return '';
		} elseif ( $redux_options['enable-sv'] == 0 && $perm == 'Yes' ) {
			//global no, local yes. return map.//
			return "<section class='map sv-map'><div class='panel panel-default'><div class='panel-heading'>Street View</div><div class='panel-body'><div id='frontend-street-view'></div><a target='_BLANK' class='btn btn-primary btn-xs btn-enhance' href='".$link."'><i class='fa fa-globe'></i> View larger</a></div></div></section><script type='text/javascript' src='".get_bloginfo('url')."/wp-content/plugins/wp-property-manager/assets/js/min/sv-min.js'></script>";

		} elseif ( $redux_options['enable-sv'] == 1 && $perm == 'Yes' ) {
			//global yes, local yes. return map//
			return "<section class='map sv-map'><div class='panel panel-default'><div class='panel-heading'>Street View</div><div class='panel-body'><div id='frontend-street-view'></div><a target='_BLANK' class='btn btn-primary btn-xs btn-enhance' href='".$link."'><i class='fa fa-globe'></i> View larger</a></div></div></section><script type='text/javascript' src='".get_bloginfo('url')."/wp-content/plugins/wp-property-manager/assets/js/min/sv-min.js'></script>";
		}   
					
	}


	/**
	*
	*	the_listing_age
	*	This function determines whether or not to display the listing age on the footer of the listing card on the 
	*	archive page.
	*	@param $last_avail int timestamp when the unit was last marked available
	*	@since 0.8
	*/
	function the_listing_age( $last_available_date ) {
		global $redux_options;

		if ( $redux_options['enable-listing-age'] == '1' ) {
			$listing_age = _listing_age( $last_available_date );
			return "<div class='freshness ".$listing_age["class"]."'><i class='fa fa-clock-o'></i> Posted ".$listing_age['text']."</div>";
		}
	}


	/**
	*
	*	_listing_age
	*	This function is used in the Archive/Single templates to determin the age of the listing
	*	as well as return a text string to be displayed on in the toolip on the page. It also determines
	*	the class that is to be applied to the icon.
	*	@param $last_avail int timestamp when the unit was last marked available
	*	@since 0.8
	*
	*/
	function _listing_age( $last_avail ) {

		if ( !empty( $last_avail ) ) {

			$currentTime = strtotime( 'now' );
			$age = $currentTime - $last_avail;

			$days = floor( $age / 86400 );
			$r1 = $age % 86400;

			$hours = floor( $r1 / 3600 );
			$r2 = $r1 % 3600;
			
			$min = floor( $r2 / 60 );

			$string = '';
			$string .= ( $days > 0 ) ? $days.'d ' : '';
			$string .= ( $hours > 0 ) ? $hours.'h ' : '';
			$string .= ( $min > 0 ) ? $min.'m ' : '';
			$string .= ' ago.';

			if ( $days == 0 && $hours == 0 && $min == 0 ) {
				$string = 'Less than 1m ago.';
			}
			
			if ( $age <= 86400 ) {
				//Less than or equal to a day old//
				$class = 'new';
			} else if ( $age > 86400 && $age <= 172800 ) {
				//Between 1 and 2 days old
				$class = 'recent';
			} else if ( $age > 172800 && $age <= 604800 ) {
				//Between 2 and 7 days old
				$class = 'aging';
			} else {
				//Older than 7 days//
				$class = 'old';
			} 

			return array( 'text' => $string, 'class' => $class );

		} else {

			return '';
		}
		
	}


	/*
		include the template hooks
	*/
	include( dirname( __FILE__ ) .'/hooks.php' );
		

?>