<?php
	/**
	*
	*	the_gallery_images
	*	Builds a string of images in the gallery used in the admin area
	*	@param ids string string of image ids
	*	@since 0.8
	*
	*/
	function the_gallery_images( $ids ) {
		
		$image_array = explode( ',', trim( $ids ) );
		
		$class = 'redux-option-image';

		$image_string = "";
		foreach ( $image_array as $image_id ) {
			$image_url = wp_get_attachment_image_src( $image_id, 'thumbnail' );
			
			$image_string .= "<img class='".$class."' src='".$image_url[0]."' />";
		}
		
		return $image_string;
	}
?>