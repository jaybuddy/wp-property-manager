(function($) {

	"use strict";

	$(document).ready(function(){

		$('#floating-list a[href*=#]').on('click', function(event){     
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top -75}, 500);
			$('#floating-list li').removeClass('selected');
			$(this).parent('li').addClass('selected');
		});

		$('#floating-list').css({'height':$(window).innerHeight()-70, 'overflow':'auto'});
		
		/* Sticky Content on Scrolling on list page */
		var el = $('.wppm-head').offset().top;
		$(window).scroll(function(){
			var doc = $(document).scrollTop();

			if (doc >= el) {
				$('.wppm-head').css({'position':'fixed', 'top':'0px','border-bottom':'1px solid #BBBBBB'});
			} 
			if (doc <= el) {
				$('.wppm-head').css({'position':'absolute','top':'0px','border-bottom':'0px'});
				//$('.wppm-head h1').css({'text-indent':'15px'});
			} 
		}); 

		var fl = $('#floating-list').offset().top - 65;
		var flstop = $('.article-wrap').height() - $('#floating-list').height() + $('#floating-list').offset().top -55;
		$(window).scroll(function(){
			var doc = $(document).scrollTop();
			if (doc >= fl && doc < flstop && doc > 0) {
				var n = doc - fl;
				$('#floating-list').css({'top':n+'px'});
			} 
		
			if (doc <= 10) {
				$('#floating-list').css({'top':'0px'});
			}
			
		});

		/* Button effects / Tabs */
		$('.btn-wppm').on('click', function(){
			var id = $(this).attr('id');
			$('.btn-wppm').removeClass('selected');
			$(this).addClass('selected');

			$('.archive-content-section').fadeOut('fast');
			$('#'+id+'-content').fadeIn('fast');

			/* Reset page position */
			$(document).scrollTop(0);

			if (id === 'map-view') {
				initializeMap();
			} 
		});

		/* Map Alterations */
		$('#floating-map-list a').on('click', function(event){     
			event.preventDefault();
			$('#floating-map-list li').removeClass('selected');
			$(this).parent('li').addClass('selected');
			
			var data = JSON.parse($(this).attr('data'));
			var i,
			 	marker;
			for ( marker in window.markers ) {
				window.markers[marker].infowindow.close();
				
				if ( window.markers[marker].id == data.id ) {
					window.markers[marker].infowindow.setContent(window.markers[marker].my_html);
					window.markers[marker].infowindow.open(window.map, window.markers[marker]);
				}
			} 
			alterMap(data);
		});

		$('.listingAge').tooltip();

		function initializeMap() {
	  		var latlng = new google.maps.LatLng(32.7792, -117.1724);
		  	var myOptions = {
		    	zoom: 12,
		    	center: latlng,
		    	mapTypeId: google.maps.MapTypeId.TERRAIN
		  	};
		 	var map = new google.maps.Map(document.getElementById("units-map"), myOptions);
			loadMarkers(map);
			window.map = map;
		}

		function loadMarkers(map) {
			for (var i = 0; i < window.apts.length; i++) {
				if (window.apts[i].meta.lat[0]) {
					var h = createHtml(window.apts[i]),
						p = new google.maps.LatLng(window.apts[i].meta.lat[0], window.apts[i].meta.lon[0]),
						marker = new google.maps.Marker({position:p});

					window.markers.push(marker);
					marker.id = window.apts[i].ID;
					marker.my_html = h;
					marker.setMap(map);
						
					var infowindow = new google.maps.InfoWindow({content: marker.my_html});
					google.maps.event.addListener(marker, "click", function() {
						  infowindow.setContent(this.my_html);
						  infowindow.open(map, this);
					});	
					marker.infowindow = infowindow;
				}
			}
			autoCenter(map);
		}

		function autoCenter(map) {
			var bounds = new google.maps.LatLngBounds();
			$.each(window.markers, function (index, marker) {
				bounds.extend(marker.position);
			});
			map.fitBounds(bounds);
		}
		
		function createHtml(a) {
			var theHtml = "<div class='map-infowindow'>";
				theHtml += "<div class='image'><a href='"+a.meta.permalink+"'><img src="+a.meta.mini_thumb+" width='80' height='55' alt='map image' /></a></div>";
				theHtml += "<div class='info'>";
				theHtml += "<address><a href='"+a.meta.permalink+"'>"+a.meta.address[0]+"</a></address>";
				theHtml += "<div class='rent'>$"+a.meta.rent+"</div>";
				theHtml += "<div class='brba'>"+a.meta.bedrooms+" bed / "+a.meta.bathrooms+" bath</div>";
				theHtml += "</div>";
			
			return theHtml;
		}

		function alterMap(unit) {
			var latlng = new google.maps.LatLng(unit.lat, unit.lng);
			
			map.setZoom(18);
			map.panTo(latlng);
		}
		
		window.onunload = google.maps.Unload;

	});
	
})(jQuery);

		

