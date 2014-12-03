<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Send to a Friend!</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="post" id="shareForm">
                  <?php wp_nonce_field( 'send_to_friend', 'sent' ); ?>
                  <input type='hidden' name='property_id' value='<?php echo get_the_id(); ?>' />
                  
                  <div class="form-group">
                    <label class="col-md-4 control-label" for="your_name">Your Name</label>  
                    <div class="col-md-8">
                         <input id="your_name" name="your_name" type="text" placeholder="" class="form-control input-md" required="">
                      
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-4 control-label" for="your_email">Your Email</label>  
                    <div class="col-md-8">
                         <input id="your_email" name="your_email" type="text" placeholder="" class="form-control input-md" required="">
                      
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-4 control-label" for="friends_name">Your Friend</label>  
                    <div class="col-md-8">
                         <input id="friends_name" name="friends_name" type="text" placeholder="" class="form-control input-md" required="">
                      
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-4 control-label" for="friends_email">Your Friend</label>  
                    <div class="col-md-8">
                         <input id="friends_email" name="friends_email" type="text" placeholder="" class="form-control input-md" required="">
                      
                    </div>
                  </div>

                  <div class='row share-property-modal'>
                    <div class='col-md-6'>
                         <img src='<?php echo $rental['featured_image']; ?>' alt='Featured Image' />
                    </div>
                    <div class='col-md-6'>
                         <address><?php echo $rental['address'][0]; ?><br>
                         <?php echo $rental['city'][0]; ?> <?php echo $rental['state'][0]; ?>, <?php echo $rental['zip'][0]; ?></address>
                         <p><?php echo (!empty($rental['bedrooms'][0])) ? $rental['bedrooms'][0] : 'N/K'; ?> BR / <?php echo (!empty($rental['bathrooms'][0])) ? $rental['bathrooms'][0] : 'N/K'; ?> BA<br>
                         <?php echo $rental['community'][0]; ?></p>
                    </div>
                  </div>
                  
                  <button type="submit" class="btn btn-primary send-btn">Share this Property</button>
                  
                </form>
                <br>
                <div class='row'>
                  <div class='col-md-12'>
                       <div class="success-message alert alert-success" role="alert" style='display:none'></div>
                       <div class="fail-message alert alert-danger" role="alert" style='display:none'></div>
                  </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->