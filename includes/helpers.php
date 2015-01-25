<?php
	global $redux_options;

	/*
		setupSingleUnitData takes the ID of the unit (post) and gets all the unit's meta fields as wall as sets up an 
		array of all the images for the unit. See WPPM codex for details on the structure of the response. It also 
		handles caching of the data.
	*/
		function setupSingleUnitData($id) {

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

		function setupArchiveUnitsData() {

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

	/* 
		This function is used in the Archive/Single templates to determin the age of the listing
		as well as return a text string to be displayed on in the toolip on the page. It also determines
		the class that is to be applied to the icon.
	*/
		function listingAge($lastAvail) {

			if (!empty($lastAvail)) {

				$currentTime = strtotime('now');
				$age = $currentTime - $lastAvail;

				$days = floor($age / 86400);
				$r1 = $age % 86400;

				$hours = floor($r1 / 3600);
				$r2 = $r1 % 3600;
				
				$min = floor($r2 / 60);

				$string = '';
				$string .= ($days > 0) ? $days.'d ' : '';
				$string .= ($hours > 0) ? $hours.'h ' : '';
				$string .= ($min > 0) ? $min.'m ' : '';
				$string .= ' old.';

				if ($days == 0 && $hours == 0 && $min == 0) {
					$string = 'Less than 1m ago.';
				}
				
				if ($age <= 86400) {
					//Less than or equal to a day old//
					$class = 'new';
				} else if ($age > 86400 && $age <= 172800) {
					//Between 1 and 2 days old
					$class = 'recent';
				} else if ($age > 172800 && $age <= 604800) {
					//Between 2 and 7 days old
					$class = 'aging';
				} else {
					//Older than 7 days//
					$class = 'old';
				} 

				return array('text' => $string, 'class' => $class);

			} else {

				return '';
			}
			
		}

	/*
		If downloading applications is enabled, this function determins whether the specific unit has a custom 
		application or if we should just use the fallback application that is set on the options page.
	*/
		function the_application( $rental ) {
			global $redux_options;
			if ( $redux_options['opt-enable-app-download'] == '1' ) {

				if ( !empty( $rental['application'][0] ) )  {
					return "<a target='_blank' href='".$rental['application'][0]."'><button type='button' class='btn btn-wppm apply-now'><i class='fa fa-pencil'></i><span class='hidden-xs'> Apply Now!</span></button></a>";
				} else {
					return "<a target='_blank' href='".$redux_options['opt-default-application']['url']."'><button type='button' class='btn btn-wppm apply-now'><i class='fa fa-pencil'></i><span class='hidden-xs'> Apply Now!</span></button></a>";
				}
			} 	
		}

	/*
		If Email A Friend is enabled, this frunction will display the button.
	*/
		function the_email_a_friend() {
			global $redux_options;
			if ( $redux_options['opt-enable-email-a-friend'] == '1' ) {
				return "<button type='button' class='btn btn-wppm' data-toggle='modal' data-target='#shareModal'><i class='fa fa-share'></i><span class='hidden-xs'> Share</span></button>";
			}
		}

	/*
		This function determines whether or not to display the google map on the single template. If so, it will
		return the map otherwise it returns an empty string.
	*/
		function the_google_map( $perm ) {
			global $redux_options;
			
			if ( $perm == 'No' && $redux_options['enable-google-map'] == 0 ) {
				//global no, local no. return nothing.//
				return '';
			} elseif ( $redux_options['enable-google-map'] == 1 && $perm == 'No' ) {
				//global yes, local no. return nothing.//
				return '';
			} elseif ( $redux_options['enable-google-map'] == 0 && $perm == 'Yes' ) {
				//global no, local yes. return map.//
				return "<section class='map google-map'><div class='panel panel-default'><div class='panel-heading'>Map</div><div class='panel-body'><div id='frontend-map'></div><a class='btn btn-primary btn-xs' href=''><i class='fa fa-globe'></i> View larger</a></div></div></section><script type='text/javascript' src='".get_bloginfo('url')."/wp-content/plugins/wp-property-manager/assets/js/min/gm-min.js'></script>";

			} elseif ( $redux_options['enable-google-map'] == 1 && $perm == 'Yes' ) {
				//global yes, local yes. return map//
				return "<section class='map google-map'><div class='panel panel-default'><div class='panel-heading'>Map</div><div class='panel-body'><div id='frontend-map'></div><a class='btn btn-primary btn-xs' href=''><i class='fa fa-globe'></i> View larger</a></div></div></section><script type='text/javascript' src='".get_bloginfo('url')."/wp-content/plugins/wp-property-manager/assets/js/min/gm-min.js'></script>";
			}        
		}

	/*
		This function determines whether or not to display the google street view on the single template. If so, it will
		return the SV otherwise it returns an empty string.
	*/

		function the_google_sv( $perm ) {

			global $redux_options;
			
			if ( $perm == 'No' && $redux_options['enable-sv'] == 0 ) {
				//global no, local no. return nothing.//
				return '';
			} elseif ( $redux_options['enable-sv'] == 1 && $perm == 'No' ) {
				//global yes, local no. return nothing.//
				return '';
			} elseif ( $redux_options['enable-sv'] == 0 && $perm == 'Yes' ) {
				//global no, local yes. return map.//
				return "<section class='map sv-map'><div class='panel panel-default'><div class='panel-heading'>Street View</div><div class='panel-body'><div id='frontend-street-view'></div><a class='btn btn-primary btn-xs' href=''><i class='fa fa-globe'></i> View larger</a></div></div></section><script type='text/javascript' src='".get_bloginfo('url')."/wp-content/plugins/wp-property-manager/assets/js/min/sv-min.js'></script>";

			} elseif ( $redux_options['enable-sv'] == 1 && $perm == 'Yes' ) {
				//global yes, local yes. return map//
				return "<section class='map sv-map'><div class='panel panel-default'><div class='panel-heading'>Street View</div><div class='panel-body'><div id='frontend-street-view'></div><a class='btn btn-primary btn-xs' href=''><i class='fa fa-globe'></i> View larger</a></div></div></section><script type='text/javascript' src='".get_bloginfo('url')."/wp-content/plugins/wp-property-manager/assets/js/min/sv-min.js'></script>";
			}   
						
		}

	/*
		This function determines whether or not to display the listing age on the footer of the listing card on the 
		archive page.
	*/
		function listing_age() {
			global $post;
			global $redux_options;

			if ( $redux_options['enable-listing-age'] == '1' ) {
				$listingAge = listingAge($post->meta['last_avail_date'][0]);
				return "<div class='freshness ".$listingAge["class"]."'><i class='fa fa-clock-o'></i> Posted ".$listingAge['text']."</div>";
			}
		}

	/*
		include the template hooks
	*/
		include( dirname( __FILE__ ) .'/hooks.php' );
		

?>