!function($){"use strict";$(document).ready(function(){function e(){var e={zoom:14,center:window.latlng,mapTypeId:google.maps.MapTypeId.TERRAIN},n=new google.maps.Map(document.getElementById("frontend-map"),e),o=new google.maps.Marker({position:window.latlng,map:n});o.setMap(n)}e()})}(jQuery);