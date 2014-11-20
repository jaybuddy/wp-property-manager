<?php 
	//Construct Address for Google Maps//
	$a = get_post_meta($post->ID, 'address', true);
	$c = get_post_meta($post->ID, 'city', true);
	$s = get_post_meta($post->ID, 'state', true);
	$z = get_post_meta($post->ID, 'zip', true);
	$lat = get_post_meta($post->ID, 'lat', true);
	$lon = get_post_meta($post->ID, 'lon', true);
	$rot = get_post_meta($post->ID, 'sv_rotation', true);
	$pit = get_post_meta($post->ID, 'sv_pitch', true);
	$zoo = get_post_meta($post->ID, 'sv_zoom', true);
	
	if (!empty($a) && !empty($c) && !empty($s) && !empty($z)) {
		$flag = false;
		$address = urlencode($a.' '.$c.', '.$s.' '.$z);
	} else {
		$flag = true;
	}

?>

<div class='tab-wrap'>
	<div class='lcol'>
		<ul>
			<li class='active'><a href='#google-map'><i class='fa fa-compass'></i>  Google Map</a></li>
			<li><a href='#google-street-view'><i class='fa fa-compass'></i>  Street View</a></li>
			<li><a href='#display-options'><i class='fa fa-desktop'></i>  Display Options</a></li>
		</ul>
	</div>
	<div class='rcol'>
		<div id='google-map' class='mpanel' style="display:block">
			<?php if (!$flag) { ?>
				<img class='map' src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $address; ?>&markers=color:blue%7C<?php echo $lat; ?>,<?php echo $lon; ?>&zoom=13&size=300x250&sensor=false">
			<?php } else { ?>
				Please provide a full address to display map
			<?php } ?>
		</div>
		<div id='google-street-view' class='mpanel' style='display:none'>
			<?php if (!$flag) { ?>
				<div id='street_view'></div>
				<div id='street_view_fields'>
					<table>
						<tr>
							<td>Longitude<br><input type="text" name="lon" id="lon" value="<?php echo get_post_meta($post->ID, 'lon', true); ?>"  /></td>
						</tr>
						<tr>
							<td>Latitude<br><input type="text" name="lat" id="lat" value="<?php echo get_post_meta($post->ID, 'lat', true); ?>"  /></td>
						</tr>
						<tr>
							<td>Rotation<br><input type="text" name="sv_rotation" id="sv_rotation" value="<?php echo get_post_meta($post->ID, 'sv_rotation', true); ?>"  /></td>
						</tr>
						<tr>
							<td>Pitch<br><input type="text" name="sv_pitch" id="sv_pitch" value="<?php echo get_post_meta($post->ID, 'sv_pitch', true); ?>"  /></td>
						</tr>
						<tr>
							<td>Zoom<br><input type="text" name="sv_zoom" id="sv_zoom" value="<?php echo get_post_meta($post->ID, 'sv_zoom', true); ?>"  /></td>
						</tr>
					</table>
				</div>
				<div class='clear'></div>
				<script type='text/javascript'>
					jQuery(function($){

						$('a[href="#google-street-view"]').click(function(e) {
		    				setTimeout(initialize_sv(), 500);
						});

						function initialize_sv() {
							
							var latlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lon; ?>);
							var myOptions = {
						  		zoom: 14,
						  		center: latlng,
						  		mapTypeId: google.maps.MapTypeId.TERRAIN,
						  		navigationControl: true,
								navigationControlOptions: {
						      		style: google.maps.NavigationControlStyle.SMALL
						    	}
						    };

						    var panoramaOptions = {
							  position: latlng,
							  pov: {
							    heading: <?php echo (!empty($rot)) ? $rot : 0 ?>,
							    pitch: <?php echo (!empty($pit)) ? $pit : 10 ?>,
							    zoom: <?php echo (!empty($zoo)) ? $zoo : 1 ?>
							  }
							};

							var map = new google.maps.Map(document.getElementById("street_view"), myOptions);
							var panorama = new google.maps.StreetViewPanorama(document.getElementById("street_view"), panoramaOptions);
							
							map.setStreetView(panorama);

							google.maps.event.addListener(panorama, 'pov_changed', function() {
								pov = panorama.getPov(); 
								
								$('input#sv_rotation').val(parseInt(pov.heading));
								$('input#sv_zoom').val(parseInt(pov.zoom));
								$('input#sv_pitch').val(parseInt(pov.pitch));
							});

							google.maps.event.addListener(panorama, 'position_changed', function() {
							    lln = panorama.getPosition();
							    
							    $('input#lat').val(parseFloat(lln.lat()));
								$('input#lon').val(parseFloat(lln.lng()));
								
							});
						};

						
					});
					
				</script>
			<?php } else { ?>
				PLease provide a full address to display map
			<?php } ?>
		</div>
		<div id='display-options' class='mpanel' style="display:none">
			<table>
				<tr>
					<td class='label'><label>Display Google Map on unit page?</label></td>
					<td>
						<?php $gm = get_post_meta($post->ID, 'ui_show_gm', true); ?>
						<select name='ui_show_gm' id='ui_show_gm'>
							<option value='Yes' <?php echo ($gm == 'Yes') ? 'Selected' : ''; ?>>Yes</option>
							<option value='No' <?php echo ($gm == 'No') ? 'Selected' : ''; ?>>No</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Display Google Street View on unit page?</label></td>
					<td>
						<?php $gsv = get_post_meta($post->ID, 'ui_show_gsv', true); ?>
						<select name='ui_show_gsv' id='ui_show_gsv'>
							<option value='Yes' <?php echo ($gsv == 'Yes') ? 'Selected' : ''; ?>>Yes</option>
							<option value='No' <?php echo ($gsv == 'No') ? 'Selected' : ''; ?>>No</option>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class='clear'></div>
</div>

<script type='text/javascript'>
	jQuery(function($){
		
		$('#maps .lcol ul li a').click(function(){
			var t = $(this);
			
			$('.mpanel').hide();
			$(t.attr('href')).show();
			
			$('#maps .lcol ul li').removeClass('active');
			t.parent().addClass('active');
			return false;
		});
	});
</script>



	





