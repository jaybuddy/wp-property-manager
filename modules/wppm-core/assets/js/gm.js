/* Google Maps Javascript */
(function($) {

  "use strict";

  $(document).ready(function(){

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
  });
  
})(jQuery);