(function($) {

	"use strict";

	$(document).ready(function(){

		/* Sticky Content on Scrolling on list page */
        var el = $('.wppm-head').offset().top;
        $(window).scroll(function(){
            var doc = $(document).scrollTop();

            if (doc >= el) {
                $('.wppm-head').css({'position':'fixed', 'top':'0px','border-bottom':'1px solid #BBBBBB'});
            } 
            if (doc <= el) {
                $('.wppm-head').css({'position':'absolute','top':'0px','border-bottom':'0px'});
            } 
        });

        $('a.mini-image').on('click', function(){
            $('#big-image').children('a').children('img').attr('src', $(this).attr('href'));
            console.log($('#big-image'));
            return false;
        });

         $('.modal-dialog').css({'width':$(window).width() - 50, 'max-height':$(window).height() - 50});

         /* Google Maps Javascript */

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
	  
	  	function initialize_map() {
	    	var myOptions = {
	      		zoom: 14,
	      		center: window.latlng,
	      		mapTypeId: google.maps.MapTypeId.TERRAIN,
	    	};
	    	var map = new google.maps.Map(document.getElementById("frontend-map"), myOptions);
	    	var marker = new google.maps.Marker({
	            position: window.latlng,
	            map: map 
	     	});
	   		marker.setMap(map);
	  	}

	  	initialize_map();
	  	initialize_sv();

	  	/* Slideshow Modal Javascript */

	  	var modalMargin = 30,
        	modalBodyPadding = 20,
        	modalContent = $(window).height() - (2 * modalMargin),
        	modalHeaderHeight = 55,
        	modalFooterHeight = 120,
        	modalBodyHeight = modalContent - modalHeaderHeight - modalFooterHeight,
        	modalBodyInnerHeight = modalBodyHeight - (2 * modalBodyPadding),
        	modalBodyWidth = $(window).width() - (2 * modalMargin),
        	modalBodyInnerWidth = modalBodyWidth - (2 * modalBodyPadding);

        $('#slideshowModal').on('shown.bs.modal', function() {   
            $('#slideshowModal .modal-content').css('height', modalContent);
            $('#slideshowModal .modal-body').css('height', modalBodyHeight);

            var imageWidth = $('#smi').width();
            var imageHeight = $('#smi').height();
            var ratio = imageHeight/imageWidth;

            if (imageWidth >= modalBodyInnerWidth || imageHeight >= modalBodyInnerHeight) {
                reduceImage(modalBodyInnerHeight, modalBodyInnerWidth, imageHeight, imageWidth, ratio);
            } else if (imageWidth <= modalBodyInnerWidth || imageHeight <= modalBodyInnerHeight) {
                increaseImage(modalBodyInnerHeight, modalBodyInnerWidth, imageHeight, imageWidth, ratio);
            }
                 
        });

        $('a.ss-mini-image').on('click', function(){
            $('.ss-overlay').show();

            var w = getUrlVars($(this).attr('href'))["w"],
                h = getUrlVars($(this).attr('href'))["h"],
                r = h/w;

            $('#smi').css({'height': 'auto', 'width': 'auto'});
            $('#smi').attr('src', $(this).attr('href')).attr('width', w).attr('height', h);

            if (w >= modalBodyInnerWidth || h >= modalBodyInnerHeight) {
                reduceImage(modalBodyInnerHeight, modalBodyInnerWidth, h, w, r);
            } else if (w <= modalBodyInnerWidth || h <= modalBodyInnerHeight) {
                increaseImage(modalBodyInnerHeight, modalBodyInnerWidth, h, w, r);
            }

            return false;

        });

        function getUrlVars(url) {
            var vars = {};
            var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                vars[key] = value;
            });
            return vars;
        }

        function reduceImage(maxModalBodyHeight, maxModalBodyWidth, ih, iw, ratio) {
            if (maxModalBodyHeight <= ih || maxModalBodyWidth <= iw) {
                var newImageWidth = iw - 20;
                var newImageHeight = newImageWidth * ratio;
                reduceImage(maxModalBodyHeight, maxModalBodyWidth, newImageHeight, newImageWidth, ratio);
            } else {
                $('#smi').animate({'width': iw, 'height': ih}, 200);
                setTimeout(fadeLoader, 300);
            }
        }
        
        function increaseImage(maxModalBodyHeight, maxModalBodyWidth, ih, iw, ratio) {
            if (maxModalBodyHeight > ih || maxModalBodyWidth > iw) {
                var newImageWidth = iw + 10;
                var newImageHeight = newImageWidth * ratio;
                reduceImage(maxModalBodyHeight, maxModalBodyWidth, newImageHeight, newImageWidth, ratio);
            } else {
                $('#smi').animate({'width': iw, 'height': ih}, 200);
                setTimeout(fadeLoader, 300);
            }
        }  

        function fadeLoader() {
          $('.ss-overlay').fadeOut('slow');
        }

	});
	
})(jQuery);

		

