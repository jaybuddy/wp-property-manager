<?php
	if (isset( $_POST['sent'] ) && wp_verify_nonce($_POST['sent'], 'send_to_friend') ) {
          echo 'form submitted with nonce correctly';
     }
?>

<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Send to a Friend!</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="" method="post" id="shareForm">
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

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type='text/javascript'>
$(document).ready(function() {
    $('#shareForm').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'fa fa-check-circle',
            invalid: 'fa fa-exclamation-circle',
            validating: 'fa fa-refresh'
        },
        fields: {
            your_name: {
                message: 'The your name is not valid',
                validators: {
                    notEmpty: {
                        message: 'The your name is required and cannot be empty'
                    },
                    stringLength: {
                        min: 3,
                        max: 16,
                        message: 'The your name must be more than 3 and less than 16 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'The your name can only consist of alphabetical, number and underscore'
                    }
                }
            },
            your_email: {
                validators: {
                    notEmpty: {
                        message: 'The your email is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            friends_name: {
                message: 'The friends name is not valid',
                validators: {
                    notEmpty: {
                        message: 'The friends name is required and cannot be empty'
                    },
                    stringLength: {
                        min: 3,
                        max: 16,
                        message: 'The friends name must be more than 3 and less than 16 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'The friends can only consist of alphabetical, number and underscore'
                    }
                }
            },
            friends_email: {
                validators: {
                    notEmpty: {
                        message: 'The friends email is required and cannot be empty'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            }
        }
    });
});
</script>