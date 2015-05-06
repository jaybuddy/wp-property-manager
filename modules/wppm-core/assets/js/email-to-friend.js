(function($) {

    "use strict";

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
            }).on('success.form.bv', function(e) {
                // Prevent form submission
                e.preventDefault();
                var $form = $(e.target);
                var bv = $form.data('bootstrapValidator');

                var o = {};
                var a = $form.serializeArray();
                $.each(a, function() {
                    if (o[this.name] !== undefined) {
                        if (!o[this.name].push) {
                            o[this.name] = [o[this.name]];
                        }
                        o[this.name].push(this.value || '');
                    } else {
                        o[this.name] = this.value || '';
                    }
                });

                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: share_property.ajaxurl,
                    data: {action: 'share_property', data: o},
                    success: function(response){
                        if (response.success) {
                            $('.alert').hide();
                            $form.slideUp('fast');
                            $('.success-message').html(response.message).show();
                        } else {
                            $('.alert').hide();
                            $('.fail-message').html(response.message).show();
                            $('button[type=submit]').attr('disabled', '');
                        }
                    }
                }); 


            });

            $('.modal').on('hidden.bs.modal', function(){
                $('#shareForm').show();
            }); 

            $('.modal').on('show.bs.modal', function(){
                $('#shareForm').show();
                $('.alert').empty().hide();
                $('button[type=submit]').attr('disabled', '');
            });

        });

})(jQuery);