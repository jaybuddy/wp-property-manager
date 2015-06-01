<?php 
/**
 * The Template for displaying unit archives, including the main units page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/wppm/archive-units.php
 *
 * @author 		Jay Pedersen
 * @package 	WP Property Manager/Templates
 * @version     0.8
 */

get_header(); ?>

<?php 
	global $redux_options;
	include( dirname( dirname(__file__) ).'/includes/helpers/helpers.php' ); 
	$posts = setup_archive_units_data();
?>


<script type='text/javascript'>
	var apts = <?php echo json_encode($posts); ?>,
		apts_count = <?php echo count($posts); ?>,
		markers = [],
		map;
</script>

	<div id='content'>
		<section id='wppm' class='archive'>
			<div class='wppm-head'>
				<div class='container'>
					
				    <div class='units-header'>
			    		<h1 class='pull-left'>Properties for Rent</h1>
			    		<div class='pull-right'>
			    			<div class="view-options btn-group">
							  	<button type="button" class="btn btn-wppm selected" id='list-view'><i class='fa fa-list'></i></button>
							  	<button type="button" class="btn btn-wppm" id='map-view'><i class='fa fa-map-marker'></i></button>
							</div>
			    		</div>
			    	</div>
					
			    </div>
			</div>
		    
		    <div class='archive-content-section row list-view-content'>
		    	<div class='container'>
		    		<div class='row'>
		    			<div class='hook-archive-top'>
			    			<?php //echo hook_archive_top(); ?>
			    		</div>
			    	</div>
			    	<div class='row'>
			    		<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>
				    		<div class='article-wrap'>
					    		<?php foreach( $posts as $post ) { ?>
					    		
						    		<article id='p<?php echo $post->ID; ?>' class='units-tile'>
						    			<header>
						    				<address>
						    					<span><?php echo $post->meta['address'][0]; ?></span>, <?php echo $post->meta['city'][0]; ?> <?php echo $post->meta['state'][0]; ?>, <?php echo $post->meta['zip'][0]; ?>
						    				</address>
						    				<div class='clear'></div>
						    			</header>
						    			<div class='row tile-content'>
						    				<div class='col-xs-12 col-sm-5 col-md-5 col-lg-5 tile-col'>
						    					<div class='feat-image'>
						    						<img class='img-thumb' src='<?php echo $post->meta['thumb']; ?>' />
						    					</div>
						    				</div>
						    				<div class='col-xs-12 col-sm-7 col-md-7 col-lg-7 tile-col'>
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
						    				</div>
						    			</div>
						    			<footer>
						    				<?php echo the_listing_age( $post->meta['last_avail_date'][0] ); ?>
						    			</footer>
						    		</article>
					    		<?php } ?>
					    	</div>
			    		</div>
			    		<div class='hidden-xs col-sm-4 col-md-4 col-lg-4'>
				    		<aside class='units-list'>
				    			<div class='floating-list'>
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
				    				<div class='hook-archive-top'>
				    					<?php //echo hook_archive_sidebar_bottom(); ?>
				    				</div>
				    			</div>
				    		</aside>
			    		</div>
				    </div>
				    <div class='row'>
				    	<div class='hook-archive-bottom'>
							<?php //echo hook_archive_bottom(); ?>
						</div>
					</div>
			    </div>
			</div>
			

			<div class='archive-content-section row map-view-content' style='display:none'>
		    	<div class='container'>
		    		<div class='row'>
				    	<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>
				    		<div id='units-map'>Loading...</div>
				    	</div>

				    	<div class='hidden-xs col-sm-4 col-md-4 col-lg-4'>
				    		<aside class='units-list'>
				    			<div class='floating-map-list'>
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
			</div>
		</section>
	</div>

<!--<script type='text/javascript' src="<?php bloginfo('url'); ?>/wp-content/plugins/wp-property-manager/assets/js/min/archive-min.js"></script>-->
<script type='text/javascript' src="<?php echo dirname( dirname(__file__) ); ?>/assets/js/archive.js"></script>
<?php get_footer(); ?>