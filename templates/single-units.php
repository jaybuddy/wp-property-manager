<?php get_header(); ?>

<?php
  /* Prep the Unit Data */
  
  include(ABSPATH.'/wp-content/plugins/wp-property-manager/includes/helpers.php');
  $rental = setupSingleUnitData( get_the_ID() );
  include(ABSPATH.'/wp-content/plugins/wp-property-manager/includes/email-to-friend.php');
?>
<script type='text/javascript'>
  /* Echo out some PHP that we will need for the JS variables. */
  var latlng = new google.maps.LatLng(<?php echo $rental['lat'][0]; ?>,<?php echo $rental['lon'][0]; ?>);
  var panoramaOptions = {
      position: latlng,
      pov: {
          heading: <?php echo (!empty($rental['sv_rotation'][0])) ? $rental['sv_rotation'][0] : 0 ?>,
          pitch: <?php echo (!empty($rental['sv_pitch'][0])) ? $rental['sv_pitch'][0] : 0 ?>,
          zoom: <?php echo (!empty($rental['sv_zoom'][0])) ? $rental['sv_zoom'][0] : 0 ?>
      }
  };
</script>

<?php if ($rental['status'][0] == 'Rented') { ?>
  
  <div id='content'>
    <div class='container'>
      <div class='row'>
        <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
          <div class='panel panel-default'>
              <div class='panel-heading'>Whoops!</div>
              <div class='panel-body'>
                <p>The unit you are looking for is not available.</p>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>

<?php } else { ?>

<div id='content'>
     <section id='wppm'>
          <div class='wppm-head'>
               <div class='container'>
                    <div class='units-header'>
                         <div class='pull-left mobile-header hidden-md hidden-lg'>
                              <h1><?php echo $rental['address'][0]; ?></h1>
                              <h4><?php echo $rental['city'][0]; ?> <?php echo $rental['state'][0]; ?>, <?php echo $rental['zip'][0]; ?></h4>
                         </div>
                         <div class='pull-left pc-header hidden-xs hidden-sm'>
                              <h1><?php echo $rental['address'][0]; ?>, <?php echo $rental['city'][0]; ?> <?php echo $rental['state'][0]; ?>, <?php echo $rental['zip'][0]; ?></h1>
                         </div>
                         <div class='pull-right unit-options'>
                              <?php echo the_application( $rental ); ?>
                              <?php echo the_email_a_friend(); ?>
                              
                         </div>
                    </div>
               </div>
          </div>
        
     <div class='single-content-section row'>
          <div class='container'>
               <div class='row'>
                    <div class='hook-single-top'>
                      <?php echo hook_single_top(); ?>
                    </div>
               </div>
               <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <div class='row'>
                         <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                              <section class='unit-details'>

                                   <div class='panel panel-default' id='unit-perts'>
                                        <div class='panel-body'>
                                             <div class='data-row'>
                                                  <div class='data-row-half'>
                                                       <span><?php echo (!empty($rental['bedrooms'][0])) ? $rental['bedrooms'][0] : 'N/K'; ?></span><br>Bedroom(s)
                                                  </div>
                                                  <div class='data-row-half last'>
                                                       <span><?php echo (!empty($rental['bathrooms'][0])) ? $rental['bathrooms'][0] : 'N/K'; ?></span><br>Bathroom(s)
                                                  </div>
                                                  <div class='clear'></div>
                                             </div>
                                             <div class='data-row'>
                                                  <div class='data-row-full'>
                                                       <span class='avail'><?php echo (!empty($rental['date_available'][0])) ? $rental['date_available'][0] : 'Coming Soon!'; ?></span><br>Date Available
                                                  </div>
                                             </div>
                                              <div class='data-row last'>
                                                  <div class='data-row-full last'>
                                                       <span class='rent'>$<?php echo (!empty($rental['rent'][0])) ? $rental['rent'][0] : 'Coming Soon!'; ?></span><br>Monthly Rent
                                                  </div>
                                             </div>
                                        </div>
                                   </div>

                                   <div class='panel panel-default'>
                                        <div class='panel-heading'>Unit Description</div>
                                        <div class='panel-body'>
                                             <div class='description'>
                                                  <?php echo (!empty($rental['description'][0])) ? $rental['description'][0] : 'Description coming soon!'; ?>
                                             </div>
                                         </div>
                                   </div>
                                   
                                   <div class='panel panel-default' id='unit-info'>
                                        <div class='panel-heading'>Unit Information</div>
                                        <div class='panel-body'>
                                             <table class='table table-bordered table-striped data-table'>
                                                  <tr>
                                                       <td width='50%'>Monthly Rent: <span><?php echo (!empty($rental['rent'][0])) ? '$'.$rental['rent'][0] : 'N/K'; ?></span></td>
                                                       <td width='50%'>Security Deposit: <span><?php echo (!empty($rental['deposit'][0])) ? '$'.$rental['deposit'][0] : 'Coming Soon!'; ?></span></td>
                                                  </tr>
                                                  <tr>
                                                       <td width='50%'>Community: <span><?php echo (!empty($rental['community'][0])) ? $rental['community'][0] : 'N/K'; ?></span></td>
                                                       <td width='50%'>Approx. SQFT: <span><?php echo (!empty($rental['sqft'][0])) ? $rental['sqft'][0] : 'N/K'; ?></span></td>
                                                  </tr>
                        
                                                  <?php echo (!empty($rental['parking'][0])) ? "<tr><td colspan='2'>Parking: <span>".$rental['parking'][0]."</span></td></tr>" : ""; ?>  
                                                  <?php echo (!empty($rental['pet_policy'][0])) ? "<tr><td colspan='2'>Pet Policy: <span>".$rental['pet_policy'][0]."</span></td></tr>" : ""; ?>
                                                  <?php echo (!empty($rental['smoking'][0])) ? "<tr><td colspan='2'>Smoking Policy: <span>".$rental['smoking'][0]."</span></td></tr>" : ""; ?>  
                                             </table>
                                        </div>
                                   </div>

                                   <div class='panel panel-default' id='unit-info'>
                                        <div class='panel-heading'>Unit Features</div>
                                        <div class='panel-body'>
                                             <div class='interior-column'>
                                                  <table class='table table-bordered table-striped data-table'>
                                                       <?php echo (!empty($rental['fireplace'][0])) ? "<tr><td colspan='2'>Fireplace: <span>".$rental['fireplace'][0]."</span></td></tr>" : ""; ?>  
                                                       <?php echo (!empty($rental['washerdryer'][0])) ? "<tr><td colspan='2'>Washer/Dryer: <span>".$rental['washerdryer'][0]."</span></td></tr>" : ""; ?>
                                                       <?php echo (!empty($rental['patio'][0])) ? "<tr><td colspan='2'>Patio: <span>".$rental['patio'][0]."</span></td></tr>" : ""; ?>  
                                                       <?php echo (!empty($rental['balcony'][0])) ? "<tr><td colspan='2'>Balcony: <span>".$rental['balcony'][0]."</span></td></tr>" : ""; ?>
                                                       <?php echo (!empty($rental['pool'][0])) ? "<tr><td colspan='2'>Pool: <span>".$rental['pool'][0]."</span></td></tr>" : ""; ?>
                                                       <?php echo (!empty($rental['spa'][0])) ? "<tr><td colspan='2'>Spa: <span>".$rental['spa'][0]."</span></td></tr>" : ""; ?>
                                                       <?php echo (!empty($rental['window_coverings'][0])) ? "<tr><td colspan='2'>Window Coverings: <span>".$rental['window_coverings'][0]."</span></td></tr>" : ""; ?>  
                                                       <?php echo (!empty($rental['floor_coverings'][0])) ? "<tr><td colspan='2'>Floor Coverings: <span>".$rental['floor_coverings'][0]."</span></td></tr>" : ""; ?>
                                                       <?php echo (!empty($rental['countertops'][0])) ? "<tr><td colspan='2'>Countertops: <span>".$rental['countertops'][0]."</span></td></tr>" : ""; ?>  
                                                       <?php echo (!empty($rental['walkin_closets'][0])) ? "<tr><td colspan='2'>Walk-in Closets: <span>".$rental['walkin_closets'][0]."</span></td></tr>" : ""; ?>
                                                       <?php echo (!empty($rental['custom_paint'][0])) ? "<tr><td colspan='2'>Custom Paint: <span>".$rental['custom_paint'][0]."</span></td></tr>" : ""; ?>
                                                       <?php echo (!empty($rental['heating_type'][0])) ? "<tr><td colspan='2'>Heating Type: <span>".$rental['heating_type'][0]."</span></td></tr>" : ""; ?>
                                                       <?php echo (!empty($rental['ac_type'][0])) ? "<tr><td colspan='2'>AC Type: <span>".$rental['ac_type'][0]."</span></td></tr>" : ""; ?>
                                                  </table>
                                             </div>
                                        </div>
                                   </div>

                                   
                                   <div class='panel panel-default'>
                                        <div class='panel-heading'>Utilities</div>
                                        <div class='panel-body'>
                                             <p>The table below describes what utilities the tenant is responsible for paying.</p>
                                             <table class='table table-striped table-bordered util-table'>
                                                  <tr>
                                                       <th></th>
                                                       <th>Tenant</th>
                                                       <th>Owner</th>
                                                       <th>Not Applicable</th>
                                                  </tr>
                                                  <tr>
                                                       <td>Water</td>
                                                       <td><?php echo ($rental['water'][0] == 'Tenant') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['water'][0] == 'Owner') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['water'][0] == 'N/A') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td>Sewer</td>
                                                       <td><?php echo ($rental['sewer'][0] == 'Tenant') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['sewer'][0] == 'Owner') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['sewer'][0] == 'N/A') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td>Trash</td>
                                                       <td><?php echo ($rental['trash'][0] == 'Tenant') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['trash'][0] == 'Owner') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['trash'][0] == 'N/A') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td>Gas</td>
                                                       <td><?php echo ($rental['gas'][0] == 'Tenant') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['gas'][0] == 'Owner') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['gas'][0] == 'N/A') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td>Electric</td>
                                                       <td><?php echo ($rental['electric'][0] == 'Tenant') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['electric'][0] == 'Owner') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['electric'][0] == 'N/A') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td>Phone</td>
                                                       <td><?php echo ($rental['phone'][0] == 'Tenant') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['phone'][0] == 'Owner') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['phone'][0] == 'N/A') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td>Cable</td>
                                                       <td><?php echo ($rental['cable'][0] == 'Tenant') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['cable'][0] == 'Owner') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['cable'][0] == 'N/A') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td>Internet</td>
                                                       <td><?php echo ($rental['internet'][0] == 'Tenant') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['internet'][0] == 'Owner') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                       <td><?php echo ($rental['internet'][0] == 'N/A') ? '<i class="fa fa-check-circle-o"></i>' : ''; ?></td>
                                                  </tr>
                                             </table>

                                             <?php if (!empty($rental['other_utilities'][0])) { ?>
                                                  <p><?php echo $rental['other_utilities'][0]; ?></p>
                                             <?php } ?>
                                        </div>
                                  </div>    
                              </section>
                         </div>

                         <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>  

                            <div class='article-wrap'>
                                   <section class='images images-table'>
                                        <div class='panel panel-default'>
                                             <div class='panel-heading'>Unit Photos</div>
                                             <div class='panel-body'>
                                                  <div class='big-image' id='big-image'>
                                                      <a href="javascript:;" data-toggle="modal" data-target="#slideshowModal">
                                                        <img src='<?php echo $rental['featured_image']; ?>' alt='Featured Image' />
                                                      </a>
                                                  </div>
                                                  <div class='mini-images'>
                                                       <?php foreach ($rental['gallery_images'] as $i) { ?>
                                                            <a href='<?php echo $i['full'][0]; ?>' class='mini-image'>
                                                                 <img src='<?php echo $i['thumb'][0]; ?>' alt='Mini Image' />
                                                            </a>
                                                       <?php } ?>
                                                  </div>
                                                  <div class='clear'></div>
                                                  <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#slideshowModal">
                                                      <i class='fa fa-camera'></i> Larger Images
                                                  </button>
                                             </div>
                                        </div>
                                   </section>
                                   <?php echo the_google_map( $rental['ui_show_gm'][0] ); ?>
                                   <?php echo the_google_sv( $rental['ui_show_gsv'][0] ); ?>
                                   
                                   <?php if ($rental['ui_show_gsv'][0] != 'No') { ?>
                                        
                                   <?php } ?>  
                              </div>    
                         </div>
                    </div>

               <?php endwhile; else: ?>

                    <p> We're Sorry, no posts were found!</p>
           
               <?php endif; ?>

               <div class='row'>
                    <div class='hook-single-bottom'>
                      <?php echo hook_single_bottom(); ?>
                    </div>
               </div>
          </div>
     </div>
     
</div>

<div class="modal" id="slideshowModal" tabindex="-1" role="dialog" aria-labelledby="slideshowModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="overflow:hidden">
            <div class="modal-header" style="overflow:hidden">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Property Photos</h4>
            </div>
            <div class="modal-body" style="overflow:hidden">
                <div class='ss-overlay' style='display:none'><i class="fa fa-circle-o-notch fa-spin"></i></div>
                <div class='slideshow-main-image'>
                    <img id='smi' src='<?php echo $rental['featured_image']; ?>' alt='Featured Image' />
                </div>
            </div>
            <div class="modal-footer" style="margin-top:0px">
                <?php foreach ($rental['gallery_images'] as $i) { ?>
                    <a href='<?php echo $i['full'][0]; ?>?w=<?php echo $i['full'][1]; ?>&h=<?php echo $i['full'][2]; ?>' class='ss-mini-image'>
                         <img src='<?php echo $i['thumb'][0]; ?>' width='<?php echo $i['thumb'][1]; ?>' height='<?php echo $i['thumb'][0]; ?>' alt='Mini Image' />
                    </a>
               <?php } ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php } ?>
    

<script type='text/javascript' src="<?php bloginfo('url'); ?>/wp-content/plugins/wp-property-manager/assets/js/min/single-min.js"></script>

<?php get_footer(); ?>
