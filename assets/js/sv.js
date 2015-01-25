/* Google SV Javascript */
(function($) {

  "use strict";

  $(document).ready(function(){

		 function initialize_sv() {
		  	var myOptions = {
		        	zoom: 14,
		        	center: window.latlng,
		        	mapTypeId: google.maps.MapTypeId.TERRAIN,
		        	navigationControl: true,
		        	navigationControlOptions: {
		          	style: google.maps.NavigationControlStyle.SMALL
		    	}
		  	};
		  	var map = new google.maps.Map(document.getElementById("frontend-street-view"), myOptions);
		  	var panorama = new google.maps.StreetViewPanorama(document.getElementById("frontend-street-view"), window.panoramaOptions);
		  	map.setStreetView(panorama);   
		}

		initialize_sv();

	 });
  
})(jQuery);