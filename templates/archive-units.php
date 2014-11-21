<?php get_header(); ?>

<?php 
	include(ABSPATH.'wp-content/plugins/wp-property-manager/assets/includes/helpers.php'); 
	$posts = setupArchiveUnitsData();
?>


<script type='text/javascript'>
	var apts = <?php echo json_encode($posts); ?>,
		apts_count = <?php echo count($posts); ?>,
		markers = [],
		map;
</script>

	<div id='content'>
		<section id='wppm'>
			<div class='wppm-head'>
				<div class='container'>
					
					    <div class='units-header'>
				    		<h1 class='pull-left'>Properties for Rent</h1>
				    		<div class='pull-right'>
				    			<div class="view-options btn-group">
								  	<button type="button" class="btn btn-wppm selected" id='list-view'><i class='fa fa-list'></i><span class='hidden-xs'> List View</span></button>
								  	<button type="button" class="btn btn-wppm" id='map-view'><i class='fa fa-map-marker'></i><span class='hidden-xs'> Map View</span></button>
								</div>
				    		</div>
				    	</div>
					
			    </div>
			</div>
		    
		    <div class='archive-content-section row' id='list-view-content'>
		    	<div class='container'>
			    	<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>
			    		<div class='article-wrap'>
				    		<?php foreach($posts as $post) { ?>
				    			<?php $listingAge = listingAge($post->meta['last_avail_date'][0]); ?>
					    		<article id='p<?php echo $post->ID; ?>' class='units-tile'>
					    			<header>
					    				<address>
					    					<span><?php echo $post->meta['address'][0]; ?></span>, <?php echo $post->meta['city'][0]; ?> <?php echo $post->meta['state'][0]; ?>, <?php echo $post->meta['zip'][0]; ?>
					    				</address>
					    				
					    				<div class='clear'></div>
					    			</header>
					    			<div class='feat-image'>
					    				<img class='img-thumb' src='<?php echo $post->meta['thumb']; ?>' />
					    			</div>
					    			<div class='units-details'>
					    				<table class='table table-bordered'>
					    					<tr>
					    						<td width='50%'><span><?php echo $post->meta['bedrooms'][0]; ?></span><br>Bedroom(s)</td>
					    						<td width='50%'><span><?php echo $post->meta['bathrooms'][0]; ?></span><br>Bathroom(s)</td>
					    					</tr>
					    					<tr>
					    						<td colspan='2'><span class='avail'><?php echo $post->meta['date_available'][0]; ?></span><br>Date Available</td>
					    					</tr>
					    					<tr>
					    						<td colspan='2'><span class='rent'>$<?php echo $post->meta['rent'][0]; ?></span><br>Monthly Rent</td>
					    					</tr>
					    				</table>
					    				<a class='more-details-button' href='<?php echo $post->meta['permalink']; ?>'>More Details <i class='fa fa-arrow-right'></i></a>
					    			</div>
					    			<div class='clear'></div>
					    			<footer>
					    				<div class='freshness <?php echo $listingAge["class"]; ?>'><i class="fa fa-clock-o"></i> Posted <?php echo $listingAge['text']; ?></div>
					    			</footer>
					    		</article>
				    		<?php } ?>
				    	</div>
			    	</div>

			    	<div class='hidden-xs col-sm-4 col-md-4 col-lg-4'>
			    		<aside id='units-list'>
			    			<div id='floating-list'>
			    				<ul>
			    					<?php foreach($posts as $post) { ?>
			    						
			    						<li class='mini-tile'>
				    						<a href='#p<?php echo $post->ID; ?>'>
				    							<div class='mini-image'>
				    								<img src='<?php echo $post->meta['mini_thumb']; ?>' alt='<?php echo $post->meta['address'][0]; ?>'>
				    							</div>
				    							<div class='mini-details'>
				    								<ul>
				    									<li><?php echo $post->meta['community'][0]; ?></li>
				    									<li class='rent'>$<?php echo $post->meta['rent'][0]; ?>/mo</li>
				    									<li class='brba'><?php echo $post->meta['bedrooms'][0]; ?> bed / <?php echo $post->meta['bathrooms'][0]; ?> bath</li>
				    								</ul>
				    							</div>
				    							<div class='clear'></div>
				    						</a>
			    						</li>
				    					
			    					<?php } ?>
			    				</ul>
			    			</div>
			    		</aside>
			    	</div>
				    
			    </div>
			</div>

			<div class='archive-content-section row' id='map-view-content' style='display:none'>
		    	<div class='container'>
			    	<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>
			    		<div id='units-map'>Loading...</div>
			    	</div>

			    	<div class='hidden-xs col-sm-4 col-md-4 col-lg-4'>
			    		<aside id='units-list'>
			    			<div id='floating-map-list'>
			    				<ul>
			    					<?php foreach($posts as $post) { ?>
			    						<?php $json_latlng = json_encode(array("id"=> $post->ID, "lat" => $post->meta['lat'][0], "lng" => $post->meta['lon'][0])); ?>
			    						<li class='mini-tile'>
				    						<a href='#p<?php echo $post->ID; ?>' data='<?php echo $json_latlng ?>'>
				    							<div class='mini-image'>
				    								<img src='<?php echo $post->meta['mini_thumb']; ?>' alt='<?php echo $post->meta['address'][0]; ?>'>
				    							</div>
				    							<div class='mini-details'>
				    								<ul>
				    									<li><?php echo $post->meta['community'][0]; ?></li>
				    									<li class='rent'>$<?php echo $post->meta['rent'][0]; ?>/mo</li>
				    									<li class='brba'><?php echo $post->meta['bedrooms'][0]; ?> bed / <?php echo $post->meta['bathrooms'][0]; ?> bath</li>
				    								</ul>
				    							</div>
				    							<div class='clear'></div>
				    						</a>
			    						</li>
				    					
			    					<?php } ?>
			    				</ul>
			    			</div>
			    		</aside>
			    	</div>
				    
			    </div>
			</div>
		</section>
	</div>

<!--<script type='text/javascript' src="<?php bloginfo('url'); ?>/wp-content/plugins/wp-property-manager/assets/js/min/archive-min.js"></script>-->
<script type='text/javascript' src="<?php bloginfo('url'); ?>/wp-content/plugins/wp-property-manager/assets/js/archive.js"></script>
<?php get_footer(); ?>