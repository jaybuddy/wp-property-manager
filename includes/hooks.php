<?php	
	
	/**
	*	These functions are not really hooks. You don't use add_action like you would in a 
	*   traditional WP hook. Rather they load options into certain parts of the page. I might 
	*	change this in future versions to use the tradition hook syntax.
	*/

	/**
	* Loads content into the top section of the archive page. 
	* 
	* @since 0.8
	* @access public
	* @return string $redux_options['hook-archive-top']	The option value defined in the admin.
	* 
	*/
	function hook_archive_top() {
		global $redux_options;
		if ( !empty($redux_options['hook-archive-top']) ) {
			return $redux_options['hook-archive-top'];
		}
	}

	/**
	* Loads content into the lower sidebar section of the archive page. 
	* 
	* @since 0.8
	* @access public
	* @return string $redux_options['hook-archive-sidebar-bottom']	The option value defined in the admin.
	* 
	*/
	function hook_archive_sidebar_bottom() {
		global $redux_options;
		if ( !empty($redux_options['hook-archive-sidebar-bottom']) ) {
			return $redux_options['hook-archive-sidebar-bottom'];
		}
	}

	/**
	* Loads content into the bottom section of the archive page. 
	* 
	* @since 0.8
	* @access public
	* @return string $redux_options['hook-archive-bottom']	The option value defined in the admin.
	* 
	*/
	function hook_archive_bottom() {
		global $redux_options;
		if ( !empty($redux_options['hook-archive-bottom']) ) {
			return $redux_options['hook-archive-bottom'];
		}
	}

	/**
	* Loads content into the top section of the single page. 
	* 
	* @since 0.8
	* @access public
	* @return string $redux_options['hook-single-top']	The option value defined in the admin.
	* 
	*/
	function hook_single_top() {
		global $redux_options;
		if ( !empty($redux_options['hook-single-top']) ) {
			return $redux_options['hook-single-top'];
		}
	}

	/**
	* Loads content into the bottom section of the single page. 
	* 
	* @since 0.8
	* @access public
	* @return string $redux_options['hook-single-bottom']	The option value defined in the admin.
	* 
	*/
	function hook_single_bottom() {
		global $redux_options;
		if ( !empty($redux_options['hook-single-bottom']) ) {
			return $redux_options['hook-single-bottom'];
		}
	}