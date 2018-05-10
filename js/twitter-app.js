var siteurl = $('.site_url').val();
var bioTweets = 'tweets';

var busyKeywords = false;
var limitKeywords = 15;
var offsetKeywords = 0;

var busyCategory = false;
var limitCategory = 15;
var offsetCategory = 0;

var busyProspects = false;
var limitProspects = 15;
var offsetProspects = 0;

var busyFollowers = false;
var limitFollowers = 15;
var offsetFollowers = 0;

var busyWebsites = false;
var limitWebsites = 15;
var offsetWebsites = 0;

var busyUnfollow = false;
var limitUnfollow = 15;
var offsetUnfollow = 0;

var busyResponses = false;
var limitResponses = 15;
var offsetResponses = 0;

var txtTweetBoxLength = 0;

// Start of document ready
$(document).ready(function() {

	if($('#ReplyMsgBox').length>0){
		$('#ReplyMsgBox').jqEasyCounter({
			'maxChars': 140,
			'maxCharsWarning': 120
		});
	}

	// Loading card thumbnail image
	setTimeout(function() {
		$('.img-loading').fadeOut();
	}, 1000);
	// End of the script
	
	// Temp customers datatable
	if ( $('#temp-customers').length > 0 ) {
		var dataTable = $('#temp-customers').DataTable({
			"columnDefs"	: [{
				"targets"		: 3,
				"orderable"		: false,
				"searchable"	: false
			}]
		});
	}
	// End of the script

	// Customer datatable
	if ( $('#customer-table').length > 0 ) {
		var dataTable = $('#customer-table').DataTable({
			"processing"	: true,
			"serverSide"	: true,
			"order"			: [[ 1, "asc" ]],
			"columnDefs"	: [{
				"targets"		: 0,
				"orderable"		: false,
				"searchable"	: false
			}, {
				"targets"		: 4,
				"orderable"		: false,
				"searchable"	: true
			}],
			"ajax" : {
				url 	: siteurl + "app-ajax?case=GetAllCustomers",
				type 	: "post",
				error  	: function() {
					$(".customer-table-error").html("");
					$("#customer-table").append('<tbody class="customer-table-error"><tr><th colspan="3">No data found in the database</th></tr></tbody>');
					$("#customer-table_processing").css("display","none");
				}
			}
		});

		$("#bulkDelete").on('click', function() {
			var status = this.checked;
			$(".deleteRow").each(function() {
				$(this).prop("checked", status);
			});
		});

		$('#deleteTriger').on("click", function(event) {
			if ( $('.deleteRow:checked').length > 0 ) {
				var ids = [];
				$('.deleteRow').each(function() {
					if ( $(this).is(':checked') ) { 
						ids.push($(this).val());
					}
				});
				var ids_string = ids.toString();
				$.ajax({
					type 	: "POST",
					url 	: siteurl + "app-ajax?case=CustomerDataDelete",
					data 	: {
						data_ids: ids_string
					},
					success : function(result) {
						dataTable.draw();
						$('#msgWrap').html(result);
					},
					async:false
				});
			}
		});	
	}
	// End of the script

	// Followers range slider for filter in twitter-crm.php
	if ( $("#followers").length > 0 ) {
		$("#followers").slider();
	}
	// End of the script

	// Followings range slider for filter in twitter-crm.php
	if ( $("#following").length > 0 ) {
		$("#following").slider();
	}
	// End of the script

	if ( $("div.bhoechie-tab-menu").length > 0 ) {
		$("div.bhoechie-tab-menu > div.list-group > a").click(function(e) {
			e.preventDefault();
			$(this).siblings('a.active').removeClass("active");
			$(this).addClass("active");
			var index = $(this).index();
			$("div.bhoechie-tab div.bhoechie-tab-content").removeClass("active");
			$("div.bhoechie-tab div.bhoechie-tab-content").eq(index).addClass("active");
		});
	}

	// Window resize event
	$(window).resize(function() {
		$('.sidebar').height($(window).height());
		if ( $('.tab-pane.active').attr('id') == 'prospect-finder-pipeline' ) {
			$('.tab-pane.active .nano').height($(window).height()-($('.topbar').outerHeight(true)+$('.group_content_topbar').outerHeight(true)+$('#twitter-tab').outerHeight(true)+$('.buttons-actions').outerHeight(true)+40));
		} else if ( $('.tab-pane.active').attr('id') == 'set-direct-message' ) {
			$('.tab-pane.active .nano').height($(window).height()-($('.topbar').height() + $('.group_content_topbar').height() + $('#twitter-tab').height() + 40));
		} else {
			$('.tab-pane.active .nano').height($(window).height()-($('.topbar').outerHeight(true)+$('.group_content_topbar').outerHeight(true)+$('#twitter-tab').outerHeight(true)+$('.buttons-actions').outerHeight(true)+85));
		}
	});
	// End of the script

	// Set sidebar height dynamically
	if ( $('.sidebar').length > 0 ) {
		$('.sidebar').height($(window).height());
	}
	if ( $('.panel-group').length > 0 ) {
	    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
	    $('.panel-group').on('shown.bs.collapse', toggleIcon);
    }
    // End of the script

    // Forgot Password Process
	if ( $('#unsubscribe_form').length > 0 ) {
		$('#unsubscribe_form').bootstrapValidator({
			live: 'enabled',
			excluded: [':disabled'],
	        message: 'This value is not valid',
	        feedbackIcons: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },
			fields: {
				name: {
	                validators: {
	                    notEmpty: {
	                        message: 'Please enter your name'
	                    }
	                }
	            },
	            email: {
	                validators: {
	                    notEmpty: {
	                        message: 'Please enter your email'
	                    },
	                    emailAddress: {
	                        message: 'The input is not a valid email address'
	                    }
	                }
	            },
	            cancel_reason: {
	                validators: {
						notEmpty: {
	                        message: 'Please choose reason of unsubscription'
	                    }
	                }
	            },
	            other: {
	                validators: {
						callback: {
	                        message: 'Please mention your reason of unsubscription',
	                        callback: function(value, validator, $field) {
	                        	if ( $('#cancel_reason').val() == 'Other' ) {
	                        		return (value != false) ? true : false;
	                        	} else {
	                        		return true;
	                        	}
	                        }
	                    }
	                }
	            }
	        }
	    }).on('status.field.bv', function(e, data) {
			data.bv.disableSubmitButtons(false);
	    }).on('success.form.bv', function(e, data) {
	        e.preventDefault();

	        $('.loader').removeClass('hide');
	        var $form = $(e.target);
	        var bv = $form.data('bootstrapValidator');
	        $.post($form.attr('action'), $form.serialize(), function(result) {
	            if ( JSON.stringify(result) == 1 ) {
					$form.html('<div id="unsubMsg" class="alert alert-success alert-dismissible" role="alert"><i class="fa fa-info-circle"></i> You have successfully submitted your cancel request. Social Sonic team will contact you soon.</div>');
				} else {
					$('#unsubMsg').html('<div class="alert alert-danger alert-dismissible" role="alert"><i class="fa fa-info-circle"></i> Something went wrong, please try again.</div>');
				}
				$('.loader').addClass('hide');
	        }, 'json');
		});
	}
	// End of the script

	// Forgot Password Process
	if ( $('#forgot_password_form').length > 0 ) {
		$('#forgot_password_form').bootstrapValidator({
			live: 'enabled',
			excluded: [':disabled'],
	        message: 'This value is not valid',
	        feedbackIcons: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },
			fields: {
				your_email: {
	                validators: {
	                    notEmpty: {
	                        message: 'Please enter your email'
	                    },
	                    emailAddress: {
	                        message: 'The input is not a valid email address'
	                    }
	                }
	            },
	            your_username: {
	                validators: {
						notEmpty: {
	                        message: 'Please enter your username'
	                    }
	                }
	            }
	        }
	    }).on('status.field.bv', function(e, data) {
			data.bv.disableSubmitButtons(false);
	    }).on('success.form.bv', function(e, data) {
	        e.preventDefault();

	        $('.loader').removeClass('hide');
	        var $form = $(e.target);
	        var bv = $form.data('bootstrapValidator');
	        $.post($form.attr('action'), $form.serialize(), function(result) {
	            if ( JSON.stringify(result) == 1 ) {
					$('#pswdMsg').html('<span class="text-success">We have mailed you a new login details.</span>');
				} else if ( JSON.stringify(result) == 2 ) {
					$('#pswdMsg').html('<span class="text-danget">Unable to reset your password, please try again.</span>');
				} else {
					$('#pswdMsg').html('<span class="text-danger">Email or Username is incorrect.</span>');
				}
				$form[0].reset();
				$('.loader').addClass('hide');
	        }, 'json');
		});
	}
	// End of the script

	// Login Process
	if ( $("#loginModal").length > 0 ) {
		$("#loginModal").on('shown.bs.modal', function() {
		    $('#username').focus();
		});
		$("#loginModal").on('hide.bs.modal', function() {
		    $('#login-form').bootstrapValidator('resetForm', true);
		    $('#loginError').empty();
		});
	}
	if ( $('#login-form').length > 0 ) {
		$('#login-form').bootstrapValidator({
			live: 'enabled',
			excluded: [':disabled'],
	        message: 'This value is not valid',
	        feedbackIcons: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },
			fields: {
				username: {
	                validators: {
	                    notEmpty: {
	                        message: 'Please enter your username'
	                    }
	                }
	            },
	            password: {
	                validators: {
						notEmpty: {
	                        message: 'Please enter your password'
	                    }
	                }
	            }
	        }
	    }).on('status.field.bv', function(e, data) {
			data.bv.disableSubmitButtons(false);
			$('#loginError').empty();
	    }).on('success.form.bv', function(e, data) {
	        e.preventDefault();

	        $('.loader').removeClass('hide');
	        var $form = $(e.target);
	        var bv = $form.data('bootstrapValidator');
	        $.post($form.attr('action'), $form.serialize(), function(result) {
	            if ( result.logStatus == 1 ) {
					window.location.href = result.Cust_Server + "app-launching/?ref=1&cust=" + result.Cust_ID + "&password=true";
				} else if ( result.logStatus == 2 ) {
					window.location.href = result.Cust_Server + "app-launching/?ref=2&cust=" + result.Cust_ID + "&sscrm=true";
				} else {
					$('#loginError').text('Username or Password is incorrect.');
					$('.loader').addClass('hide');
				}
	        }, 'json');
		});
	}
	// End of the script

	// Change and Skip password
	if ( $('#change-password-form').length > 0 ) {
		$('#change-password-form').bootstrapValidator({
			live: 'enabled',
			excluded: [':disabled'],
	        message: 'This value is not valid',
	        feedbackIcons: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },
			fields: {
				current_password: {
	                validators: {
	                    notEmpty: {
	                        message: 'Please enter your current password'
	                    },
						remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckCurrentPassword',
	                        message: 'You typed your current password wrong'
	                    }
	                }
	            },
	            new_password: {
	                validators: {
						notEmpty: {
	                        message: 'Please enter a new password'
	                    },
	                    identical: {
	                        field: 'confirm_password',
	                        message: 'The password and its confirm are not the same'
	                    },
	                    stringLength: {
	                        min: 6,
	                        max: 16,
	                        message: 'The password must be more than 6 and less than 16 characters long'
	                    }
	                }
	            },
				confirm_password: {
	                validators: {
	                    notEmpty: {
	                        message: 'Please re enter your new password'
	                    },
		                identical: {
		                    field: 'new_password',
		                    message: 'The password and its confirm are not the same'
		                }
	                }
	            }
	        }
	    }).on('status.field.bv', function(e, data) {
			data.bv.disableSubmitButtons(false);
	    }).on('success.form.bv', function(e, data) {
	        e.preventDefault();

	        $('#skip_password').attr('disabled', 'disabled');
	        $('.loader').removeClass('hide');
	        var $form = $(e.target);
	        var bv = $form.data('bootstrapValidator');
	        $.post($form.attr('action'), $form.serialize(), function(result) {
	            if ( JSON.stringify(result) == 1 ) {
					$('#passwordMsg').html('<span class="text-success">Your password has been changed successfully.</span>');
					$('#skip_password').text('Go to Twitter CRM');
					$('#skip_password').removeAttr('disabled');
					$('#skip_password').attr('id', 'gotocrm');
					$('#gotocrm').on('click', function() {
						$('.loader').removeClass('hide');
						window.location.href = siteurl + "twitter-crm/";
					});
				} else {
					$('#passwordMsg').html('<span class="text-danger">Unable to update your password, please try again.</span>');
					$('#skip_password').removeAttr('disabled');
				}
				$('.loader').addClass('hide');
				$form[0].reset();
	        }, 'json');
		});
	}
	// End of the script

	// Skipping password change
	if ( $('#skip_password').length > 0 ) {
		$('#skip_password').on('click', function(e){
			e.preventDefault();

			$('.loader').removeClass('hide');
	        var $form = $('#change-password-form');
	        $.post($form.attr('action'), 'skip=true', function(result) {
	            if ( JSON.stringify(result) == 2 ) {
					window.location.href = siteurl + "twitter-crm/";
				} else {
					$('#passwordMsg').html('<span class="text-danger">Unable to skip change password, please try again.</span>');
					$('.loader').removeClass('hide');
				}
				$form[0].reset();
	        }, 'json');
		});
	}
	// End of the script

	// Set toggle button data-rel in twitter-crm.php
	if ( $('.alterbiotalk').length > 0 ) {
		$('.alterbiotalk').data('rel', bioTweets);
	}
	// End of the script

	// Custom nano scrollbar
	if ( $('.nano').length > 0 ) {
		$('.nano').height($(window).height() - ($('.topbar').height() + $('.group_content_topbar').outerHeight(true) + 30));
	}
	// End of the script

	// Bootstrap tooltip
	if ( $('[data-toggle="tooltip"]').length > 0 ) {
		$('[data-toggle="tooltip"]').tooltip({
			trigger: 'hover'
		});
	}
	// End of the script

	var coming_from = $('#coming_from').val();
	// Keyword Pipeline Tab for Twitter CRM in twitter-crm.php
	if ( $("#prospect-finder-pipeline").length > 0 ) {
		if ( coming_from == 'keyword' ) {
			if (busyKeywords == false) {
				busyKeywords = true;
				var filterData = $('#filterForm').serialize();
				displayExtraKeywords(limitKeywords, offsetKeywords, filterData);
			}
		}
		$('#prospect-finder-pipeline .nano').bind('scroll', function() {
			if ( $(this).scrollTop() + $(this).innerHeight() + 800 >= $(this)[0].scrollHeight ) {
				if ( !busyKeywords ) {
					busyKeywords = true;
					offsetKeywords = limitKeywords + offsetKeywords;
					var filterData = $('#filterForm').serialize();
					displayExtraKeywords(limitKeywords, offsetKeywords, filterData);
				}
			}
		});
	}
	// End of the script
	
	// Category Pipeline Tab for Twitter CRM in Twitter-crm.php
	if ( $("#category-pipeline").length > 0 ) {
		if ( coming_from == 'category' ) {
			if (busyCategory == false) {
				busyCategory = true;
				var filterData = $('#filterForm').serialize();
				displayExtraCategories(limitCategory, offsetCategory, filterData);
			}
		}
		$('#category-pipeline .nano').bind('scroll', function() {
			if ( $(this).scrollTop() + $(this).innerHeight() + 800 >= $(this)[0].scrollHeight ) {
				if ( !busyCategory ) {
					busyCategory = true;
					offsetCategory = limitCategory + offsetCategory;
					var filterData = $('#filterForm').serialize();
					displayExtraCategories(limitCategory, offsetCategory, filterData);
				}
			}
		});
	}
	// End of the script
	
	// Prospects Tab for Twitter CRM in twitter-crm.php
	if ( $("#prospects").length > 0 ) {
		if (busyProspects == false) {
			busyProspects = true;
			var filterData = $('#filterForm').serialize();
			displayExtraProspects(limitProspects, offsetProspects, filterData);
		}
		$('#prospects .nano').bind('scroll', function() {
			if ( $(this).scrollTop() + $(this).innerHeight() + 800 >= $(this)[0].scrollHeight ) {
				if ( !busyProspects ) {
					busyProspects = true;
					offsetProspects = limitProspects + offsetProspects;
					var filterData = $('#filterForm').serialize();
					displayExtraProspects(limitProspects, offsetProspects, filterData);
				}
			}
		});
	}
	// End of the script

	// Followers Tab for Twitter CRM in twitter-crm.php
	if ( $("#existing-followers").length > 0 ) {
		$('#existing-followers .nano').bind('scroll', function() {
			if ( $(this).scrollTop() + $(this).innerHeight() + 800 >= $(this)[0].scrollHeight ) {
				if ( !busyFollowers ) {
					busyFollowers = true;
					offsetFollowers = limitFollowers + offsetFollowers;
					var filterData = $('#filterForm').serialize();
					displayExtraFollowers(limitFollowers, offsetFollowers, filterData);
				}
			}
		});
	}
	// End of the script

	// Websites Tab for Twitter CRM in twitter-crm.php
	if ( $("#show-websites").length > 0 ) {
		$('#show-websites .nano').bind('scroll', function() {
			if ( $(this).scrollTop() + $(this).innerHeight() + 800 >= $(this)[0].scrollHeight ) {
				if ( !busyWebsites ) {
					busyWebsites = true;
					offsetWebsites = limitWebsites + offsetWebsites;
					displayExtraWebsites(limitWebsites, offsetWebsites);
				}
			}
		});
	}
	// End of the script

	// Prospects Tab for Twitter CRM in twitter-crm.php
	if ( $("#unfollow").length > 0 ) {
		$('#unfollow .nano').bind('scroll', function() {
			if ( $(this).scrollTop() + $(this).innerHeight() + 500 >= $(this)[0].scrollHeight ) {
				if ( !busyUnfollow ) {
					busyUnfollow = true;
					offsetUnfollow = limitUnfollow + offsetUnfollow;
					displayExtraUnfollow(limitUnfollow, offsetUnfollow);
				}
			}
		});
	}
	// End of the script

	// Responses Tab for Lead Responses in lead-responses.php
	if ( $("#responses").length > 0 ) {
		if (busyResponses == false) {
			busyResponses = true;
			displayExtraResponses(limitResponses, offsetResponses);
		}
		$('#responses .nano').bind('scroll', function() {
			if ( $(this).scrollTop() + $(this).innerHeight() + 500 >= $(this)[0].scrollHeight ) {
				if ( !busyResponses ) {
					busyResponses = true;
					offsetResponses = limitResponses + offsetResponses;
					displayExtraResponses(limitResponses, offsetResponses);
				}
			}
		});
	}
	// End of the script
	
	// Set Talks About Tags in Keyword Search Page in keyword-search.php
	if ( $('#txt_talk_about_tags').val() != '' && $('#txt_talk_about_tags').val() != undefined ) {
		var talkabout_total_data = $('#txt_talk_about_tags').val();
		var talkabout_exist_tags = talkabout_total_data.split(",");
		if ( $('#div_talk_about_tags').html() == 'Loading...' || $('#div_talk_about_tags').html() == 'No Keywords.' ) {
			$('#div_talk_about_tags').html('');
		}
		var readonly = $('#frm_keyword_pipeline').attr('class');
		for ( var i = 0; i < talkabout_exist_tags.length; i++ ) {
			var html = '<span id="div_talk_about_tags_' + talkabout_exist_tags[i] + '" class="tag-wrap"><i class="fa fa-tag"></i>&nbsp;<span class="tag-text">' + talkabout_exist_tags[i] + '</span>';
			if ( readonly != 'readonly' ) {
				html += '<a title="Remove" href="javascript:remove_tag(\'txt_talk_about_tags\', \'div_talk_about_tags\', \'' + talkabout_exist_tags[i] + '\')"><i class="fa fa-times"></i></a>';
			} else {
				html += '<a><i class="fa fa-times"></i></a>';
			}
			html += '</span>';
			$("#div_talk_about_tags").append(html);	
		}
	} else {
		$('#div_talk_about_tags').html('No Keywords.');
	}
	// End of the script
	
	// Set Influencers on Category Search Page in category-search.php
	if ( $('#influencer_screenname').length > 0 ) {
		var readonly = $('#catReadonly').val();
		var influencers = $('#influencer_screenname').val().split(',');
		var catId = $('.category:checked').val();
		if ( catId == 'own' ) { catId = 0; }
		$.ajax({
			type: 'GET',
			url: siteurl+'app-ajax/?case=GetInfluencersByCatId',
			data: {'catId':catId},
			success: function(data) {
				if ( data == '' ) {
					if ( catId == 0 ) {
						$('#div_category_tags').html('<label for="category">Enter Influencers Name:</label><ul id="myInfluencers" name="myInfluencers[]"></ul><small>Hit \'Enter/Tab/Comma/Space\' after writing an Influencer Name (Max 10 Influencers).</small>');
						$('#myInfluencers').tagit({
							maxTags		 : 10,
							triggerKeys  : ['enter'],
							select		 : true
						});
					} else {
						$('#div_category_tags').html('');
					}
				} else {
					$('#div_category_tags').html('');
					var infData = data.split(',');
					if ( catId == 0 ) {
						var InitTags = new Array(); 
					}
					for ( var i=0; i < infData.length; i++ ) {
						var sepIdName = infData[i].split('@');
						if ( catId == 0 ) {
							InitTags.push('@'+sepIdName[1]);
						} else {
							if (influencers.indexOf(sepIdName[0]) != -1 ) {
								if ( readonly == 'readonly' ) {
									$('#div_category_tags').append('<a disabled="disabled" rel="' + sepIdName[0] + '" class="btn btn-info btn-sm influencer-btn"> +' + sepIdName[1] + ' </a> ');
								} else {
									$('#div_category_tags').append('<a rel="' + sepIdName[0] + '" class="btn btn-info btn-sm influencer-btn"> +' + sepIdName[1] + ' </a> ');
								}
							} else {
								if ( readonly == 'readonly' ) {
									$('#div_category_tags').append('<a disabled="disabled" rel="' + sepIdName[0] + '" class="btn btn-default btn-sm influencer-btn"> +' + sepIdName[1] + ' </a> ');
								} else {
									$('#div_category_tags').append('<a rel="' + sepIdName[0] + '" class="btn btn-default btn-sm influencer-btn"> +' + sepIdName[1] + ' </a> ');
								}
							}
						}
					}
					if ( catId == 0 ) {
						$('#div_category_tags').html('<label for="category">Enter Influencers name:</label><ul id="myInfluencers" name="myInfluencers[]"></ul><small>Hit \'Enter/Tab/Comma/Space\' after writing an Influencer Name (Max 10 Influencers).</small>');
						if ( readonly == 'readonly' ) {
							$('#myInfluencers').tagit({
								maxTags		 : 10,
								tagSource	 : InitTags,
								initialTags  : InitTags,
								triggerKeys  : ['enter'],
								select		 : true,
								allowNewTags : false,
							});
							/*$('.tagit-close').click(function(e) {
                                return false;
                            });*/
						} else {
							$('#myInfluencers').tagit({
								maxTags		 : 10,
								initialTags  : InitTags,
								triggerKeys  : ['enter'],
								select		 : true
							});
						}
					}
				}
			}
		});
	}
	// End of the script

	// Hide loader after 1 sec on page load in twitter-crm.php
	if ( $('.signup-loading').length > 0 ) {
		setTimeout(function() {
			if ( ! $('.signup-loading').hasClass('hide') ) {
				$('.signup-loading').addClass('hide');
			}
		}, 4000);
	}
	// End of the script
	
	// Hide loader after 1 sec on page load in twitter-crm.php
	if ( $('.crm-loading').length > 0 ) {
		setTimeout(function() {
			if ( ! $('.crm-loading').hasClass('hide') ) {
				$('.crm-loading').addClass('hide');
			}
		}, 1000);
	}
	// End of the script
	
	// Disabled/Enabled Follows & Tals About filter field in twitter-crm.php
	var coming_from = $('#coming_from').val();
	if ( coming_from == 'category' ) {
		$('#follows').removeAttr('disabled').parents('.form-group').removeClass('hide');
		$('#talks_about').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
	} else if ( coming_from == 'keyword' ) {
		$('#talks_about').removeAttr('disabled').parents('.form-group').removeClass('hide');
		$('#follows').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
	} else {
		$('#talks_about').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
		$('#follows').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
	}
	// End of the script

	// Reset Search Results nano scrollbar on loading in twitter-crm.php
	if ( $('.tab-pane.active').attr('id') == 'prospect-finder-pipeline' ) {
		$('.tab-pane.active .nano').height($(window).height()-($('.topbar').outerHeight(true)+$('.group_content_topbar').outerHeight(true)+$('#twitter-tab').outerHeight(true)+$('.buttons-actions').outerHeight(true)+40));
	} else {
		$('.tab-pane.active .nano').height($(window).height()-($('.topbar').outerHeight(true)+$('.group_content_topbar').outerHeight(true)+$('#twitter-tab').outerHeight(true)+$('.buttons-actions').outerHeight(true)+85));
	}
	// End of the script

	// Registration page tutorials video
	if ( $('#signup').length > 0 ) {
		jwplayer("signup").setup({
	        file: siteurl + "tutorials/signup.mp4",
	        width: "100%",
	        aspectratio: "16:9",
	        stretching: "exactfit",
	        autostart: true,
	        repeat: true,
	    });
	}
	if ( $('#twitter-account').length > 0 ) {
		jwplayer("twitter-account").setup({
	        file: siteurl + "tutorials/twitter-account.mp4",
	        width: "100%",
	        aspectratio: "16:9",
	        stretching: "exactfit",
	        repeat: true,
	    });
	}
	if ( $('#twitter-app').length > 0 ) {
		jwplayer("twitter-app").setup({
	        file: siteurl + "tutorials/twitter-app.mp4",
	        width: "100%",
	        aspectratio: "16:9",
	        stretching: "exactfit",
	        repeat: true,
	    });
	}
	if ( $('#access-token').length > 0 ) {
		jwplayer("access-token").setup({
	        file: siteurl + "tutorials/access-token.mp4",
	        width: "100%",
	        aspectratio: "16:9",
	        stretching: "exactfit",
	        repeat: true,
	    });
	}
	if ( $('#clkbnk-account').length > 0 ) {
		jwplayer("clkbnk-account").setup({
	        file: siteurl + "tutorials/clkbnk-account.mp4",
	        width: "100%",
	        aspectratio: "16:9",
	        stretching: "exactfit",
	        repeat: true,
	    });
	}
	if ( $('#hopcode').length > 0 ) {
		jwplayer("hopcode").setup({
	        file: siteurl + "tutorials/hopcode.mp4",
	        width: "100%",
	        aspectratio: "16:9",
	        stretching: "exactfit",
	        repeat: true,
	    });
	}
	// End of the script

	// Registration page tab
	if ( $('#signupTab').length > 0 ) {
		$('#signupTab a[data-toggle="tab"]').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');
			if ( $(this).attr('href') == '#first' ) {
				var video = document.getElementById("signup");
				playTutorial(video);
			}

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	// End of the script
	
	// Signup Steps
	if ( $('#GoToStep1_1').length > 0 ) {
		$('#GoToStep1_1').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			$('#signupTab a[href="#first_1"]').attr('data-toggle', 'tab');

			$('#signupTab a[href="#first"]').parent('li').addClass('hide');
			$('#signupTab a[href="#first_1"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#BackToStep1').length > 0 ) {
		$('#BackToStep1').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			$('#signupTab a[href="#first"]').attr('data-toggle', 'tab');

			var video = document.getElementById("signup");
			playTutorial(video);

			$('#signupTab a[href="#first_1"').parent('li').addClass('hide');
			$('#signupTab a[href="#first"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#GoToStep2').length > 0 ) {
		$('#GoToStep2').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			var validStep1 = false, validStep2 = false, validStep3 = false, validStep4 = false;
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			var email = $('#customer_email').val(),	fname = $('#customer_firstname').val(), 
				lname = $('#customer_lastname').val(), uname = $('#customer_username').val();

			if ( email != false && re.test(email) ) {
				$.ajax({
					type 		: 'POST',
					dataType 	: "json",
					async		: false,
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=CheckUsersDataExist',
					data		: "customer_email=" + email,
					success: function(data) {
						if ( data.valid == true ) {
							validStep1 = true;
						} else {
							validStep1 = false;
						}
					}
				});
			} else {
				validStep1 = false;
			}
			if ( fname != false ) {
				validStep2 = true;
			} else {
				validStep2 = false;
			}
			if ( lname != false ) {
				validStep3 = true;
			} else {
				validStep3 = false;
			}
			if ( uname != false && uname.length >= 6 && uname.length <= 30 && uname.match(/^[A-Za-z0-9_]+$/i) ) {
				$.ajax({
					type 		: 'POST',
					dataType 	: "json",
					async		: false,
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=CheckUsersDataExist',
					data		: "customer_username=" + uname,
					success: function(data) {
						if ( data.valid == true ) {
							validStep4 = true;
						} else {
							validStep4 = false;
						}
					}
				});
			} else {
				validStep4 = false;
			}
			if ( validStep1 == true && validStep2 == true && validStep3 == true && validStep4 == true ) {
				$('#signupTab a[href="#first"]').attr('data-toggle', 'tab');
				$('#signupTab a[href="#first_1"]').attr('data-toggle', 'tab');

				$('#signupTab a[href="#first"]').parent('li').removeClass('hide');
				$('#signupTab a[href="#first_1"]').parent('li').addClass('hide');

				$('#signupTab a[href="#second"]').tab('show');
				
				$('#post_payment_form').data('bootstrapValidator').resetForm();
			} else {
				$('#commit').click();
			}

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#NoTwitterAccount').length > 0 ) {
		$('#NoTwitterAccount').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			var video = document.getElementById("twitter-account");
			playTutorial(video);

			$('#signupTab a[href="#second"], #signupTab a[href="#second_2"], #signupTab a[href="#second_3"], #signupTab a[href="#second_4"]').parent('li').addClass('hide');
			$('#signupTab a[href="#second_1"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#HaveTwitterAccount, #GoToStep2_2, #BackToStep2_2').length > 0 ) {
		$('#HaveTwitterAccount, #GoToStep2_2, #BackToStep2_2').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			var video = document.getElementById("twitter-app");
			playTutorial(video);

			$('#signupTab a[href="#second"], #signupTab a[href="#second_1"], #signupTab a[href="#second_3"], #signupTab a[href="#second_4"]').parent('li').addClass('hide');
			$('#signupTab a[href="#second_2"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('.BackToStep2').length > 0 ) {
		$('.BackToStep2').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			$('#signupTab a[href="#second_1"], #signupTab a[href="#second_2"], #signupTab a[href="#second_3"], #signupTab a[href="#second_4"]').parent('li').addClass('hide');
			$('#signupTab a[href="#second"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#GoToStep2_3, #BackToStep2_3').length > 0 ) {
		$('#GoToStep2_3, #BackToStep2_3').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			var video = document.getElementById("access-token");
			playTutorial(video);

			$('#signupTab a[href="#second"], #signupTab a[href="#second_1"], #signupTab a[href="#second_2"], #signupTab a[href="#second_4"]').parent('li').addClass('hide');
			$('#signupTab a[href="#second_3"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#GoToStep2_4').length > 0 ) {
		$('#GoToStep2_4').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			$('#signupTab a[href="#second"], #signupTab a[href="#second_1"], #signupTab a[href="#second_2"], #signupTab a[href="#second_3"]').parent('li').addClass('hide');
			$('#signupTab a[href="#second_4"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#GoToStep3').length > 0 ) {
		$('#GoToStep3').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			$('#signupTab a[href="#third"]').attr('data-toggle', 'tab');
			$('#signupTab a[href="#third_1"]').attr('data-toggle', 'tab');
			$('#signupTab a[href="#third_2"]').attr('data-toggle', 'tab');

			var validStep5 = false, validStep6 = false, validStep7 = false, validStep8 = false, validStep9 = false, validStep10 = false;
			var consumer_key = $('#customer_twitter_consumer_key').val(), consumer_secret = $('#customer_twitter_consumer_secret').val(),
				screen_name = $('#customer_twitter_screenname').val(), twitter_id = $('#customer_twitter_id').val(),
				access_token = $('#customer_twitter_access_token').val(), token_secret = $('#customer_access_token_secret').val();

			if ( consumer_key != false && consumer_key.match(/^[A-Za-z0-9]+$/i) ) {
				$.ajax({
					type 		: 'POST',
					dataType 	: "json",
					async		: false,
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=CheckUsersDataExist',
					data		: "customer_twitter_consumer_key=" + consumer_key,
					success: function(data) {
						if ( data.valid == true ) {
							validStep5 = true;
						} else {
							validStep5 = false;
						}
					}
				});
			} else {
				validStep5 = false;
			}
			if ( consumer_secret != false && consumer_secret.match(/^[A-Za-z0-9]+$/i) ) {
				$.ajax({
					type 		: 'POST',
					dataType 	: "json",
					async		: false,
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=CheckUsersDataExist',
					data		: "customer_twitter_consumer_secret=" + consumer_secret,
					success: function(data) {
						if ( data.valid == true ) {
							validStep6 = true;
						} else {
							validStep6 = false;
						}
					}
				});
			} else {
				validStep6 = false;
			}
			if ( screen_name != false && screen_name.length <= 15 && screen_name.match(/^[A-Za-z0-9_]+$/i) ) {
				$.ajax({
					type 		: 'POST',
					dataType 	: "json",
					async		: false,
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=CheckUsersDataExist',
					data		: "customer_twitter_screenname=" + screen_name,
					success: function(data) {
						if ( data.valid == true ) {
							validStep7 = true;
						} else {
							validStep7 = false;
						}
					}
				});
			} else {
				validStep7 = false;
			}
			if ( twitter_id != false && twitter_id.match(/^[0-9]+$/i) ) {
				$.ajax({
					type 		: 'POST',
					dataType 	: "json",
					async		: false,
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=CheckUsersDataExist',
					data		: "customer_twitter_id=" + twitter_id,
					success: function(data) {
						if ( data.valid == true ) {
							validStep8 = true;
						} else {
							validStep8 = false;
						}
					}
				});
			} else {
				validStep8 = false;
			}
			var dashCount = access_token.split('-');
			if ( access_token != false && access_token.match(/^[A-Za-z0-9-]+$/i) && access_token.indexOf(twitter_id) != -1 && access_token.indexOf('-') != -1 && dashCount.length == 2 ) {
				$.ajax({
					type 		: 'POST',
					dataType 	: "json",
					async		: false,
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=CheckUsersDataExist',
					data		: "customer_twitter_access_token=" + access_token,
					success: function(data) {
						if ( data.valid == true ) {
							validStep9 = true;
						} else {
							validStep9 = false;
						}
					}
				});
			} else {
				validStep9 = false;
			}
			if ( token_secret != false && token_secret.match(/^[A-Za-z0-9]+$/i) ) {
				$.ajax({
					type 		: 'POST',
					dataType 	: "json",
					async		: false,
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=CheckUsersDataExist',
					data		: "customer_access_token_secret=" + token_secret,
					success: function(data) {
						if ( data.valid == true ) {
							validStep10 = true;
						} else {
							validStep10 = false;
						}
					}
				});
			} else {
				validStep10 = false;
			}

			var validCredentials = false; var twitterMsg = '';
			if ( validStep5 == true && validStep6 == true && validStep7 == true && validStep8 == true && validStep9 == true && validStep10 == true ) {
				$.ajax({
					type 		: 'POST',
					dataType 	: "json",
					async		: false,
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=CheckTwitterAuthenticData',
					data		: "consumer_key=" + consumer_key + "&consumer_secret=" + consumer_secret + "&access_token=" + access_token + "&token_secret=" + token_secret + "&screen_name=" + screen_name + "&twitter_id=" + twitter_id,
					success: function(data) {
						if ( data.valid == true ) {
							if ( data.message.id_str != twitter_id ) {
								$('#customer_twitter_id').val(data.message.id_str);
							}
							if ( data.message.screen_name != screen_name ) {
								$('#customer_twitter_screenname').val(data.message.screen_name);
							}
							twitterMsg = '';
							validCredentials = true;
						} else {
							twitterMsg = data.message;
							validCredentials = false;
						}
					}
				});
			}

			if ( validCredentials == true ) {
				$('.twitterError').parents('form-group').removeClass('has-error').addClass('has-success');
				$('.twitterError').prev('.glyphicon').removeClass('glyphicon-remove').addClass('glyphicon-ok').css('color', '#3c763d');
				$('.twitterError').addClass('hide');

				$('#signupTab a[href="#second"]').attr('data-toggle', 'tab');
				$('#signupTab a[href="#second_1"]').attr('data-toggle', 'tab');
				$('#signupTab a[href="#second_2"]').attr('data-toggle', 'tab');
				$('#signupTab a[href="#second_3"]').attr('data-toggle', 'tab');
				$('#signupTab a[href="#second_4"]').attr('data-toggle', 'tab');

				$('#signupTab a[href="#second"]').parent('li').removeClass('hide');
				$('#signupTab a[href="#second_1"], #signupTab a[href="#second_2"], #signupTab a[href="#second_3"], #signupTab a[href="#second_4"]').parent('li').addClass('hide');

				$('#signupTab a[href="#third_1"], #signupTab a[href="#third_2"], #signupTab a[href="#third_3"]').parent('li').addClass('hide');
				$('#signupTab a[href="#third"]').tab('show').parent('li').removeClass('hide');
				
				$('#post_payment_form').data('bootstrapValidator').resetForm();
			} else {
				$('#commit').click();
				$('.twitterError').parents('form-group').removeClass('has-success').addClass('has-error');
				$('.twitterError').prev('.glyphicon').removeClass('glyphicon-ok').addClass('glyphicon-remove').css('color', '#a94442');
				$('.twitterError').text(twitterMsg).removeClass('hide');
			}

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#NoClkbnkAccount').length > 0 ) {
		$('#NoClkbnkAccount').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			var video = document.getElementById("clkbnk-account");
			playTutorial(video);

			$('#signupTab a[href="#third"], #signupTab a[href="#third_2"], #signupTab a[href="#third_3"]').parent('li').addClass('hide');
			$('#signupTab a[href="#third_1"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#HaveClkbnkAccount, #GoToStep3_2, #BackToStep3_2').length > 0 ) {
		$('#HaveClkbnkAccount, #GoToStep3_2, #BackToStep3_2').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			var video = document.getElementById("hopcode");
			playTutorial(video);

			$('#signupTab a[href="#third"], #signupTab a[href="#third_1"], #signupTab a[href="#third_3"]').parent('li').addClass('hide');
			$('#signupTab a[href="#third_2"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('.BackToStep3').length > 0 ) {
		$('.BackToStep3').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			$('#signupTab a[href="#third_1"], #signupTab a[href="#third_2"], #signupTab a[href="#third_3"]').parent('li').addClass('hide');
			$('#signupTab a[href="#third"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	if ( $('#GoToStep3_3').length > 0 ) {
		$('#GoToStep3_3').on('click', function() {
			$('.signup-loading').removeClass('hide');
			pauseAllTutorials('.signup-video');

			$('#signupTab a[href="#third_1"], #signupTab a[href="#third_2"], #signupTab a[href="#third"]').parent('li').addClass('hide');
			$('#signupTab a[href="#third_3"]').tab('show').parent('li').removeClass('hide');

			setTimeout(function() {
				$('.signup-loading').addClass('hide');
			}, 1000);
		});
	}
	// End of the script

	// Signup process
	if ( $('#post_payment_form').length > 0 ) {
		$('#post_payment_form').bootstrapValidator({
			live: 'enabled',
			excluded: [':disabled'],
	        message: 'This value is not valid',
	        feedbackIcons: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },
			fields: {
				category: {
	                validators: {
	                    notEmpty: {
	                        message: 'Please select one category'
	                    }
	                }
	            },
	            customer_email: {
	                validators: {
						notEmpty: {
	                        message: 'Email Address is required and cannot be empty'
	                    },
	                    emailAddress: {
	                        message: 'The input is not a valid email address'
	                    },
						remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckUsersDataExist',
	                        message: 'The Email is already used'
	                    }
	                }
	            },
				customer_firstname: {
	                validators: {
	                    notEmpty: {
	                        message: 'First Name is required and cannot be empty'
	                    }
	                }
	            },
	            customer_lastname: {
	                validators: {
	                    notEmpty: {
	                        message: 'Last Name is required and cannot be empty'
	                    }
	                }
	            },
				customer_username: {
	                message: 'The username is not valid',
	                validators: {
	                    notEmpty: {
	                        message: 'The username is required and cannot be empty'
	                    },
	                    stringLength: {
	                        min: 6,
	                        max: 30,
	                        message: 'The username must be more than 6 and less than 30 characters long'
	                    },
	                    regexp: {
	                        regexp: /^[A-Za-z0-9_.]+$/i,
	                        message: 'Your desired username doesn\'t contain any symbols, dashes, or spaces except underscores and dots'
	                    },
	                    remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckUsersDataExist',
	                        message: 'The username is not available'
	                    }
	                }
	            },
				customer_twitter_consumer_key: {
	                validators: {
	                    notEmpty: {
	                        message: 'Consumer Key is required and cannot be empty'
	                    },
	                    regexp: {
	                        regexp: /^[A-Za-z0-9]+$/i,
	                        message: 'Your Consumer Key doesn\'t contain any symbols, dashes, or spaces'
	                    },
	                    remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckUsersDataExist',
	                        message: 'The Cosumer Key is already in use'
	                    }
	                }
	            },
				customer_twitter_consumer_secret: { 
	                validators: {
	                    notEmpty: {
	                        message: 'Consumer Secret is required and cannot be empty'
	                    },
	                    regexp: {
	                        regexp: /^[A-Za-z0-9]+$/i,
	                        message: 'Your Consumer Secret doesn\'t contain any symbols, dashes, or spaces'
	                    },
	                    remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckUsersDataExist',
	                        message: 'The Cosumer Secret is already in use'
	                    }
	                }
	            },
				customer_twitter_screenname: { 
	                validators: {
	                    notEmpty: {
	                        message: 'Screen Name is required and cannot be empty'
	                    },
	                    regexp: {
	                        regexp: /^[A-Za-z0-9_]+$/i,
	                        message: 'Your Screen Name doesn\'t contain any symbols, dashes, or spaces except underscores'
	                    },
	                    stringLength: {
	                        max: 15,
	                        message: 'Your Screen Name must be less than 15 characters long'
	                    },
	                    remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckUsersDataExist',
	                        message: 'The Screen Name is already in use'
	                    }
	                }
	            },
				customer_twitter_id: { 
	                validators: {
	                    notEmpty: {
	                        message: 'Owner ID is required and cannot be empty'
	                    },
	                    regexp: {
	                        regexp: /^[0-9]+$/i,
	                        message: 'Your Owner ID doesn\'t contain any alphabets, symbols, dashes, or spaces'
	                    },
	                    remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckUsersDataExist',
	                        message: 'The Twitter ID is already in use'
	                    }
	                }
	            },
				customer_twitter_access_token: { 
	                validators: {
	                    notEmpty: {
	                        message: 'Access Token is required and cannot be empty'
	                    },
	                    regexp: {
	                        regexp: /^[A-Za-z0-9-]+$/i,
	                        message: 'Your Access Token doesn\'t contain any symbols or spaces except dash'
	                    },
	                    remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckUsersDataExist',
	                        message: 'The Access Token is already in use'
	                    }
	                }
	            },
				customer_access_token_secret: { 
	                validators: {
	                    notEmpty: {
	                        message: 'Access Token Secret is required and cannot be empty'
	                    },
	                    regexp: {
	                        regexp: /^[A-Za-z0-9]+$/i,
	                        message: 'Your Access Token Secret doesn\'t contain any symbols, dashes, or spaces'
	                    },
	                    remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckUsersDataExist',
	                        message: 'The Access Token Secret is already in use'
	                    }
	                }
	            },
				customer_hopcode: { 
	                validators: {
	                    /*notEmpty: {
	                        message: 'Clickbank Username is required and cannot be empty'
	                    },*/
	                    regexp: {
	                        regexp: /^[A-Za-z0-9]+$/i,
	                        message: 'Your Clickbank Username doesn\'t contain any symbols, dashes, or spaces'
	                    },
	                    /*remote: {
	                        type: 'POST',
	                        url: siteurl + 'app-ajax/?case=CheckUsersDataExist',
	                        message: 'Your Clickbank Username is already in use'
	                    }*/
	                }
	            }
	        }	
		}).on('status.field.bv', function(e, data) {
			data.element.parents('.form-group').find('.twitterError').addClass('hide');
			data.bv.disableSubmitButtons(false);
	    }).on('success.form.bv', function(e, data) {
	        e.preventDefault();

	        $('.signup-loading').removeClass('hide');
	        var $form = $(e.target);
	        var bv = $form.data('bootstrapValidator');
	        $.post($form.attr('action'), $form.serialize(), function(result) {
	            if ( JSON.stringify(result) == 1 ) {
	            	$('.signup-body').html('<p class="text-center sucmsg"><i class="fa fa-check-circle"></i> Thank you for registering with Social Sonic.<br /> We have sent you an email with your login details.<br>Please check your mail and login to Social Sonic account from <a href="' + siteurl + '">here</a>.</p>');
				} else if(JSON.stringify(result) == 2){
					$('#signupMsg').html('<span class="text-success">Unable to create your profile, please try again.</span>');
				} else {
					$('#signupMsg').html('<span class="text-success">You have already registered with us, <a href="' + siteurl + '">Login here</a></span>');
				}
				setTimeout(function() {
					$('.signup-loading').addClass('hide');
				}, 1000);
	        }, 'json');
		});
	}
	// End of the script

	// Show filter button on pipeline tab
	var parentID = $('.tab-pane.active').attr('id');
	if ( parentID == 'prospect-finder-pipeline' || parentID == 'category-pipeline' ) {
		$('#SaveSearchBtn').removeClass('hide');
	}
	// End of the script
	
	// Check login sessions for authentication
	if($('#CkLogin').val() == 1){
		setInterval(function() {
			$.ajax({
				type 		: 'POST',
				dataType 	: "json",
				async		: false,
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=CheckCustomerSession',
				success: function(data) {
					if ( data.sessionCheck == 1 ) {
						$('#Ses-Expire-Msg').removeClass('hide');
						$('#login-form .close').remove();
						$('#loginModal .btn-default').remove();
						$('#loginModal').modal('show');
					}
				}		
			});	
		}, 10000);
	}
	// End of the script
	
	// Close modal reply tweet area clean
	$('#LastReplyTweetsModal').on('hidden.bs.modal', function () {
		$('TweetMsgBox').val('');
		var screenname = $('#LastScreenName').text();
		$("#TweetMsgBox").val('@' + screenname + ' ');
	})
	// End of the script
	
}); // End of document ready

// Change cancel reason to other
$(document).on('change', '#cancel_reason', function(e) {
	$('#unsubscribe_form').bootstrapValidator('updateStatus', 'other', 'NOT_VALIDATED');
	if ( $(this).val() == 'Other' ) {
		$('#other').parents('.form-group').removeClass('hide');
	} else {
		$('#other').parents('.form-group').addClass('hide');
	}
});
// End of the script

// Edit Keyword Search inline
$(document).on('click', '#EditKeywordSearch', function(e) {
	e.preventDefault();

	$('.add_tag').removeAttr('readonly');
	$('.tag-wrap').each(function(index, element) {
		var id = $(element).attr('id');
		var tagName = id.split('_');
		var tag = tagName[tagName.length - 1];
		$(element).find('a').attr({
			'href': 'javascript:remove_tag("txt_talk_about_tags", "div_talk_about_tags", "' + tag + '")',
			'title': 'Remove'
		});
	});
	$('.keyword_submit').html('<i class="fa fa-search"></i> Update Search');
});
// End of the script

// Edit Keyword Search inline
$(document).on('click', '.EditCategorySearch', function(e) {
	e.preventDefault();

	$('.category, .influencer-btn').removeAttr('disabled');
	$('#catReadonly').val('editonly');
	var InitTags = $('#myInfluencers').tagit("tags");
	$('#myInfluencers').tagit({
		maxTags		 : 10,
		tagSource	 : InitTags,
		initialTags  : InitTags,
		triggerKeys  : ['enter'],
		select		 : true,
		allowNewTags : true,
	});
	$('.category_submit').html('<i class="fa fa-search"></i> Update Search');
});
// End of the script

// Toggle Talks About & Bio fields on result in Keyword Pipeline Tab in twitter-crm.php
$(document).on('click', '.alterbiotalk', function(e) {
	e.preventDefault();

	var button = $(this);
	var rel = button.data('rel');
	if ( rel == 'bio' ) {
		$('#prospect-finder-pipeline').find('.twuserbox').each(function(index, element) {
            var tweetsTag = $(element).find('.tweets-tag').text();
			$(element).find('.bioTweetsP').html(tweetsTag);
			$('.bioIcon').addClass('hide');
			$('.talkIcon').removeClass('hide');
        });
		bioTweets = 'tweets';
	} else {
		$('#prospect-finder-pipeline').find('.twuserbox').each(function(index, element) {
            var bioTag = $(element).find('.bio-tag').text();
			$(element).find('.bioTweetsP').html(bioTag);
			$('.bioIcon').removeClass('hide');
			$('.talkIcon').addClass('hide');
        });
		bioTweets = 'bio';
	}
	button.data('rel', bioTweets);
});
// End of the script

// Refresh data from MongoDB & Load on each Tab in twitter-crm.php
$(document).on('click', '.refreshdata', function(e) {
	e.preventDefault();
	
	$('.tab-pane.active').find('.crm-loading').removeClass('hide');
	
	$('#filterForm')[0].reset();
	$('.SearchFilterBtn').removeClass('hide');
	$('#SaveSearchBtn').html('<i class="fa fa-save"></i> Save & Search').attr({
		'data-id': '',
		'data-action': ''
	});
	$("#followers, #following").slider('setValue', [0, 1000000]);
	var filterData = $('#filterForm').serialize();

	var parentID = $(this).parents('.tab-pane.active').attr('id');
	if ( parentID == 'prospect-finder-pipeline' ) {
		$('#KeywordFilterCount').html('');
		$.ajax({
			type 		: 'GET',
			dataType 	: "json",
			cache		: false,
			url 		: siteurl + 'app-ajax/?case=LoadingExtraKeywords',
			data		: "limit=15&offset=0&bioTweets=" + bioTweets + "&" + filterData,
			success 	: function(data) {
				window.busyKeywords = false;
				limitKeywords = 15;
				offsetKeywords = 0;
				if ( data.html == 'noProspect' ) {
					$('#prospect-finder-pipeline-content').html('');
					$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your keywords.<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
				} else if ( data.html == "noResult" ) {
					$('#prospect-finder-pipeline-content').html('');
					$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get result.</p></div>');
				} else if ( data.html == 'noFilter' ) {
					$('#prospect-finder-pipeline-content').html('');
					$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
				} else {
					$('#Empty_Keywords').html('');
					if ( data.html == "noMore" ) {
						if ( $('#prospect-finder-pipeline .twuserbox').length > 0 ) {
							$("#loader_Keywords").html('<p>No more prospects.</p>').show();
						}
					} else {
						GenerateKeywordsCard(data.html);
					}
				}
				$('.tab-pane.active').find('.crm-loading').addClass('hide');
				//$('#KeywordFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
			}
		});
	} else if ( parentID == 'category-pipeline' ) {
		$('#CategoryFilterCount').html('');
		$.ajax({
			type 		: 'GET',
			dataType 	: "json",
			cache		: false,
			url 		: siteurl + 'app-ajax/?case=LoadingExtraCategories',
			data		: "limit=15&offset=0&" + filterData,
			success 	: function(data) {
				window.busyCategory = false;
				limitCategory = 15;
				offsetCategory = 0;
				if ( data.html == 'apiError' ) {
					$('#category-pipeline-content').html('');
					$('#Empty_Category').html('<div class="results-box empty-msg"><p>You have exceeded the Twitter API limits.<br />Please wait for 30 min. to generate more prospects.</p></div>');
				} else if ( data.html == 'noProspect' ) {
					$('#category-pipeline-content').html('');
					$('#Empty_Category').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your selected influencer(s).<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
				} else if ( data.html == "noResult" ) {
					$('#category-pipeline-content').html('');
					$('#Empty_Category').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'category-search/">Click here</a> to create a prospect to get result.</p></div>');
				} else if ( data.html == 'noFilter' ) {
					$('#category-pipeline-content').html('');
					$('#Empty_Category').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
				} else {
					$('#Empty_Category').html('');
					if ( data.html == "noMore" ) {
						if ( $('#category-pipeline .twuserbox').length > 0 ) {
							$("#loader_Category").html('<p>No more prospects.</p>').show();
						}
					} else {
						GenerateCategoriesCard(data.html);
					}
				}
				$('.tab-pane.active').find('.crm-loading').addClass('hide');
				//$('#CategoryFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
			}
		});
	} else if ( parentID == 'prospects' ) {
		$('#SaveSearchBtn').addClass('hide');
		$('#ProspectsFilterCount').html('');
		$.ajax({
			type 		: 'GET',
			dataType 	: "json",
			cache		: false,
			url 		: siteurl + 'app-ajax/?case=LoadingExtraProspects',
			data		: "limit=15&offset=0&" + filterData,
			success 	: function(data) {
				window.busyProspects = false;
				limitProspects = 15;
				offsetProspects = 0;
				if ( data.html == "noResult" ) {
					$('#prospects-content').html('');
					$('#Empty_Prospects').html('<div class="results-box empty-msg"><p>You didn\'t create any prospect yet.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get result.</p></div>');
				} else {
					$('#Empty_Prospects').html('');
					if ( data.html == "noMore" ) {
						if ( $('#prospects .twuserbox').length > 0 ) {
							$("#loader_Prospects").html('<p>No more prospects.</p>').show();
						}
					} else {
						GenerateProspectsCard(data.html);
					}
				}
				$('.tab-pane.active').find('.crm-loading').addClass('hide');
				//$('#ProspectsFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
			}
		});
	} else if ( parentID == 'existing-followers' ) {
		$('#FollowersFilterCount').html('');
		$.ajax({
			type 		: 'GET',
			dataType 	: "json",
			cache		: false,
			url 		: siteurl + 'app-ajax/?case=LoadingExtraFollowers',
			data		: "limit=15&offset=0&" + filterData,
			success 	: function(data) {
				window.busyFollowers = false;
				limitFollowers = 15;
				offsetFollowers = 0;
				if ( data.html == "noResult" ) {
					$('#existing-followers-content').html('');
					$('#Empty_Followers').html('<div class="results-box empty-msg"><p>You don\'t have any followers.</p></div>');
				} else {
					$('#Empty_Followers').html('');
					if ( data.html == "noMore" ) {
						if ( $('#existing-followers .twuserbox').length > 0 ) {
							$("#loader_Followers").html('<p>No more followers.</p>').show();
						}
					} else {
						GenerateFollowersCard(data.html);
					}
				}
				$('.tab-pane.active').find('.crm-loading').addClass('hide');
				//$('#FollowersFilterCount').html('Showing <b>' + data.resultCount + '</b> Followers');
			}
		});
	} else if ( parentID == 'show-websites' ) {
		$('#WebsitesFilterCount').html('');
		$.ajax({
			type 		: 'GET',
			dataType 	: "json",
			cache		: false,
			url 		: siteurl+'app-ajax/?case=LoadingExtraWebsites',
			data 		: "limit=15&offset=0",
			success 	: function(data) {
				window.busyWebsites = false;
				limitWebsites = 15;
				offsetWebsites = 0;
				if ( data.html == "noResult" ) {
					$('#show-websites-content .table tbody').html('');
					$('#Empty_Websites').html('<div class="results-box empty-msg"><p>You didn\'t create any prospect yet.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get website results.</p></div>');
				} else {
					$('#Empty_Websites').html('');
					if ( data.html == "noMore" ) {
						if ( $('#show-websites-content .table tbody tr').length > 0 ) {
							$("#loader_Websites").html('<p>No more prospects.</p>').show();
						}
					} else {
						GenerateWebsiteRow(data.html);
					}
				}
				if ( $('#checkAll').is(':checked') ) {
					$('#checkAll').removeAttr('checked');
				}
				$('.tab-pane.active').find('.crm-loading').addClass('hide');
				//$('#WebsitesFilterCount').html('Showing <b>' + data.resultCount + '</b> Websites');
			}
		});
	} else if ( parentID == 'unfollow' ) {
		$('#UnFollowFilterCount').html('');
		$.ajax({
			type 		: 'GET',
			dataType 	: "json",
			cache		: false,
			url 		: siteurl + 'app-ajax/?case=LoadingExtraUnfollow',
			data		: "limit=15&offset=0&" + filterData,
			success 	: function(data) {
				window.busyUnfollow = false;
				limitUnfollow = 15;
				offsetUnfollow = 0;
				if ( data.html == "noResult" ) {
					$('#unfollow-content').html('');
					$('#Empty_Unfollow').html('<div class="results-box empty-msg"><p>You don\'t have any prospects to unfollow.</p></div>');
				} else {
					$('#Empty_Unfollow').html('');
					if ( data.html == "noMore" ) {
						if ( $('#unfollow .twuserbox').length > 0 ) {
							$("#loader_Unfollow").html('<p>No more prospects.</p>').show();
						}
					} else {
						GenerateUnfollowCard(data.html);
					}
				}
				$('.tab-pane.active').find('.crm-loading').addClass('hide');
				//$('#UnFollowFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
			}
		});
	}
	$('.tab-pane.active .nano').scrollTop(0);
});
// End of the script

// Check & Submit the Keyword Search Form in keyword-search.php
$(document).on('click', '.keyword_submit', function() {
	$('.loader').removeClass('hide');
	var talk_about_data = $('#txt_talk_about_tags').val();
	var talk_about_tags = talk_about_data.split(",");
	if ( talk_about_tags == false ) {
		$('#ProspectAboutModal').modal('show');
		$('.loader').addClass('hide');
		return false;
	} else {
		$('#frm_keyword_pipeline').submit();
	}
});
// End of the script

// Check & Submit the Category Search Form in category-search.php
$(document).on('click', '.category_submit', function() {
	$('.loader').removeClass('hide');
	var btn = $('.category_submit');
	btn.attr('disabled', 'disabled');
	var atLeastOneCatIsChecked = false;
	$('.category').each(function(index, element) {
		if ( $(element).is(':checked') ) {
			atLeastOneCatIsChecked = true;
			return false;
		}
	});
	
	if ( atLeastOneCatIsChecked == false ) {
		$('#CategoryModal').modal('show');
		$('.loader').addClass('hide');
		btn.removeAttr('disabled');
		return false;
	} else {
		var atLeastOneIsCheckedBtn = false;
		if ( $('.category:checked').val() == 'own' ) {
			if ( $('.tagit-hiddenSelect').val() != null ) {
				atLeastOneIsCheckedBtn = true;
			}
		} else {
			$('.influencer-btn').each(function(index, element) {
				if ( $(element).hasClass('btn-info') ) {
					atLeastOneIsCheckedBtn = true;
					return false;
				}
			});
		}
		if ( atLeastOneIsCheckedBtn == false ) {
			$('#InfluncerModal').modal('show');
			$('.loader').addClass('hide');
			btn.removeAttr('disabled');
			return false;
		} else {
			$('.category').removeAttr('disabled');
			if ( $('.category:checked').val() == 'own' ) {
				var myInfluencers = $('select[name="myInfluencers[]"').val();
				$.ajax({
					type 		: 'GET',
					url 		: siteurl + 'app-ajax/?case=CheckValidScreenName',
					data		: "myInfluencers=" + myInfluencers,
					success: function(data) {
						if ( data == '' ) {
							$('#frm_category_pipeline').submit();
						} else {
							$('#screenErrors').html(data + ' - invalid screen name(s)');
							$('.loader').addClass('hide');
							btn.removeAttr('disabled');
						}
					}
				});
			} else {
				$('#frm_category_pipeline').submit();
			}
		}
	}
});
// End of the script
	
// Add Tags on Keyword Search in keyword-search.php
$(document).on("keydown", '.add_tag', function(e) {
	var key;
	if ( window.event ) 
		key = window.event.keyCode;
	else 
		key = e.which;
	
	if ( key == 13 ) {
		e.preventDefault();
		
		if ( $(this).val() == false ) {
			return false;
		}

		if ( !$(this).val().match(/^[A-Za-z0-9_ -]+$/i) ) {
			$('#spacialCharModal').modal('show');
			return false;
		}
		if ( $(this).val().match(/\d\.\s+|[a-z]\)\s+|•\s+|[A-Z]\.\s+|[IVX]+\.\s+/g) ) {
			$('#spacialCharModal').modal('show');
			return false;
		}
		
		var limit = parseInt($(this).data("limit"));
		var storeid = $(this).data("storeid");
		var false_case = $(this).data("case");
		var existing_data = $('#' + storeid).val();
		var tags_container = $(this).data("tagsid");
		var is_unique = true;
		if ( existing_data.length > 0 ) {
			var existing_tags = existing_data.toLowerCase().split(",");
		} else {
			var existing_tags = [];
		}
		for ( var i = 0; i < existing_tags.length; i++ ) {
			if ( existing_tags[i] == $(this).val().toLowerCase() ) {
				is_unique = false;
			}
		}
		
		if ( existing_tags.length < limit && is_unique == true ) {
			existing_tags.push($(this).val());
			$('#' + storeid).val(existing_tags.toString());
			var container = document.getElementById(tags_container);
			if ( container.innerHTML == 'Loading...' || container.innerHTML == 'No Keywords.' ) {
				container.innerHTML = '';
			}
			var parent_span = document.createElement("span");
			parent_span.setAttribute("id", tags_container + "_" + $(this).val());
			parent_span.setAttribute("class", "tag-wrap");
			var child_icon = document.createElement("i");
			child_icon.setAttribute("class", "fa fa-tag");
			parent_span.appendChild(child_icon);
			var child_span_text = document.createElement("span");
			child_span_text.setAttribute("class", "tag-text");
			child_span_text.appendChild(document.createTextNode(' '+$(this).val()));
			parent_span.appendChild(child_span_text);
			var child_remove_link = document.createElement("a");
			child_remove_link.setAttribute("href", "javascript:remove_tag('" + storeid + "','" + tags_container + "','" + $(this).val() + "')");
			var child_remove_icon = document.createElement("i");
			child_remove_icon.setAttribute("class", "fa fa-times");
			child_remove_link.appendChild(child_remove_icon);
			parent_span.appendChild(child_remove_link);
			container.appendChild(parent_span);
			
		} else if ( existing_tags.length >= limit ) {
			
			switch ( false_case ) {
				case "talk_about_tags": {
					alert("You can’t enter more than 10 Keywords");
					break;
				}
			}

		} else {
			alert("You have already added the requested tag.");
		}
		
		$(this).val("");
		return false;
	}
});
// End of the script
	
// Add More fields for Direct Message Tab in twitter-crm.php	
$(document).on('click', ".add_another_messages", function(e) {
	e.preventDefault();
	var cust_status = $('#customer_status').val();
	if(cust_status == "trip"){
		$('#LimitedDMModal').modal('show');
		return false;
	} else {
		var dm_msg = new Array(
		'The second message would be sent 1 day after the target profile has followed you.',
		'The third message would be sent 2 days after the target profile has followed you.',
		'The fourth message would be sent 3 days after the target profile has followed you.'
		);
		var wrapper = $(this).parents('form').find('.prospect_directmessages_div');
		var x = $(this).parents('form').find('.prospect_messages').length;
		if ( x < 4 ) {
			if ( !$(this).parents('form').find('.max_directmessages').hasClass('hide') ) {
				$(this).parents('form').find('.max_directmessages').addClass('hide');
			}
			$(wrapper).append('<span class="form-rows"><textarea name="direct_messages[]" rows="5" class="form-control prospect_messages"></textarea><small class="dm-msg dm-msg-' + x + '">' + dm_msg[x-1] + '</small><a href="#" class="pull-right" onClick="remove_dm_field(this, event)">Remove</a><span>');
		}
		if ( x == 4 ) {
			if ( $(this).parents('form').find('.max_directmessages').hasClass('hide') ) {
				$("#set-direct-message .nano").scrollTop(0);
				$(this).parents('form').find('.max_directmessages').removeClass('hide');
			}
		}
	}
});
// End of the script

// Set Direct Message in twitter-crm.php
$(document).on('click', ".prospect_message_button", function() {
	var button = $(this);
	var errors = [];
	var textarea = $(this).parents('form').find('.prospect_messages');
	textarea.each(function(index, element) {
		if ( $(element).val() == false ) {
			errors.push('error');
		}
	});
	if ( errors.indexOf('error') != -1 ) {
		alert("Message area should not be blank.");
		return false;
	} else {
		button.parents(".twitter-directcomment-form").submit(function(e) {
			e.preventDefault();
			var form = $(this);
			var postData = form.serialize();
			$.ajax({
				url : siteurl+'app-ajax/?case=SaveDirectMessage',
				type: "POST",
				data : postData,
				success:function(data, textStatus, jqXHR) {
					$('.pdm_msg').addClass('text-success').html(' <i class="fa fa-check-circle"></i> You have successfully scheduled Direct Messages. ');
					setTimeout(function() {
						$('.pdm_msg').html('');
					}, 5000);
				}
			});
			
		});
	}
});
// End of the script

// Get Influencers on category change in category-search.php
$(document).on('change', '.category', function() {
	$('#influencer_screenname').val('');
	var readonly = $(this).attr('readonly');
	var catId = $(this).val();
	if ( catId != 'own' ) {
		$.ajax({
			type: 'GET',
			url: siteurl+'app-ajax/?case=GetInfluencersByCatId',
			data: {'catId':catId},
			success: function(data) {
				if ( data == '' ) {
					$('#div_category_tags').html('');
				} else {
					$('#div_category_tags').html('');
					var infData = data.split(',');
					for ( var i=0; i<infData.length; i++ ) {
						var sepIdName = infData[i].split('@');
						if ( readonly == 'readonly' ) {
							$('#div_category_tags').append('<a disabled="disabled" rel="' + sepIdName[0] + '" class="btn btn-default btn-sm influencer-btn"> +' + sepIdName[1] + ' </a> ');
						} else {
							$('#div_category_tags').append('<a rel="' + sepIdName[0] + '" class="btn btn-default btn-sm influencer-btn"> +' + sepIdName[1] + ' </a> ');
						}
					}
				}
			}
		});
	} else {
		$('#div_category_tags').html('<label for="category">Enter Influencers name:</label><ul id="myInfluencers" name="myInfluencers[]"></ul><small>Hit \'Enter/Tab/Comma/Space\' after writing an Influencer Name (Max 10 Influencers).</small>');
		$('#myInfluencers').tagit({
			maxTags		: 10,
			triggerKeys : ['enter'],
			select		: true
		});
	}
});
// End of the script

// Select/Deselect Influencers tag in category-search.php
var hidden_names = new Array();
$(document).on('click', '.influencer-btn', function(e) {
	e.preventDefault();
	
	var screenname = $(this).attr('rel');
	if ($(this).hasClass("btn-info")) {
		var newValue = "";
		var allname = $('#influencer_screenname').val();
		hidden_names = allname.split(',');
		var counter = 0;
		while ( counter < hidden_names.length ) {
			if (hidden_names[counter] == screenname) {
				//
			} else {
				if (newValue == "") {
					newValue = hidden_names[counter];
				} else {
					newValue = newValue + "," + hidden_names[counter];
				}
			}
			counter++;
		}
		$('#influencer_screenname').val(newValue);
		$(this).removeClass('btn-info').addClass('btn-default');
	} else {
		var allname = $('#influencer_screenname').val();
		if (allname == "") {
			newValue = screenname;
		} else {
			newValue = allname + "," + screenname;
		}
		$("#influencer_screenname").val(newValue);
		$(this).removeClass('btn-default').addClass('btn-info');
	}
});
// End of the script

// Twitter CRM Tab Click
$(document).on('click', '#twitter-tab a.tab-link', function(e) {
	e.preventDefault();
	
	if ( $(this).parent().hasClass('disabled') ) {
		return false; 
	}
	var cust_status = $('#customer_status').val();
	$(this).tab('show');
	$('.tab-pane.active').find('.crm-loading').removeClass('hide');
	$('#filterForm')[0].reset();
	$('.SearchFilterBtn').removeClass('hide');
	$('#SaveSearchBtn').html('<i class="fa fa-save"></i> Save & Search').attr({
		'data-id': '',
		'data-action': ''
	});
	$("#followers, #following").slider('setValue', [0, 1000000]);
	$('#twitter-tab li').addClass('disabled');
	var filterData = $('#filterForm').serialize();
	if ( $(this).attr('href') == '#prospect-finder-pipeline' ) {
		$('#KeywordFilterCount').html('');
		setTimeout(function() {
			$('#talks_about').removeAttr('disabled').parents('.form-group').removeClass('hide');
			$('#follows').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
			$('.SearchFilterBtn').removeClass('hide');
			$('#editBack').removeClass('hide');
			$('#filter-handle').removeClass('hide');
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraKeywords',
				data		: "limit=15&offset=0&bioTweets=" + bioTweets + "&" + filterData,
				success 	: function(data) {
					window.busyKeywords = false;
					limitKeywords = 15;
					offsetKeywords = 0;
					if ( data.html == 'noProspect' ) {
						$('#prospect-finder-pipeline-content').html('');
						$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your keywords.<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
					} else if ( data.html == "noResult" ) {
						$('#prospect-finder-pipeline-content').html('');
						$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get result.</p></div>');
					} else if ( data.html == 'noFilter' ) {
						$('#prospect-finder-pipeline-content').html('');
						$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
					} else {
						$('#Empty_Keywords').html('');
						if ( data.html == "noMore" ) {
							if ( $('#prospect-finder-pipeline .twuserbox').length > 0 ) {
								$("#loader_Keywords").html('<p>No more prospects.</p>').show();
							}
						} else {
							GenerateKeywordsCard(data.html);
						}
					}
					$('#LoadingKeywords').remove();
					ResetContentHeightAndScroller('prospect-finder-pipeline');
					//$('#KeywordFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
				}
			});
		}, 100);
	} else if ( $(this).attr('href') == '#category-pipeline' ) {
		if(cust_status == 'trip'){
			$('#category-pipeline-content').html('');
			$('#filter-handle').addClass('hide');
			$('#category-pipeline').find(':button').addClass('hide');
			$('#Empty_Category').html('<div class="results-box empty-msg"><p>This feature is not available to you. <a href="https://123employee.infusionsoft.com/app/orderForms/e982d81f-89ac-4837-92ba-93a500eea89d">Upgrade</a> to get the full version of SocialSonicCRM.</p></div>');
			ResetContentHeightAndScroller('category-pipeline');
		} else {
			$('#CategoryFilterCount').html('');
			setTimeout(function() {
				$('#follows').removeAttr('disabled').parents('.form-group').removeClass('hide');
				$('#talks_about').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
				$('.SearchFilterBtn').removeClass('hide');
				$('#editBack').removeClass('hide');
				$('#filter-handle').removeClass('hide');
				$.ajax({
					type 		: 'GET',
					dataType 	: "json",
					cache		: false,
					url 		: siteurl + 'app-ajax/?case=LoadingExtraCategories',
					data		: "limit=15&offset=0" + "&" + filterData,
					success 	: function(data) {
						window.busyCategory = false;
						limitCategory = 15;
						offsetCategory = 0;
						if ( data.html == 'apiError' ) {
							$('#category-pipeline-content').html('');
							$('#Empty_Category').html('<div class="results-box empty-msg"><p>You have exceeded the Twitter API limits.<br />Please wait for 30 min. to generate more prospects.</p></div>');
						} else if ( data.html == 'noProspect' ) {
							$('#category-pipeline-content').html('');
							$('#Empty_Category').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your selected influencer(s).<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
						} else if ( data.html == "noResult" ) {
							$('#category-pipeline-content').html('');
							$('#Empty_Category').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'category-search/">Click here</a> to create a prospect to get result.</p></div>');
						} else if ( data.html == 'noFilter' ) {
							$('#category-pipeline-content').html('');
							$('#Empty_Category').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
						} else {
							$('#Empty_Category').html('');
							if ( data.html == "noMore" ) {
								if ( $('#category-pipeline .twuserbox').length > 0 ) {
									$("#loader_Category").html('<p>No more prospects.</p>').show();
								}
							} else {
								GenerateCategoriesCard(data.html);
							}
						}
						$('#LoadingCategory').remove();
						ResetContentHeightAndScroller('category-pipeline');
						//$('#CategoryFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
					}
				});
			}, 100);
		}	
	} else if ( $(this).attr('href') == '#prospects' ) {
		$('#ProspectsFilterCount').html('');
		setTimeout(function() {
			$('#follows').removeAttr('disabled').parents('.form-group').addClass('hide');
			$('#talks_about').removeAttr('disabled').parents('.form-group').addClass('hide');
			$('#editBack').addClass('hide');
			$('#SaveSearchBtn').addClass('hide');
			$('#filter-handle').removeClass('hide');
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraProspects',
				data		: "limit=15&offset=0&" + filterData,
				success 	: function(data) {
					window.busyProspects = false;
					limitProspects = 15;
					offsetProspects = 0;
					if ( data.html == "noResult" ) {
						$('#prospects-content').html('');
						$('#Empty_Prospects').html('<div class="results-box empty-msg"><p>You didn\'t create any prospect yet.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get result.</p></div>');
					} else {
						$('#Empty_Prospects').html('');
						if ( data.html == "noMore" ) {
							if ( $('#prospects .twuserbox').length > 0 ) {
								$("#loader_Prospects").html('<p>No more prospects.</p>').show();
							}
						} else {
							GenerateProspectsCard(data.html);
						}
					}
					$('#LoadingProspects').remove();
					ResetContentHeightAndScroller('prospects');
					//$('#ProspectsFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
				}
			});
		}, 100);
	} else if ( $(this).attr('href') == '#existing-followers' ) {
		$('#FollowersFilterCount').html('');
		setTimeout(function() {
			$('#follows').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
			$('#talks_about').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
			$('#editBack').addClass('hide');
			$('#SaveSearchBtn').addClass('hide');
			$('#filter-handle').addClass('hide');
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraFollowers',
				data		: "limit=15&offset=0&" + filterData,
				success 	: function(data) {
					window.busyFollowers = false;
					limitFollowers = 15;
					offsetFollowers = 0;
					if ( data.html == "noResult" ) {
						$('#existing-followers-content').html('');
						$('#Empty_Followers').html('<div class="results-box empty-msg"><p>You don\'t have any followers.</p></div>');
					} else {
						$('#Empty_Followers').html('');
						if ( data.html == "noMore" ) {
							if ( $('#existing-followers .twuserbox').length > 0 ) {
								$("#loader_Followers").html('<p>No more followers.</p>').show();
							}
						} else {
							GenerateFollowersCard(data.html);
						}
					}
					$('#LoadingFollowers').remove();
					ResetContentHeightAndScroller('existing-followers');
					//$('#FollowersFilterCount').html('Showing <b>' + data.resultCount + '</b> Followers');
				}
			});
		}, 100);
	} else if ( $(this).attr('href') == '#set-direct-message' ) {
		$('#editBack').addClass('hide');
		$('#SaveSearchBtn').addClass('hide');
		$('#filter-handle').addClass('hide');
		ResetContentHeightAndScroller('set-direct-message');
	} else if ( $(this).attr('href') == '#show-websites' ) {
		if(cust_status == 'trip'){
			$('#editBack').addClass('hide');
			$('#SaveSearchBtn').addClass('hide');
			$('#filter-handle').addClass('hide');
			$('#show-websites-content').html('');
			$('#show-websites').find(":button").addClass('hide');
			$('#Empty_Websites').html('<div class="results-box empty-msg"><p>This feature is not available to you. <a href="https://123employee.infusionsoft.com/app/orderForms/e982d81f-89ac-4837-92ba-93a500eea89d">Upgrade</a> to get the full version of SocialSonicCRM.</p></div>');
			ResetContentHeightAndScroller('show-websites');
		} else {
			$('#WebsitesFilterCount').html('');
			setTimeout(function() {
				$('#editBack').addClass('hide');
				$('#SaveSearchBtn').addClass('hide');
				$('#filter-handle').addClass('hide');
				$.ajax({
					type 		: 'GET',
					dataType 	: "json",
					cache		: false,
					url 		: siteurl+'app-ajax/?case=LoadingExtraWebsites',
					data 		: "limit=15&offset=0",
					success 	: function(data) {
						window.busyWebsites = false;
						limitWebsites = 15;
						offsetWebsites = 0;
						if ( data.html == "noResult" ) {
							$('#show-websites-content .table tbody').html('');
							$('#Empty_Websites').html('<div class="results-box empty-msg"><p>You didn\'t create any prospect yet.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get website results.</p></div>');
						} else {
							$('#Empty_Websites').html('');
							if ( data.html == "noMore" ) {
								if ( $('#show-websites-content .table tbody tr').length > 0 ) {
									$("#loader_Websites").html('<p>No more prospects.</p>').show();
								}
							} else {
								GenerateWebsiteRow(data.html);
							}
						}
						if ( $('#checkAll').is(':checked') ) {
							$('#checkAll').removeAttr('checked');
						}
						$('#LoadingWebsites').remove();
						ResetContentHeightAndScroller('show-websites');
						//$('#WebsitesFilterCount').html('Showing <b>' + data.resultCount + '</b> Websites');
					}
				});
			}, 100);
		}
	} else if ( $(this).attr('href') == '#unfollow' ) {
		$('#UnFollowFilterCount').html('');
		setTimeout(function() {
			$('#editBack').addClass('hide');
			$('#SaveSearchBtn').addClass('hide');
			$('#filter-handle').addClass('hide');
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraUnfollow',
				data		: "limit=15&offset=0&" + filterData,
				success 	: function(data) {
					window.busyUnfollow = false;
					limitUnfollow = 15;
					offsetUnfollow = 0;
					if ( data.html == "noResult" ) {
						$('#unfollow-content').html('');
						$('#Empty_Unfollow').html('<div class="results-box empty-msg"><p>You don\'t have any prospects to unfollow.</p></div>');
					} else {
						$('#Empty_Unfollow').html('');
						if ( data.html == "noMore" ) {
							if ( $('#unfollow .twuserbox').length > 0 ) {
								$("#loader_Unfollow").html('<p>No more prospects.</p>').show();
							}
						} else {
							GenerateUnfollowCard(data.html);
						}
					}
					$('#LoadingUnfollow').remove();
					ResetContentHeightAndScroller('unfollow');
					//$('#UnFollowFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
				}
			});
		}, 100);
	} else if ( $(this).attr('href') == '#saved-filters' ) {
		setTimeout(function() {
			$('#editBack').addClass('hide');
			$('#SaveSearchBtn').addClass('hide');
			$('#filter-handle').addClass('hide');
			$.ajax({
				type 		: 'GET',
				dataType	: "json",
				cache		: false,
				url 		: siteurl+'app-ajax/?case=LoadingSavedFilters',
				data 		: "",
				success 	: function(data) {
					if ( data.html == "noResult" ) {
						$('#saved-filters-content .table tbody').html('');
						$('#Empty_SavedFilters').html('<div class="results-box empty-msg"><p>You didn\'t save any filter yet.</p></div>');
					} else {
						$('#Empty_SavedFilters').html('');
						$('#saved-filters-content .table tbody').html(data.html);
					}
					ResetContentHeightAndScroller('saved-filters');
				}
			});
		}, 100);
	} else {
		$('#editBack').addClass('hide');
		$('#SaveSearchBtn').addClass('hide');
		$('#filter-handle').removeClass('hide');
	}
});
// End of the script

// Filter Action btn for saved filter tab in Twitter CRM
$(document).on('click', '.FilterActionBtn', function(e) {
	e.preventDefault();

	var btn = $(this);
	var action = btn.attr('data-rel');
	var from   = btn.parent('td').attr('data-from');
	if ( action == 'view' && from == 'keyword' ) {
		$('#twitter-tab a[href="#prospect-finder-pipeline"]').tab('show');
		$('.crm-loading').removeClass('hide');
	} else if ( action == 'view' && from == 'category' ) {
		$('#twitter-tab a[href="#category-pipeline"]').tab('show');
		$('.crm-loading').removeClass('hide');
	}
	var filterID 	= btn.parent('td').attr('data-id');
	var name 		= btn.parent('td').attr('data-name');
	var bio 		= btn.parent('td').attr('data-bio');
	var tweet 		= btn.parent('td').attr('data-tweet');
	var followers 	= btn.parent('td').attr('data-followers');
	var following 	= btn.parent('td').attr('data-following');
	var follows 	= btn.parent('td').attr('data-follows');
	var location 	= btn.parent('td').attr('data-location');
	if ( action == 'view' ) {
		var Followers = followers.split(',');
		var Following = following.split(',');
		$('#filter_name').val(name);
		$('#bio_field').val(bio);
		$('#followers').slider('setValue', [parseFloat(Followers[0]), parseFloat(Followers[1])]);
		$('#following').slider('setValue', [parseFloat(Following[0]), parseFloat(Following[1])]);
		$('#location').val(location);
		if ( from == 'keyword' ) {
			$('#KeywordFilterCount').html('');
			$('#talks_about').val(tweet);
			$('.SearchFilterBtn').addClass('hide');
			$('#SaveSearchBtn').html('<i class="fa fa-save"></i> Update & Filter').attr({
				'data-id'		: filterID,
				'data-action'	: 'update'
			}).removeClass('hide');
			$('#talks_about').removeAttr('disabled').parents('.form-group').removeClass('hide');
			$('#follows').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
			$('#editBack').removeClass('hide');
			$('#filter-handle').removeClass('hide');
			var formData = 'bio_field=' + bio + '&talks_about=' + tweet + '&followers=' + followers + '&following=' + following + '&location=' + location;
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraKeywords',
				data		: "limit=15&offset=0&bioTweets=" + bioTweets + "&" + formData,
				success 	: function(data) {
					window.busyKeywords = false;
					limitKeywords = 15;
					offsetKeywords = 0;
					if ( data.html == 'noProspect' ) {
						$('#prospect-finder-pipeline-content').html('');
						$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your keywords.<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
					} else if ( data.html == "noResult" ) {
						$('#prospect-finder-pipeline-content').html('');
						$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get result.</p></div>');
					} else if ( data.html == 'noFilter' ) {
						$('#prospect-finder-pipeline-content').html('');
						$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
					} else {
						$('#Empty_Keywords').html('');
						if ( data.html == "noMore" ) {
							if ( $('#prospect-finder-pipeline .twuserbox').length > 0 ) {
								$("#loader_Keywords").html('<p>No more prospects.</p>').show();
							}
						} else {
							GenerateKeywordsCard(data.html);
						}
					}
					$('#LoadingKeywords').remove();
					ResetContentHeightAndScroller('prospect-finder-pipeline');
					//$('#KeywordFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
				}
			});
		} else if ( from == 'category' ) {
			$('#CategoryFilterCount').html('');
			$('#follows').val(follows);
			$('.SearchFilterBtn').addClass('hide');
			$('#SaveSearchBtn').html('<i class="fa fa-save"></i> Update & Filter').attr({
				'data-id'		: filterID,
				'data-action'	: 'update'
			}).removeClass('hide');
			$('#follows').removeAttr('disabled').parents('.form-group').removeClass('hide');
			$('#talks_about').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
			$('#editBack').removeClass('hide');
			$('#filter-handle').removeClass('hide');
			var formData = 'bio_field=' + bio + '&follows=' + follows + '&followers=' + followers + '&following=' + following + '&location=' + location;
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraCategories',
				data		: "limit=15&offset=0&" + formData,
				success 	: function(data) {
					window.busyCategory = false;
					limitCategory = 15;
					offsetCategory = 0;
					if ( data.html == 'apiError' ) {
						$('#category-pipeline-content').html('');
						$('#Empty_Category').html('<div class="results-box empty-msg"><p>You have exceeded the Twitter API limits.<br />Please wait for 30 min. to generate more prospects.</p></div>');
					} else if ( data.html == 'noProspect' ) {
						$('#category-pipeline-content').html('');
						$('#Empty_Category').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your selected influencer(s).<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
					} else if ( data.html == "noResult" ) {
						$('#category-pipeline-content').html('');
						$('#Empty_Category').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'category-search/">Click here</a> to create a prospect to get result.</p></div>');
					} else if ( data.html == 'noFilter' ) {
						$('#category-pipeline-content').html('');
						$('#Empty_Category').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
					} else {
						$('#Empty_Category').html('');
						if ( data.html == "noMore" ) {
							if ( $('#category-pipeline .twuserbox').length > 0 ) {
								$("#loader_Category").html('<p>No more prospects.</p>').show();
							}
						} else {
							GenerateCategoriesCard(data.html);
						}
					}
					$('#LoadingCategory').remove();
					ResetContentHeightAndScroller('category-pipeline');
					//$('#CategoryFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
				}
			});
		}
	} else if ( action == 'edit' ) {
		$.ajax({
			type 		: 'GET',
			dataType	: "json",
			cache		: false,
			url 		: siteurl + 'app-ajax/?case=GetSingleFilter',
			data 		: "filterID=" + filterID + "&action=" + action,
			success 	: function(response) {
				var Followers = response.field.Followers.split(',');
				var Following = response.field.Following.split(',');
				$('#filter_name').val(response.field.Filter_Name);
				$('#bio_field').val(response.field.Bio);
				$('#followers').slider('setValue', [parseFloat(Followers[0]), parseFloat(Followers[1])]);
				$('#following').slider('setValue', [parseFloat(Following[0]), parseFloat(Following[1])]);
				$('#location').val(response.field.Location);
				if ( response.field.Filter_From == 'keyword' ) {
					$('#talks_about').val(response.field.Talks_About).removeAttr('disabled').parents('.form-group').removeClass('hide');
					$('#follows').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
				} else if ( response.field.Filter_From == 'category' ) {
					$('#follows').val(response.field.Follows).removeAttr('disabled').parents('.form-group').removeClass('hide');
					$('#talks_about').attr('disabled', 'disabled').parents('.form-group').addClass('hide');
				}
				$('#editBack').addClass('hide');
				$('#filter-handle').addClass('hide');
				$('.SearchFilterBtn').addClass('hide');
				$('#SaveSearchBtn').html('<i class="fa fa-save"></i> Update Filter').attr({
					'data-id'		: response.field.Filter_ID,
					'data-action'	: 'update'
				}).removeClass('hide');
				$('#FilterModal').modal('show');
			}
		});
	} else if ( action == 'remove' ) {
		$('#remove_filter_yes').attr({
			'data-id'	: filterID,
			'data-rel'	: action
		});
		$('#RemoveFilterModal').modal('show');
	}
});

$(document).on('click', '#remove_filter_yes', function(e) {
	e.preventDefault();

	var btn = $(this);
	var filterID = btn.attr('data-id');
	var action = btn.attr('data-rel');
	$.ajax({
		type 		: 'GET',
		dataType	: "json",
		async		: false,
		cache		: false,
		url 		: siteurl + 'app-ajax/?case=GetSingleFilter',
		data 		: "filterID=" + filterID + "&action=" + action,
		success 	: function(data) {
			if ( data.code == 3 ) {
				$('a[href="#saved-filters"]').click();
				$('#RemoveFilterModal').modal('hide');
				$('#saved-filters-content').prepend('<div id="filterRemoveMsg" class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Saved filter is successfully removed, you can save upto 5 filters.</div>');
				setTimeout(function() {
					$('#filterRemoveMsg').remove();
				}, 2000);
			}
		}
	});
});
// End of the script

// Single Initiate Nurturing from Pipeline, Prospect Finder Tab on Twitter CRM
$(document).on('click', '.unfollow_prospect', function(e) {
	e.preventDefault();
	
	var btn = $(this);
	var prospect_id = $('#prospect_id').val();
	var userid = btn.data('userid');
	var screenname = btn.data('screenname');
	$.ajax({
		type: 'GET',
		url: siteurl+'app-ajax/?case=UnfollowProspectFunc',
		data: {
			'prospect_id' : prospect_id,
			'userid' 	  : userid,
			'screenname'  : screenname
		},
		success: function(data) {
			if ( data == 1 ) {
				btn.parents('.twuserbox').addClass('animatedBorder').fadeOut(500);
				$('.unflwmessage').text(screenname + ' is successfully unfollowed!').removeClass('text-danger').addClass('text-success');
				setTimeout(function() {
					btn.parents('.twuserbox').remove();
				}, 500);
				setTimeout(function() {
					if ( $('.tab-pane.active').find('.twuserbox').length == 0 ) {
						$('.tab-pane.active').find('.refreshdata').click();
					}
				}, 1000);
				setTimeout(function() {
					$('.unflwmessage').text('').removeClass('text-success');
				}, 5000);
			} else {
				$('.unflwmessage').text('Something went wrong, try again!').removeClass('text-success').addClass('text-danger');
				btn.removeAttr('disabled');
				setTimeout(function() {
					$('.unflwmessage').text('').removeClass('text-danger');
				}, 5000);
			}
		}
	});
});
// End of the script

// Remove Prospects from Pipeline
$(document).on('click', '.remove_prospect_btn', function(e) {
	e.preventDefault();

	var btn = $(this);
	var userid = btn.data('userid');
	var custid = btn.data('custid');
	$('#remove_prospect_yes').attr('data-userid', userid);
	$('#remove_prospect_yes').attr('data-custid', custid);
	$('#RemoveProspectModal').modal('show');
});

$(document).on('click', '#remove_prospect_yes', function(e) {
	e.preventDefault();

	var keyCount = $('#KeywordFilterCount b');
	var catCount = $('#CategoryFilterCount b');
	var btn = $(this);
	var userid = btn.attr('data-userid');
	var custid = btn.attr('data-custid');
	var itemBox = $('.remove_prospect_btn[data-userid="' + userid + '"][data-custid="' + custid + '"]');
	$.ajax({
		type: 'GET',
		url: siteurl+'app-ajax/?case=RemoveProspectsPipeline',
		data: {
			'userid'  : userid,
			'custid'  : custid,
		},
		success: function(data) {
			$('#RemoveProspectModal').modal('hide');
			if ( data == 1 ) {
				itemBox.parents('.twuserbox').addClass('animatedBorderRemove').fadeOut(500);
				$('.twmessage').text('Prospect is successfully removed.').removeClass('text-danger').addClass('text-success');
				var parentID = $('.tab-pane.active').attr('id');
				if ( parentID == 'prospect-finder-pipeline' ) {
					keyCount.text(keyCount.text()-1);
				} else if ( parentID == 'category-pipeline' ) {
					catCount.text(catCount.text()-1);
				}
				setTimeout(function() {
					itemBox.parents('.twuserbox').remove();
				}, 500);
				setTimeout(function() {
					if ( $('.tab-pane.active').find('.twuserbox').length == 0 ) {
						if ( $('#SaveSearchBtn').attr('data-action') == 'update' ) {
							$('#SaveSearchBtn').click();
						} else {
							$('.tab-pane.active').find('.refreshdata').click();
						}
					}
				}, 1000);
				setTimeout(function() {
					$('.twmessage').text('').removeClass('text-success');
				}, 5000);
			} else {
				$('.twmessage').text('Something went wrong, try again!').removeClass('text-success').addClass('text-danger');
				itemBox.removeAttr('disabled');
				setTimeout(function() {
					$('.twmessage').text('').removeClass('text-danger');
				}, 5000);
			}
		}
	});
});
// End of the script
	
// Single Initiate Nurturing from Pipeline, Prospect Finder Tab on Twitter CRM
$(document).on('click', '.initiate_nurture', function(e) {
	e.preventDefault();
	
	$('.initiate_nurture').attr('disabled','disabled');
	var keyCount = $('#KeywordFilterCount b');
	var catCount = $('#CategoryFilterCount b');
	var btn = $(this);
	var prospect_id = $('#prospect_id').val();
	var userid = btn.data('userid');
	var screenname = btn.data('screenname');
	var tweetid = btn.data('tweetid');
	$.ajax({
		type: 'GET',
		url: siteurl+'app-ajax/?case=InitiateNurturingFunc',
		data: {
			'prospect_id' : prospect_id,
			'userid' 	  : userid,
			'screenname'  : screenname,
			'tweetid' 	  : tweetid
		},
		success: function(data) {
			if ( data == 1 ) {
				btn.parents('.twuserbox').addClass('animatedBorder').fadeOut(500);
				$('.twmessage').text(screenname + ' initiate nurtured successfully!').removeClass('text-danger').addClass('text-success');
				var parentID = $('.tab-pane.active').attr('id');
				if ( parentID == 'prospect-finder-pipeline' ) {
					keyCount.text(keyCount.text()-1);
				} else if ( parentID == 'category-pipeline' ) {
					catCount.text(catCount.text()-1);
				}
				setTimeout(function() {
					btn.parents('.twuserbox').remove();
				}, 500);
				setTimeout(function() {
					if ( $('.tab-pane.active').find('.twuserbox').length == 0 ) {
						if ( $('#SaveSearchBtn').attr('data-action') == 'update' ) {
							$('#SaveSearchBtn').click();
						} else {
							$('.tab-pane.active').find('.refreshdata').click();
						}
					}
				}, 800);
				setTimeout(function() {
					$('.twmessage').text('').removeClass('text-success');
				}, 5000);
			} else {
				$('.twmessage').text('Something went wrong, try again!').removeClass('text-success').addClass('text-danger');
				btn.removeAttr('disabled');
				setTimeout(function() {
					$('.twmessage').text('').removeClass('text-danger');
				}, 5000);
			}
			$('.initiate_nurture').removeAttr('disabled');
		}
	});
});
// End of the script
	
// Send Direct Message from Prospects Tab on Twitter CRM
$(document).on('click', '.directmessage', function(e) {
	e.preventDefault();
	
	var atLeastOneIsChecked = false;
	$('.direct-check').each(function(index, element) {
		if ( $(element).is(':checked') ) {
			atLeastOneIsChecked = true;
			return false;
		}
	});
	var boxCount = $('.direct-box').length;
	if ( boxCount == 0 ) {
		$('.LimitProspects').addClass('hide');
		$('.MessageForm').addClass('hide');
		$('.TweetMessageForm').addClass('hide');
		$('.NoCheckedProspects').addClass('hide');
		$('.NoProspects').removeClass('hide');
		
		$('#DirectMessageCRM').modal('show');
		return false;
	} else if ( atLeastOneIsChecked == false ) {
		$('.LimitProspects').addClass('hide');
		$('.MessageForm').addClass('hide');
		$('.TweetMessageForm').addClass('hide');
		$('.NoProspects').addClass('hide');
		$('.NoCheckedProspects').removeClass('hide');
		
		$('#DirectMessageCRM').modal('show');
		return false;
	} else {
		$('.LimitProspects').addClass('hide');
		$('.TweetMessageForm').addClass('hide');
		$('.NoCheckedProspects').addClass('hide');
		$('.NoProspects').addClass('hide');
		$('.MessageForm').removeClass('hide');
		
		$('#DirectMessageCRM').modal('show');
	}
});
// End of the script
	
// Submit Direct Message from Prospects Tab on Twitter CRM
$(document).on('click', '#message_submit', function() {
	var userids = new Array();
	var screennames = new Array();
	$('.direct-check').each(function(index, element) {
		if ( $(element).is(':checked') ) {
			userids.push($(element).data('userid'));
			screennames.push($(element).data('screenname'));
		}
	});
	var message = $('#message').val();
	if ( message == false ) {
		alert('Please enter a message!');
		return false;
	} else {
		$.ajax({
			type: 'GET',
			url: siteurl+'app-ajax/?case=SingleDirectMessage',
			data: {
				'userids' 	  : userids,
				'screennames' : screennames,
				'message'	  : message
			},
			success: function(data) {
				if ( data == 0 ) {
					$('.dmmessage').removeClass('text-success').addClass('text-danger').html('Something wrong, try again!');
				} else {
					$('#message').val('');
					$('.dmmessage').removeClass('text-danger').addClass('text-success').html('Your message has been successfully sent!');
					setTimeout(function(){
						$('.dmmessage').removeClass('text-danger').removeClass('text-success').html('');
						$('#DirectMessageCRM').modal('hide');
					},2000);
				}
			}
		});
	}
});
// End of the script
	
// Send Tweet Message from Prospects Tab on Twitter CRM
$(document).on('click', '.sendtweet', function(e) {
	e.preventDefault();
	
	$('.post_msg').html('');
	var role = $(this).data('role');
	var atLeastOneIsChecked = false;
	$(this).parents('.tab-pane.active').find('.direct-check').each(function(index, element) {
		if ( $(element).is(':checked') ) {
			atLeastOneIsChecked = true;
			return false;
		}
	});
	var screen_names = new Array();
	$(this).parents('.tab-pane.active').find('.direct-check').each(function(index, element) {
		if ( $(element).is(':checked') ) {
			screen_names.push($(element).data('screenname'));
		}
	});
	if ( screen_names.length > 0 ) {
		$('.jqEasyCounterMsg').remove();
		var longest = screen_names.reduce(function (a, b) { return a.length > b.length ? a : b; });
		var longestChar = '@' + longest + ' ';
		$('#post_message').jqEasyCounter({
			'maxChars': (140 - longestChar.length),
			'maxCharsWarning': 140 - (longestChar.length + 10)
		});
	}
	
	var boxCount = $('.tab-pane.active').find('.direct-check:checked').length;
	if ( atLeastOneIsChecked == false ) {
		$('.LimitProspects').addClass('hide');
		$('.MessageForm').addClass('hide');
		$('.TweetMessageForm').addClass('hide');
		$('.NoProspects').addClass('hide');
		$('.NoCheckedProspects').removeClass('hide');
		
		$('#DirectMessageCRM').modal('show');
		return false;
	}
	
	if ( boxCount == 0 ) {
		$('.LimitProspects').addClass('hide');
		$('.MessageForm').addClass('hide');
		$('.TweetMessageForm').addClass('hide');
		$('.NoCheckedProspects').addClass('hide');
		$('.NoProspects').removeClass('hide');
		
		$('#DirectMessageCRM').modal('show');
		return false;
	} else {
		if ( role == 'multiple' ) {
			$('.LimitProspects').addClass('hide');
			$('.MessageForm').addClass('hide');
			$('.NoCheckedProspects').addClass('hide');
			$('.NoProspects').addClass('hide');
			$('.TweetMessageForm').removeClass('hide');
			var html = '';
			//if ( boxCount == 1 ) {
				    var i = 1;
					html += '<div class="form-group">\
						<label for="post_message' + i + '">Enter Tweet ' + /*parseInt(i) +*/ ': </label>\
						<textarea name="post_message" id="post_message_' + i + '" class="form-control TweetsBox"></textarea>\
					</div>';
			/*} else if ( boxCount >= 2 && boxCount <= 5 ) {
				for ( var i = 0; i < 2; i++ ) {
					html += '<div class="form-group">\
						<label for="post_message' + i + '">Enter Tweet ' + parseInt(i+1) + ': </label>\
						<textarea name="post_message" id="post_message_' + i + '" class="form-control TweetsBox"></textarea>\
					</div>';
				}
			} else if ( boxCount >= 6 && boxCount <= 20 ) {
				for ( var i = 0; i < 5; i++ ) {
					html += '<div class="form-group">\
						<label for="post_message' + i + '">Enter Tweet ' + parseInt(i+1) + ': </label>\
						<textarea name="post_message" id="post_message_' + i + '" class="form-control TweetsBox"></textarea>\
					</div>';
				}
			} else if ( boxCount > 20 ) {
				for ( var i = 0; i < 10; i++ ) {
					html += '<div class="form-group">\
						<label for="post_message' + i + '">Enter Tweet ' + parseInt(i+1) + ': </label>\
						<textarea name="post_message" id="post_message_' + i + '" class="form-control TweetsBox"></textarea>\
					</div>';
				}
			}*/
			$('#additionalbox').html(html);
			$('.TweetsBox').jqEasyCounter({
				'maxChars': 120,
				'maxCharsWarning': 110
			});
			$('#post_message_submit').html('Schedule');
			$('#DirectMessageCRM').modal('show');
			return false;
		} else {
			$('.LimitProspects').addClass('hide');
			$('.NoCheckedProspects').addClass('hide');
			$('.NoProspects').addClass('hide');
			$('.MessageForm').addClass('hide');
			$('.TweetMessageForm').removeClass('hide');
			
			$('#additionalbox').html('<div class="form-group">\
				<label for="post_message">Enter Your Tweet: </label>\
				<textarea name="post_message" id="post_message" class="form-control TweetsBox"></textarea>\
			 </div>');
			$('#post_message').jqEasyCounter({
				'maxChars': 120,
				'maxCharsWarning': 110
			});
			$('#DirectMessageCRM').modal('show');
		}
	}
});
// End of the script
	
// Submit Tweet Message from Prospects Tab on Twitter CRM
$(document).on('click', '#post_message_submit', function() {
	var userids = new Array();
	var screennames = new Array();
	var tweetids = new Array();
	var tweetmsgs = new Array();
	$('.tab-pane.active').find('.direct-check').each(function(index, element) {
		if ( $(element).is(':checked') ) {
			userids.push($(element).data('userid'));
			screennames.push($(element).data('screenname'));
			tweetids.push($(element).data('tweetid'));
		}
	});
	
	var required = false;
	$('.TweetsBox').each(function(index, element) {
		if ( $(element).val() == '' ) {
			required = true;
			return false;
		}
	});
	$('.TweetsBox').each(function(index, element) {
		tweetmsgs.push($(element).val());
	});
	
	var role = $('.tab-pane.active').find('.sendtweet').data('role');
	if ( required == true ) {
		if ( role == 'multiple' ) {
			alert('Please enter your tweets.');
		} else {
			alert('Please enter your tweet.');
		}
		return false;
	} else {
		$.ajax({
			type: 'GET',
			url: siteurl+'app-ajax/?case=SingleTweetMessage',
			data: {
				'role'		  : role,
				'userids' 	  : userids,
				'screennames' : screennames,
				'tweetids'	  : tweetids,
				'tweetmsgs'	  : tweetmsgs
			},
			success: function(data) {
				if ( data == 1 ) {
					$('#post_message').val('');
					$('.post_msg').removeClass('text-danger').addClass('text-success').html('Your tweet has been successfully sent!');
					setTimeout(function(){
						$('#DirectMessageCRM').modal('hide');
						$('.direct-check').removeAttr('checked').parent('.results-box').removeClass('checked');
						for ( var i in userids ) {
							var uid = userids[i];
							$('.direct-check[data-userid="' + uid + '"]').parents('.results-box').css('background-color', '#b4eab5');
							$('.direct-check[data-userid="' + uid + '"]').remove();
						}
					}, 1000);
				} else if ( data == 2 ) {
					$('#post_message').val('');
					$('.post_msg').removeClass('text-danger').addClass('text-success').html('Your tweets have been successfully sheduled!');
					setTimeout(function(){
						$('#DirectMessageCRM').modal('hide');
						$('.direct-check').removeAttr('checked').parent('.results-box').removeClass('checked');
						for ( var i in userids ) {
							var uid = userids[i];
							$('.direct-check[data-userid="' + uid + '"]').parents('.results-box').css('background-color', '#b4eab5');
							$('.direct-check[data-userid="' + uid + '"]').remove();
						}
					}, 1000);
				} else {
					$('.post_msg').removeClass('text-success').addClass('text-danger').html('Something wrong, try again!');
				}
			}
		});
	}
});
// End of the script

// Check/Uncheck prospects in Prospects Tab on Twitter CRM
$(document).on('click', '.direct-check', function() {
	var role = $(this).parents('.tab-pane.active').find('.sendtweet').data('role');
	var checkCount = $('.tab-pane.active').find('.direct-check:checked').length;
	if ( role == 'multiple' ) {
		if ( ! $(this).is(':checked') ) {
			$(this).parents('.results-box').removeClass('checked');
		} else {
			if ( checkCount == 1 ) {
				if ( $(this).is(':checked') ) {
					$(this).parents('.results-box').addClass('checked');
				} else {
					$(this).parents('.results-box').removeClass('checked');
				}
			} else {
				$('.TweetMessageForm').addClass('hide');
				$('.MessageForm').addClass('hide');
				$('.NoProspects').addClass('hide');
				$('.NoCheckedProspects').addClass('hide');
				$('.LimitProspects').removeClass('hide');
				
				$('#limitSpan').text(1);
				$(this).removeAttr('checked');
				$('#DirectMessageCRM').modal('show');
			}
		}
	} else {
		if ( checkCount <= 10 ) {
			if ( $(this).is(':checked') ) {
				$(this).parents('.results-box').addClass('checked');
			} else {
				$(this).parents('.results-box').removeClass('checked');
			}
		} else {
			$('.TweetMessageForm').addClass('hide');
			$('.MessageForm').addClass('hide');
			$('.NoProspects').addClass('hide');
			$('.NoCheckedProspects').addClass('hide');
			$('.LimitProspects').removeClass('hide');
			
			$('#limitSpan').text(10);
			$(this).removeAttr('checked');
			$('#DirectMessageCRM').modal('show');
		}
	}
});
// End of the script

// Check all checkbox in Show Websites Tab on Twitter CRM
$(document).on('change', "#checkAll", function() {
	$(".allcheck").prop('checked', $(this).prop("checked"));
});
// End of the script
	
// Download selected websites in Show Websites Tab on Twitter CRM
$(document).on('click', '#downloadCSV', function() {
	var atLeastOneIsChecked = false;
	$('.allcheck').each(function(index, element) {
        if ( $(element).is(':checked') ) {
			atLeastOneIsChecked = true;
			return false;
		}
    });
	
	if ( atLeastOneIsChecked == false ) {
		$('#WebsitesModal').modal('show');
		return false;
	} else {
		$('#showWebsites').submit();
	}
});
// End of the script

//Stop to put space and backspace in tweet reply area
$(function() {
  $('body').on('keydown', '#TweetMsgBox', function(e) {
    var FldLength= $('#TweetMsgBox').val().length;
    if ((e.which === 32 || e.which === 8)&& e.target.selectionStart === txtTweetBoxLength) {
      return false;
    }  
  });
});
// End of the script

// Reply Tweet Button in last-tweets.php
$(document).on('click', '.ReplyTweet', function(e) {
	e.preventDefault();

	var tweet = $(this).data('tweet');
	var tweetid = $(this).data('tweetid');
	$('#LastReplyTweetID').val(tweetid);
	$('#LastReplyTweet').html(tweet);

	$('.jqEasyCounterMsg').remove();
	$('#TweetMsgBox').jqEasyCounter({
		'maxChars': 140,
		'maxCharsWarning': 130
	});
	$('#LastReplyTweetsModal').modal('show');
	setTimeout(function() {
		var FldLength= $('#TweetMsgBox').val().length;
		txtTweetBoxLength = FldLength;
		$('#TweetMsgBox').focus();
		$('#TweetMsgBox')[0].setSelectionRange(FldLength, FldLength);
		
	}, 500);
});
// End of the script

// Reply Tweet on Last 10 Tweets page in last-tweets.php
$(document).on('click', '#LastTweetReplyBtn', function(e) {
	e.preventDefault();

	var screenname = $('#LastScreenName').text();
	var userid = $('#LastUserId').val();
	var textareaval = $("#TweetMsgBox").val();
	var twit_id = $("#LastReplyTweetID").val();
	if ( (textareaval.length > screenname.length + 2) ) {
		$.ajax({
			url: siteurl + 'app-ajax/?case=SubmitRetweet',
			method: 'POST',
			data:{
				'twit_id' 	  : twit_id,
				'twit_string' : textareaval,
				'user_id' 	  : userid,
				'user_screen' : screenname
			},
			success: function(data) {
				if ( data == 1 ) {
					$("#TweetMsgBox").val('@' + screenname + ' ');
					$('#LastTweetReplyMsg').removeClass('text-danger').addClass('text-success').text('Your tweet reply is successfully submitted.');
				} else {
					$('#LastTweetReplyMsg').removeClass('text-success').addClass('text-danger').text('Something is wrong, please try again.');
				}
				setTimeout(function() {
					$('#LastTweetReplyMsg').text('');
				}, 3000);
			}
		}); 
	} else {
		$('#LastTweetReplyMsg').removeClass('text-success').addClass('text-danger').text('Please write something before submit.');
		setTimeout(function() {
			$('#LastTweetReplyMsg').text('');
		}, 3000);
	}
});
// End of the script

$(document).on('click', 'a[data-target="#FilterModal"]', function() {
	$('#FilterMsgBox').empty();
});

// Filter in twitter-crm.php
$(document).on('click', '.SearchFilterBtn', function(e) {
	e.preventDefault();

	$('#filterLoader').removeClass('hide');
	var Filter_Flag = true;
	var filterData = $('#filterForm').serialize();
	var SearchFrom = $(this).attr('data-rel');
	var update = $(this).attr('data-action');
	var filterID = $(this).attr('data-id');
	if ( SearchFrom == 'SaveSearch' ) {
		$.ajax({
			type 		: 'GET',
			dataType 	: "json",
			cache		: false,
			url 		: siteurl + 'app-ajax/?case=SaveFilterSearch',
			data		: filterData + '&filterID=' + filterID + '&update=' + update,
			success 	: function(data) {
				if ( data.SaveSearch < 5 ) {
					if ( data.code == 1 ) {
						Filter_Flag = true;
						if ( data.filterID ) {
							$('.SearchFilterBtn').addClass('hide');
							$('#SaveSearchBtn').html('<i class="fa fa-save"></i> Update Filter').attr({
								'data-id': data.filterID,
								'data-action': 'update'
							}).removeClass('hide');
						}
					} else {
						Filter_Flag = false;
						$('#FilterMsgBox').html('Something went wrong, please try again').addClass('text-danger');
					}					
				} else {
					Filter_Flag = false;
					$('#FilterMsgBox').html('You have already saved 5 filters').addClass('text-danger');
				}
			}
		});
	}
	
	if ( Filter_Flag == true ) {
		$('.tab-pane.active').find('.crm-loading').removeClass('hide');
		$('#FilterModal').modal('hide');
		$('#filterLoader').addClass('hide');
		var parentID = $('.tab-pane.active').attr('id');
		if ( parentID == 'saved-filters' ) {
			$('#twitter-tab a[href="#saved-filters"]').click();
		} else if ( parentID == 'prospect-finder-pipeline' ) {
			$('#KeywordFilterCount').html('');
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraKeywords',
				data		: "limit=15&offset=0&bioTweets=" + bioTweets + "&" + filterData,
				success 	: function(data) {
					window.busyKeywords = false;
					limitKeywords = 15;
					offsetKeywords = 0;
					if ( data.html == 'noProspect' ) {
						$('#prospect-finder-pipeline-content').html('');
						$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your keywords.<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
					} else if ( data.html == "noResult" ) {
						$('#prospect-finder-pipeline-content').html('');
						$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get result.</p></div>');
					} else if ( data.html == 'noFilter' ) {
						$('#prospect-finder-pipeline-content').html('');
						$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
					} else {
						$('#Empty_Keywords').html('');
						if ( data.html == "noMore" ) {
							if ( $('#prospect-finder-pipeline .twuserbox').length > 0 ) {
								$("#loader_Keywords").html('<p>No more prospects.</p>').show();
							}
						} else {
							GenerateKeywordsCard(data.html);
						}
					}
					$('#LoadingKeywords').remove();
					ResetContentHeightAndScroller('prospect-finder-pipeline');
					//$('#KeywordFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
				}
			});
		} else if ( parentID == 'category-pipeline' ) {
			$('#CategoryFilterCount').html('');
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraCategories',
				data		: "limit=15&offset=0&" + filterData,
				success 	: function(data) {
					window.busyCategory = false;
					limitCategory = 15;
					offsetCategory = 0;
					if ( data.html == 'apiError' ) {
						$('#category-pipeline-content').html('');
						$('#Empty_Category').html('<div class="results-box empty-msg"><p>You have exceeded the Twitter API limits.<br />Please wait for 30 min. to generate more prospects.</p></div>');
					} else if ( data.html == 'noProspect' ) {
						$('#category-pipeline-content').html('');
						$('#Empty_Category').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your selected influencer(s).<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
					} else if ( data.html == "noResult" ) {
						$('#category-pipeline-content').html('');
						$('#Empty_Category').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'category-search/">Click here</a> to create a prospect to get result.</p></div>');
					} else if ( data.html == 'noFilter' ) {
						$('#category-pipeline-content').html('');
						$('#Empty_Category').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
					} else {
						$('#Empty_Category').html('');
						if ( data.html == "noMore" ) {
							if ( $('#category-pipeline .twuserbox').length > 0 ) {
								$("#loader_Category").html('<p>No more prospects.</p>').show();
							}
						} else {
							GenerateCategoriesCard(data.html);
						}
					}
					$('#LoadingCategory').remove();
					ResetContentHeightAndScroller('category-pipeline');
					//$('#CategoryFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
				}
			});
		} else if ( parentID == 'prospects' ) {
			$('#ProspectsFilterCount').html('');
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraProspects',
				data		: "limit=15&offset=0&" + filterData,
				success 	: function(data) {
					window.busyProspects = false;
					limitProspects = 15;
					offsetProspects = 0;
					if ( data.html == "noResult" ) {
						$('#prospects-content').html('');
						$('#Empty_Prospects').html('<div class="results-box empty-msg"><p>You didn\'t create any prospect yet.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get result.</p></div>');
					} else {
						$('#Empty_Prospects').html('');
						if ( data.html == "noMore" ) {
							if ( $('#prospects .twuserbox').length > 0 ) {
								$("#loader_Prospects").html('<p>No more prospects.</p>').show();
							}
						} else {
							GenerateProspectsCard(data.html);
						}
					}
					$('#LoadingProspects').remove();
					ResetContentHeightAndScroller('prospects');
					//$('#ProspectsFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
				}
			});
		} else if ( parentID == 'existing-followers' ) {
			$('#FollowersFilterCount').html('');
			$.ajax({
				type 		: 'GET',
				dataType 	: "json",
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=LoadingExtraFollowers',
				data		: "limit=15&offset=0&" + filterData,
				success 	: function(data) {
					window.busyFollowers = false;
					limitFollowers = 15;
					offsetFollowers = 0;
					if ( data.html == "noResult" ) {
						$('#existing-followers-content').html('');
						$('#Empty_Followers').html('<div class="results-box empty-msg"><p>You don\'t have any followers.</p></div>');
					} else {
						$('#Empty_Followers').html('');
						if ( data.html == "noMore" ) {
							if ( $('#existing-followers .twuserbox').length > 0 ) {
								$("#loader_Followers").html('<p>No more followers.</p>').show();
							}
						} else {
							GenerateFollowersCard(data.html);
						}
					}
					$('#LoadingFollowers').remove();
					ResetContentHeightAndScroller('existing-followers');
					//$('#FollowersFilterCount').html('Showing <b>' + data.resultCount + '</b> Followers');
				}
			});
		}
	}
});
// End of the script

// Remove Tag from keyword-search.php
function remove_tag(store_id, container_id, val) {
	var tags = $('#'+store_id).val().split(",");
	for(var i=0;i<tags.length;i++){
		if(tags[i].toLowerCase()==val.toLowerCase()){
			tags.splice(i,1);
		}
	}
	$('#'+store_id).val(tags.toString());
	var container=document.getElementById(container_id);
	var child=document.getElementById(container_id+"_"+val);
	container.removeChild(child);
}
// End of the function

// Remove fields from Direct Message Tab in twitter-crm.php
function remove_dm_field(elem, e) {
	e.preventDefault();
	
	$(elem).parent('span').remove();
	var dm_msg = new Array(
		'The second message would be sent 3 days after the target profile has followed you.',
		'The third message would be sent 7 days after the target profile has followed you.',
		'The fourth message would be sent 15 days after the target profile has followed you.'
	);
	$('.dm-msg').each(function(index, element) {
        $(element).text(dm_msg[index]);
    });
}
// End of the function

// Scrolling pagination for Keyword Pipeline Tab in twitter-crm.php
function displayExtraKeywords(lim, off, filter) {
	$.ajax({
		type		: "GET",
		dataType 	: "json",
		cache		: false,
		url			: siteurl + 'app-ajax/?case=LoadingExtraKeywords',
		data		: "limit=" + lim + "&offset=" + off + "&bioTweets=" + bioTweets + "&" + filter,
		beforeSend	: function() {
			$("#loader_Keywords").html('<div id="LoadingKeywords"><div class="loader"></div></div>').show();
		},
		success: function(data) {
			if ( data.html == 'noProspect' ) {
				$('#prospect-finder-pipeline-content').html('');
				$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your keywords.<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
			} else if ( data.html == "noResult" ) {
				$('#prospect-finder-pipeline-content').html('');
				$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get result.</p></div>');
			} else if ( data.html == 'noFilter' ) {
				$('#prospect-finder-pipeline-content').html('');
				$('#Empty_Keywords').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
			} else {
				$('#Empty_Keywords').html('');
				if ( data.html == "noMore" ) {
					if ( $('#prospect-finder-pipeline .twuserbox').length > 0 ) {
						$("#loader_Keywords").html('<p>No more prospects.</p>').show();
					}
				} else {
					GenerateKeywordsCard(data.html, true);
				}
			}
			$('#LoadingKeywords').remove();
			//$('#KeywordFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
			window.busyKeywords = false;
		}
	});
}
// End of the function

// Scrolling pagination for Category Pipeline Tab in twitter-crm.php
function displayExtraCategories(lim, off, filter) {
	var cust_status = $('#customer_status').val();
	if(cust_status == 'trip'){
			$('#category-pipeline-content').html('');
			$('#filter-handle').addClass('hide');
			$('#category-pipeline').find(':button').addClass('hide');
			$('#Empty_Category').html('<div class="results-box empty-msg"><p>This feature is not available to you. <a href="https://123employee.infusionsoft.com/app/orderForms/e982d81f-89ac-4837-92ba-93a500eea89d">Upgrade</a> to get the full version of SocialSonicCRM.</p></div>');
			ResetContentHeightAndScroller('category-pipeline');
	} else {
		$.ajax({
			type		: "GET",
			dataType 	: "json",
			cache		: false,
			url			: siteurl + 'app-ajax/?case=LoadingExtraCategories',
			data		: "limit=" + lim + "&offset=" + off + "&" + filter,
			beforeSend	: function() {
				$("#loader_Category").html('<div id="LoadingCategory"><div class="loader"></div></div>').show();
			},
			success: function(data) {
				if ( data.html == 'apiError' ) {
					$('#category-pipeline-content').html('');
					$('#Empty_Category').html('<div class="results-box empty-msg"><p>You have exceeded the Twitter API limits.<br />Please wait for 30 min. to generate more prospects.</p></div>');
				} else if ( data.html == 'noProspect' ) {
					$('#category-pipeline-content').html('');
					$('#Empty_Category').html('<div class="results-box empty-msg"><p>We are unable to find any prospects with your selected influencer(s).<br />Click <strong>Edit Search</strong> to change your keywords.</p></div>');
				} else if ( data.html == "noResult" ) {
					$('#category-pipeline-content').html('');
					$('#Empty_Category').html('<div class="results-box empty-msg"><p>You don\'t have any prospects in your pipeline.<br /><a href="' + siteurl + 'category-search/">Click here</a> to create a prospect to get result.</p></div>');
				} else if ( data.html == 'noFilter' ) {
					$('#category-pipeline-content').html('');
					$('#Empty_Category').html('<div class="results-box empty-msg"><p>There are no prospects with your filter criteria.</p></div>');
				} else {
					$('#Empty_Category').html('');
					if ( data.html == "noMore" ) {
						if ( $('#category-pipeline .twuserbox').length > 0 ) {
							$("#loader_Category").html('<p>No more prospects.</p>').show();
						}
					} else {
						GenerateCategoriesCard(data.html, true);
					}
				}
				$('#LoadingCategory').remove();
				//$('#CategoryFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
				window.busyCategory = false;
			}
		});
		
	}	
}
// End of the function

// Scrolling pagination for Prospects Tab in twitter-crm.php
function displayExtraProspects(lim, off, filter) {
	$.ajax({
		type		: "GET",
		dataType 	: "json",
		cache		: false,
		url			: siteurl + 'app-ajax/?case=LoadingExtraProspects',
		data		: "limit=" + lim + "&offset=" + off + "&" + filter,
		beforeSend	: function() {
			$("#loader_Prospects").html('<div id="LoadingProspects"><div class="loader"></div></div>').show();
		},
		success: function(data) {
			if ( data.html == "noResult" ) {
				$('#prospects-content').html('');
				$('#Empty_Prospects').html('<div class="results-box empty-msg"><p>You didn\'t create any prospect yet.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get result.</p></div>');
			} else {
				$('#Empty_Prospects').html('');
				if ( data.html == "noMore" ) {
					if ( $('#prospects .twuserbox').length > 0 ) {
						$("#loader_Prospects").html('<p>No more prospects.</p>').show();
					}
				} else {
					GenerateProspectsCard(data.html, true);
				}
			}
			$('#LoadingProspects').remove();
			//$('#ProspectsFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
			window.busyProspects = false;
		}
	});
}
// End of the function

// Scrolling pagination for Followers Tab in twitter-crm.php
function displayExtraFollowers(lim, off, filter) {
	$.ajax({
		type		: "GET",
		dataType 	: "json",
		cache		: false,
		url			: siteurl + 'app-ajax/?case=LoadingExtraFollowers',
		data		: "limit=" + lim + "&offset=" + off + "&" + filter,
		beforeSend	: function() {
			$("#loader_Followers").html('<div id="LoadingFollowers"><div class="loader"></div></div>').show();
		},
		success: function(data) {
			if ( data.html == "noResult" ) {
				$('#existing-followers-content').html('');
				$('#Empty_Followers').html('<div class="results-box empty-msg"><p>You don\'t have any followers.</p></div>');
			} else {
				$('#Empty_Followers').html('');
				if ( data.html == "noMore" ) {
					if ( $('#existing-followers .twuserbox').length > 0 ) {
						$("#loader_Followers").html('<p>No more followers.</p>').show();
					}
				} else {
					GenerateFollowersCard(data.html, true);
				}
			}
			$('#LoadingFollowers').remove();
			//$('#FollowersFilterCount').html('Showing <b>' + data.resultCount + '</b> Followers');
			window.busyFollowers = false;
		}
	});
}
// End of the function

function displayExtraWebsites(lim, off) {
	$.ajax({
		type 		: 'GET',
		dataType 	: "json",
		cache		: false,
		url 		: siteurl + 'app-ajax/?case=LoadingExtraWebsites',
		data		: "limit=" + lim + "&offset=" + off,
		beforeSend	: function() {
			$("#loader_Websites").html('<div id="LoadingWebsites"><div class="loader"></div></div>').show();
		},
		success: function(data) {
			if ( data.html == "noResult" ) {
				$('#show-websites-content').html('');
				$('#Empty_Websites').html('<div class="col-xs-12"><div class="results-box empty-msg" style="border:none;box-shadow:none;"><p>You didn\'t create any prospect yet.<br /><a href="' + siteurl + 'keyword-search/">Click here</a> to create a prospect to get website results.</p></div></div>');
			} else {
				$('#Empty_Websites').html('');
				if ( data.html == "noMore" ) {
					if ( $('#show-websites-content .table tbody tr').length > 0 ) {
						$("#loader_Websites").html('<p>No more prospects.</p>').show();
					}
				} else {
					GenerateWebsiteRow(data.html, true);
				}
			}
			$('#LoadingWebsites').remove();
			//$('#WebsitesFilterCount').html('Showing <b>' + data.resultCount + '</b> Websites');
			window.busyWebsites = false;
		}
	});
}

// Scrolling pagination for Prospects Tab in twitter-crm.php
function displayExtraUnfollow(lim, off) {
	$.ajax({
		type		: "GET",
		dataType 	: "json",
		cache		: false,
		url			: siteurl + 'app-ajax/?case=LoadingExtraUnfollow',
		data		: "limit=" + lim + "&offset=" + off,
		beforeSend	: function() {
			$("#loader_Unfollow").html('<div id="LoadingUnfollow"><div class="loader"></div></div>').show();
		},
		success: function(data) {
			if ( data.html == "noResult" ) {
				$('#unfollow-content').html('');
				$('#Empty_Unfollow').html('<div class="results-box empty-msg"><p>You don\'t have any prospects to unfollow.</p></div>');
			} else {
				$('#Empty_Unfollow').html('');
				if ( data.html == "noMore" ) {
					if ( $('#unfollow .twuserbox').length > 0 ) {
						$("#loader_Unfollow").html('<p>No more prospects.</p>').show();
					}
				} else {
					GenerateUnfollowCard(data.html, true);
				}
			}
			$('#LoadingUnfollow').remove();
			//$('#UnFollowFilterCount').html('Showing <b>' + data.resultCount + '</b> Prospects');
			window.busyUnfollow = false;
		}
	});
}
// End of the function

// Scrolling pagination for Responses Tab in lead-responses.php
function displayExtraResponses(lim, off) {
	$.ajax({
		type		: "GET",
		dataType 	: "json",
		cache		: false,
		url			: siteurl + 'app-ajax/?case=LoadingExtraResponses',
		data		: "limit=" + lim + "&offset=" + off,
		beforeSend	: function() {
			$("#loader_Responses").html('<div id="LoadingResponses"><div class="loader"></div></div>').show();
		},
		success: function(data) {
			if ( data.html == "noResult" ) {
				$('#responses-content').html('');
				$('#Empty_Responses').html('<div class="TweetCard empty-msg" style="display:table;"><p><br /><br /><i class="fa fa-info-circle"></i> You didn\'t send any tweets yet.<br /><br /><br /></p></div>');
			} else {
				$('#Empty_Responses').html('');
				if ( data.html == "noMore" ) {
					if ( $('#responses-content .TweetCard').length > 0 ) {
						$("#loader_Responses").html('<p>No more responses.</p>').show();
					}
				} else {
					GenerateResponsesCard(data.html, true);
				}
			}
			$('#LoadingResponses').remove();
			window.busyResponses = false;
		}
	});
}
// End of the function

// Edit account info
$(document).on('click', '.AccountEditBtn', function(e) {
	e.preventDefault();

	$(this).parents('td').find('.Cust_Value').toggleClass('hide');
	$(this).parents('td').find('.Cust_Field').toggleClass('hide');

	if ( $(this).html() == '<i class="fa fa-edit"></i> Change' ) {
		$(this).parents('td').find('.AccountSaveBtn').toggleClass('hide');
		$(this).html('<i class="fa fa-times"></i> Cancel');
	} else {
		$(this).parents('td').find('.AccountSaveBtn').toggleClass('hide');
		$(this).html('<i class="fa fa-edit"></i> Change');
	}
})
// End of the script

// Edit account info
$(document).on('click', '.AccountSaveBtn', function(e) {
	e.preventDefault();

	var btn = $(this);
	var Cust_Field = $(this).parents('td').find('.Cust_Field');
	$.ajax({
		type		: "GET",
		url			: siteurl + 'app-ajax/?case=UpdateCustomerAccount',
		data		: "Cust_Field=" + Cust_Field.attr('name') + "&Cust_Value=" + Cust_Field.val(),
		success: function(data) {
			if ( data == 1 ) {
				btn.parents('td').find('.Cust_Value').text(Cust_Field.val());
				btn.parents('td').find('.Cust_Value').toggleClass('hide');
				btn.parents('td').find('.Cust_Field').toggleClass('hide');
				btn.parents('td').find('.AccountSaveBtn').toggleClass('hide');
				btn.parents('td').find('.AccountEditBtn').html('<i class="fa fa-edit"></i> Change');
			} else {
				Cust_Field.css({
					'borderColor': '#f00',
					'backgroundColor': '#fee'
				});
			}
		}
	});
});
// End of the script

// Toggle plus minus icon for faq page
function toggleIcon(e) {
    $(e.target).prev('.panel-heading').find(".more-less").toggleClass('glyphicon-plus glyphicon-minus');
}
// End of the script

// Reset page scroll to top
function ResetContentHeightAndScroller(currentTab) {
	$('#twitter-tab li').removeClass('disabled');
	if ( currentTab == 'prospect-finder-pipeline' ) {
		$('.tab-pane.active .nano').height($(window).height()-($('.topbar').outerHeight(true)+$('.group_content_topbar').outerHeight(true)+$('#twitter-tab').outerHeight(true)+$('.buttons-actions').outerHeight(true)+40));
	} else if ( currentTab == 'set-direct-message' ) {
		$('.tab-pane.active .nano').height($(window).height()-($('.topbar').height() + $('.group_content_topbar').height() + $('#twitter-tab').height() + 40));
	} else {
		$('.tab-pane.active .nano').height($(window).height()-($('.topbar').outerHeight(true)+$('.group_content_topbar').outerHeight(true)+$('#twitter-tab').outerHeight(true)+$('.buttons-actions').outerHeight(true)+85));
	}
	$('.tab-pane.active .nano').scrollTop(0);
	$('#' + currentTab).find('.crm-loading').addClass('hide');
}
// End of the script

// Play video with jwplayer 
function playTutorial(vid) {
    jwplayer(vid).play(true);
}
// End of the script

// Pause video with jwplayer
function pauseTutorial(vid) {
    jwplayer(vid).pause(true);
}
// End of the script

// Pause all video with jwplayer
function pauseAllTutorials(className) {
	$(className).each(function() {
		var player = $(this).find('.jwplayer').attr('id');
	    jwplayer(player).pause(true);
	});
}
// End of the script

// Check twitter Valid data
function CheckTwitterAuthenticData(consumer_key, consumer_secret, access_token, token_secret, screen_name, twitter_id) {
	$.ajax({
		type 		: 'POST',
		dataType 	: "json",
		async		: false,
		cache		: false,
		url 		: siteurl + 'app-ajax/?case=CheckTwitterAuthenticData',
		data		: "consumer_key=" + consumer_key + "&consumer_secret=" + consumer_secret + "&access_token=" + access_token + "&token_secret=" + token_secret + "&screen_name=" + screen_name + "&twitter_id=" + twitter_id,
		success: function(data) {
			return data;
		}
	});
}
// End of the script

// Reset error message
if ( $('#customer-table').length > 0 ) {
	setInterval(ResetErrorSession, 10000);
}
function ResetErrorSession() {
	$.ajax({
		type: 'GET',
		url: siteurl + 'admin/admin-ajax/?case=ExpireErrorReport',
		success: function(data) {
			if ( $('.session-success').length > 0 ) {
				$('.session-success').addClass('hide');
			}
		}
	});
}
// End of the script

// Generate html for keyword pipeline
function GenerateKeywordsCard(prospects, append = false) {
	var html = '';
	for ( var i in prospects ) {
		var prospect = prospects[i];
		html += '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 twuserbox animated fadeIn">';
			html += '<div class="results-box">';
				html += '<a href="javascript:;" class="remove_prospect_btn" data-userid="' + prospect.user_id + '" data-custid="' + prospect.search_user_id + '"><i class="fa fa-trash"></i></a>';
				html += '<div class="col-xs-12"><h4><strong>@' + prospect.screen_name + '</strong></h4></div>';
				html += '<div class="col-xs-12">';
					html += '<div class="row">';
						html += '<div class="col-xs-3" style="padding-right:0;">';
							html += '<div class="CardImg">';
								html += '<span class="img-loading"></span>';
								if ( prospect.profile_image ) {
									html += '<img src="' + prospect.profile_image + '" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								} else {
									html += '<img src="' + siteurl + 'images/default_profile_0_400x400.png" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								}
							html += '</div>';
						html += '</div>';
						html += '<div class="col-xs-9">';
							html += '<input type="hidden" value="' + prospect.location + '" class="location-tag" />';
							html += '<input type="hidden" value="' + prospect.followers + '" class="followers-tag" />';
							html += '<input type="hidden" value="' + prospect.following + '" class="following-tag" />';
							if ( bioTweets == 'bio' && prospect.description ) {
								html += '<p class="bioTweetsP">';
								html += prospect.description;
								html += '</p>';
							} else if ( bioTweets == 'tweets' && prospect.tweets ) {
								html += '<p class="bioTweetsP">';
								html += prospect.tweets;
								html += '</p>';
							} else {
								html += '<p class="bioTweetsP"></p>';
							}
							html += '<span class="bio-tag hide">';
							if ( prospect.description ) {
								html += prospect.description;
							}
							html += '</span>';
							html += '<span class="tweets-tag hide">';
							if ( prospect.tweets ) {
								html += prospect.tweets;
							}
							html += '</span>';
						html += '</div>';
					html += '</div>';
				html += '</div>';
				html += '<div class="fourBox">';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="https://www.twitter.com/' + prospect.screen_name + '"><i class="fa fa-twitter"></i> Profile on Twitter</a></p>';
					html += '</div>';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="' + siteurl + '10tweets/' + prospect.user_id + '/"><i class="fa fa-comments"></i> Last 10 Tweets</a></p>';
					html += '</div>';
					if ( prospect.website ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><a target="_blank" href="' + prospect.website + '"><i class="fa fa-globe"></i> ' + prospect.website + '</a></p>';
						html += '</div>';
					}
					if ( prospect.location ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><i class="fa fa-map-marker"></i> ' + prospect.location + '</p>';
						html += '</div>';
					}
				html += '</div>';
				html += '<div class="action-btns">';
					html += '<a href="javascript:;" class="btn btn-info btn-xs initiate_nurture" data-userid="' + prospect.user_id + '" data-screenname="' + prospect.screen_name + '" data-tweetid="' + prospect.tweet_id + '"><i class="fa fa-thumbs-up"></i> Initiate Nurturing</a>';
				html += '</div>';
			html += '</div>';
		html += '</div>';
	}
	if ( append == true ) {
		$('#prospect-finder-pipeline-content').append(html);
	} else {
		$('#prospect-finder-pipeline-content').html(html);
	}
	setTimeout(function() {
		$('.img-loading').fadeOut();
	}, 1000);
}
// End of the script

// Generate html for category pipeline
function GenerateCategoriesCard(prospects, append = false) {
	var html = '';
	for ( var i in prospects ) {
		var prospect = prospects[i];
		html += '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 twuserbox animated fadeIn">';
			html += '<div class="results-box">';
				html += '<a href="javascript:;" class="remove_prospect_btn" data-userid="' + prospect.user_id + '" data-custid="' + prospect.search_user_id + '"><i class="fa fa-trash"></i></a>';
				html += '<div class="col-xs-12"><h4><strong>@' + prospect.screen_name + '</strong></h4></div>';
				html += '<div class="col-xs-12">';
					html += '<div class="row">';
						html += '<div class="col-xs-3" style="padding-right:0;">';
							html += '<div class="CardImg">';
								html += '<span class="img-loading"></span>';
								if ( prospect.profile_image ) {
									html += '<img src="' + prospect.profile_image + '" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								} else {
									html += '<img src="' + siteurl + 'images/default_profile_0_400x400.png" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								}
							html += '</div>';
						html += '</div>';
						html += '<div class="col-xs-9">';
							html += '<input type="hidden" value="' + prospect.influncer_id + '" class="influncer-tag" />';
							html += '<input type="hidden" value="' + prospect.location + '" class="location-tag" />';
							html += '<input type="hidden" value="' + prospect.followers + '" class="followers-tag" />';
							html += '<input type="hidden" value="' + prospect.following + '" class="following-tag" />';
							if ( prospect.description ) {
								html += '<p class="bioTweetsP">';
								html += prospect.description;
								html += '</p>';
							}
						html += '</div>';
					html += '</div>';
				html += '</div>';
				html += '<div class="fourBox">';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="https://www.twitter.com/' + prospect.screen_name + '"><i class="fa fa-twitter"></i> Profile on Twitter</a></p>';
					html += '</div>';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="' + siteurl + '10tweets/' + prospect.user_id + '/"><i class="fa fa-comments"></i> Last 10 Tweets</a></p>';
					html += '</div>';
					if ( prospect.website ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><a target="_blank" href="' + prospect.website + '"><i class="fa fa-globe"></i> ' + prospect.website + '</a></p>';
						html += '</div>';
					}
					if ( prospect.location ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><i class="fa fa-map-marker"></i> ' + prospect.location + '</p>';
						html += '</div>';
					}
				html += '</div>';
				html += '<div class="action-btns">';
					html += '<a href="javascript:;" class="btn btn-info btn-xs initiate_nurture" data-userid="' + prospect.user_id + '" data-screenname="' + prospect.screen_name + '" data-tweetid="influence"><i class="fa fa-thumbs-up"></i> Initiate Nurturing</a>';
				html += '</div>';
			html += '</div>';
		html += '</div>';
	}
	if ( append == true ) {
		$('#category-pipeline-content').append(html);
	} else {
		$('#category-pipeline-content').html(html);
	}
	setTimeout(function() {
		$('.img-loading').fadeOut();
	}, 1000);
}
// End of the script

// Generate html for prospects
function GenerateProspectsCard(prospects, append = false) {
	var html = '';
	for ( var i in prospects ) {
		var prospect = prospects[i];
		if ( prospect.status == 'scTweet' ) {
			var bg = 'style="background-color:#b4eab5"';
		} else {
			var bg = '';
		}
		html += '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 direct-box twuserbox animated fadeIn">';
  			html += '<div class="results-box" ' + bg + '>';
  				if ( prospect.status == 'complete' ) {
    				html += '<input name="direct-check[]" class="direct-check" data-userid="' + prospect.user_id + '" data-screenname="' + prospect.screen_name + '" data-tweetid="' + (prospect.tweet_id ? prospect.tweet_id : 'influence') + '" type="checkbox">';
    			}
    			html += '<div class="col-xs-12"><h4><strong>@' + prospect.screen_name + '</strong></h4></div>';
    			html += '<div class="col-xs-12">';
      				html += '<div class="row">';
        				html += '<div class="col-xs-3" style="padding-right:0;">';
          					html += '<div class="CardImg">';
            					html += '<span class="img-loading"></span>';
            					if ( prospect.profile_image ) {
									html += '<img src="' + prospect.profile_image + '" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								} else {
									html += '<img src="' + siteurl + 'images/default_profile_0_400x400.png" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								}
          					html += '</div>';
        				html += '</div>';
        				html += '<div class="col-xs-9">';
        					html += '<div class="contentBox">';
	          					if ( prospect.influncer_id ) {
									html += '<input type="hidden" value="' + prospect.influncer_id + '" class="influncer-tag" />';
						    	}
								html += '<input type="hidden" value="' + prospect.location + '" class="location-tag" />';
								html += '<input type="hidden" value="' + prospect.followers + '" class="followers-tag" />';
								html += '<input type="hidden" value="' + prospect.following + '" class="following-tag" />';
	          					if ( prospect.description ) {
									html += '<div class="DescDiv">';
									html += prospect.description;
									html += '</div>';
								}
								html += '<div class="TweetsDiv">';
									html += '<p class="loadingTweets"><img src="' + siteurl + 'images/loading.gif" alt="loading" /></p>';
								html += '</div>';
							html += '</div>';
        				html += '</div>';
      				html += '</div>';
    			html += '</div>';
    			html += '<div class="fourBox">';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="https://www.twitter.com/' + prospect.screen_name + '"><i class="fa fa-twitter"></i> Profile on Twitter</a></p>';
					html += '</div>';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="' + siteurl + '10tweets/' + prospect.user_id + '/"><i class="fa fa-comments"></i> Last 10 Tweets</a></p>';
					html += '</div>';
					if ( prospect.website ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><a target="_blank" href="' + prospect.website + '"><i class="fa fa-globe"></i> ' + prospect.website + '</a></p>';
						html += '</div>';
					}
					if ( prospect.location ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><i class="fa fa-map-marker"></i> ' + prospect.location + '</p>';
						html += '</div>';
					}
				html += '</div>';
				html += '<div class="action-btns">';
					html += '<a href="javascript:;" class="tweetsButton" data-userid="' + prospect.user_id + '" data-show="desc"><i class="fa fa-chevron-circle-left fa-lg"></i></a>';
				html += '</div>';
  			html += '</div>';
		html += '</div>';		
	}
	if ( append == true ) {
		$('#prospects-content').append(html);
	} else {
		$('#prospects-content').html(html);
	}
	setTimeout(function() {
		$('.img-loading').fadeOut();
	}, 1000);
}
// End of the script

// Small arrow button for showing last 10 tweets each prospect in prospects tab
$(document).on('click', '.tweetsButton', function (e) {
	e.preventDefault();

	var btn = $(this);
	if ( btn.attr('data-show') == 'desc' ) {
	    btn.parents('.results-box').find('.DescDiv').animate({left: '-100%', opacity: 0}, 400);
	    btn.parents('.results-box').find('.TweetsDiv').animate({right: '0', opacity: 1}, 400);
	    btn.find('i').removeClass('fa-chevron-circle-left').addClass('fa-chevron-circle-right');
	    var userid = $(this).attr('data-userid');
	    setTimeout(function() {
		    $.ajax({
				type 		: 'POST',
				dataType 	: "json",
				async		: false,
				cache		: false,
				url 		: siteurl + 'app-ajax/?case=GetProspectsLast10Tweets',
				data		: "userid=" + userid,
				success: function(data) {
					if ( data.error ) {
						btn.parents('.results-box').find('.TweetsDiv').html('<p>' + data.error + '</p>');
					} else {
						var html = '<div id="myCarousel' + userid + '" class="carousel slide" data-ride="carousel"><div class="carousel-inner" role="listbox">';
						for ( var i in data.tweets ) {
							var tweet = data.tweets[i];
							html += '<div class="item ' + (i==0?'active':'') + '"><div class="carousel-content">';
								html +='<p><strong>Tweet ' + (parseInt(i)+1) + ': </strong>' + tweet.text + '</p>';
								html += '<div class="text-center"><button data-tweetid="' + tweet.id + '" class="btn btn-info btn-xs ReplyBtnLoadLead"><i class="fa fa-reply"></i> Reply</button></div>';
							html += '</div></div>';
						}
						html += '</div></div><a class="pull-left carousel-control" href="#myCarousel' + userid + '" role="button" data-slide="prev"><i class="fa fa-angle-left"></i></a><a class="pull-right carousel-control" href="#myCarousel' + userid + '" role="button" data-slide="next"><i class="fa fa-angle-right"></i></a>';
						btn.parents('.results-box').find('.TweetsDiv').html(html);
						$('.carousel').carousel({
							interval: false
						});
					}
					btn.attr('data-show', 'tweet');
				}
			});
		}, 500);
	} else {
		btn.parents('.results-box').find('.DescDiv').animate({left: '0', opacity: 1}, 400);
	    btn.parents('.results-box').find('.TweetsDiv').animate({right: '-100%', opacity: 0}, 400);
	    btn.find('i').removeClass('fa-chevron-circle-right').addClass('fa-chevron-circle-left');
	    btn.attr('data-show', 'desc');
	}
});
// End of the script

// Open tweet reply modal on twitter-crm.php
$(document).on('click', '.ReplyBtnLoadLead', function(e) {
	e.preventDefault();

	var btn = $(this);
	var tweetid = btn.attr('data-tweetid');
	var screenname = btn.parents('.results-box').find('h4').find('strong').text().replace('@', '');
	var userid = btn.parents('.results-box').find('.direct-check').attr('data-userid');
	$('#ReplyTweetMsg').empty();
	$('#ReplyMsgBox').val('@' + screenname + ' ');
	$('#TweetReplySendBtn').attr({
		'data-tweetid': tweetid,
		'data-screenname': screenname,
		'data-userid': userid
	});
	$('#ReplyTweetsMsgModal').modal('show');
	$('#ReplyMsgBox').focus();
});
// End of the script

// Tweet reply from prospects tab twitter-crm.php 
$(document).on('click', '#TweetReplySendBtn', function(e) {
	e.preventDefault();

	var btn = $(this);
	btn.html('<img src="' + siteurl + 'images/loading.gif" />').attr('disabled', 'disabled');
	var tweetid = btn.attr('data-tweetid');
	var screenname = btn.attr('data-screenname');
	var userid = btn.attr('data-userid');
	var tweetmsg = $("#ReplyMsgBox").val();	
	if ( tweetmsg.length != 0 && tweetmsg.length <= 140 && tweetmsg.indexOf('@' + screenname + ' ') != -1 && tweetmsg != '@' + screenname + ' ' ) {
		$.ajax({
			url: siteurl + 'app-ajax/?case=SubmitRetweet',
			method: 'POST',
			data:{
				'twit_id' : tweetid,
				'twit_string' : tweetmsg,
				'user_id' : userid,
				'user_screen' : screenname
			},
			success: function(data) {
				if ( data == 1 ) {
					$('#ReplyMsgBox').val('@' + screenname + ' ');
					$('#ReplyTweetMsg').removeClass('text-danger').addClass('text-success').text('Your twitter reply successfully submitted.');
				} else {
					$('#ReplyTweetMsg').removeClass('text-success').addClass('text-danger').text('Something went wrong, try again.');
				}
				$('#ReplyMsgBox').focus();
				btn.html('<i class="fa fa-reply"></i> Reply').removeAttr('disabled');
			}
		}); 
	} else {
		if ( tweetmsg.length == 0 || tweetmsg == '@' + screenname + ' ' ) {
			alert('Please enter your tweet before send.');
		} else if ( tweetmsg.length > 140 ) {
			alert('Please enter your tweet within 140 characters.');
		} else if ( tweetmsg.indexOf('@' + screenname + ' ') == -1 ) {
			alert('Please do not remove the \'@screenname \' from reply box.');
		}
		btn.html('<i class="fa fa-reply"></i> Reply').removeAttr('disabled');
	}
});

// Generate html for followers tab
function GenerateFollowersCard(prospects, append = false) {
	var html = '';
	for ( var i in prospects ) {
		var prospect = prospects[i];
		html += '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 direct-box twuserbox animated fadeIn">';
  			html += '<div class="results-box">';
    			html += '<input name="direct-check[]" class="direct-check" data-userid="' + prospect.id_str + '" data-screenname="' + prospect.screen_name + '" data-tweetid="" type="checkbox">';
    			html += '<div class="col-xs-12"><h4><strong>@' + prospect.screen_name + '</strong></h4></div>';
    			html += '<div class="col-xs-12">';
      				html += '<div class="row">';
        				html += '<div class="col-xs-3" style="padding-right:0;">';
          					html += '<div class="CardImg">';
								html += '<span class="img-loading"></span>';
								if ( prospect.profile_pic ) {
									html += '<img src="' + prospect.profile_pic + '" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								} else {
									html += '<img src="' + siteurl + 'images/default_profile_0_400x400.png" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								}
							html += '</div>';
        				html += '</div>';
        				html += '<div class="col-xs-9">';
          					html += '<input type="hidden" value="' + prospect.location + '" class="location-tag" />';
							html += '<input type="hidden" value="' + prospect.followers + '" class="followers-tag" />';
							html += '<input type="hidden" value="' + prospect.following + '" class="following-tag" />';
          					if ( prospect.description ) {
								html += '<p class="bioTweetsP">';
								html += prospect.description;
								html += '</p>';
							}
        				html += '</div>';
      				html += '</div>';
    			html += '</div>';
    			html += '<div class="fourBox">';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="https://www.twitter.com/' + prospect.screen_name + '"><i class="fa fa-twitter"></i> Profile on Twitter</a></p>';
					html += '</div>';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="' + siteurl + '10tweets/' + prospect.id_str + '/"><i class="fa fa-comments"></i> Last 10 Tweets</a></p>';
					html += '</div>';
					if ( prospect.website ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><a target="_blank" href="' + prospect.website + '"><i class="fa fa-globe"></i> ' + prospect.website + '</a></p>';
						html += '</div>';
					}
					if ( prospect.location ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><i class="fa fa-map-marker"></i> ' + prospect.location + '</p>';
						html += '</div>';
					}
				html += '</div>';
  			html += '</div>';
		html += '</div>';
	}
	if ( append == true ) {
		$('#existing-followers-content').append(html);
	} else {
		$('#existing-followers-content').html(html);
	}
	setTimeout(function() {
		$('.img-loading').fadeOut();
	}, 1000);
}
// End of the script

// Generate html for show websites tab
function GenerateWebsiteRow(websites, append = false) {
	var html = '';
	for ( var i in websites ) {
		var website = websites[i];
		html += '<tr>';
		html += '<td>';
		html += '<input type="checkbox" class="allcheck" name="checkall[]" value="' + website.full_name + '@|@' + website.screen_name + '@|@' + website.description + '@|@' + website.location + '@|@' + website.website + '" />';
		html += '</td>';
		html += '<td>' + website.full_name + '</td>';
		html += '<td>';
		html += '<a target="_blank" href="https://www.twitter.com/' + website.screen_name + '">';
		html += website.screen_name;
		html += '</a>';
		html += '</td>';
		html += '<td>' + website.description + '</td>';
		html += '<td>' + website.location + '</td>';
		html += '<td>';
		html += '<a target="_blank" href="' + website.website + '">';
		html += website.website;
		html += '</a>';
		html += '</td>';
		html += '</tr>';
	}
	if ( append == true ) {
		$('#show-websites-content .table tbody').append(html);
	} else {
		$('#show-websites-content .table tbody').html(html);
	}
}
// End of the script

// Generate html for unfollow tab
function GenerateUnfollowCard(prospects, append = false) {
	var html = '';
	for ( var i in prospects ) {
		var prospect = prospects[i];
		html += '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 direct-box twuserbox animated fadeIn">';
  			html += '<div class="results-box">';
    			html += '<div class="col-xs-12"><h4><strong>@' + prospect.screen_name + '</strong></h4></div>';
    			html += '<div class="col-xs-12">';
      				html += '<div class="row">';
        				html += '<div class="col-xs-3" style="padding-right:0;">';
          					html += '<div class="CardImg">';
            					html += '<span class="img-loading"></span>';
            					if ( prospect.profile_image ) {
									html += '<img src="' + prospect.profile_image + '" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								} else {
									html += '<img src="' + siteurl + 'images/default_profile_0_400x400.png" alt="' + prospect.screen_name + '" height="100%" width="100%" class="thumbnail" />';
								}
          					html += '</div>';
        				html += '</div>';
        				html += '<div class="col-xs-9">';
          					if ( prospect.influncer_id ) {
								html += '<input type="hidden" value="' + prospect.influncer_id + '" class="influncer-tag" />';
					    	}
							html += '<input type="hidden" value="' + prospect.location + '" class="location-tag" />';
							html += '<input type="hidden" value="' + prospect.followers + '" class="followers-tag" />';
							html += '<input type="hidden" value="' + prospect.following + '" class="following-tag" />';
          					if ( prospect.description ) {
								html += '<p class="bioTweetsP">';
								html += prospect.description;
								html += '</p>';
							}
        				html += '</div>';
      				html += '</div>';
    			html += '</div>';
    			html += '<div class="fourBox">';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="https://www.twitter.com/' + prospect.screen_name + '"><i class="fa fa-twitter"></i> Profile on Twitter</a></p>';
					html += '</div>';
					html += '<div class="col-xs-6">';
						html += '<p class="three-dot"><a target="_blank" href="' + siteurl + '10tweets/' + prospect.user_id + '/"><i class="fa fa-comments"></i> Last 10 Tweets</a></p>';
					html += '</div>';
					if ( prospect.website ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><a target="_blank" href="' + prospect.website + '"><i class="fa fa-globe"></i> ' + prospect.website + '</a></p>';
						html += '</div>';
					}
					if ( prospect.location ) {
						html += '<div class="col-xs-6">';
							html += '<p class="three-dot"><i class="fa fa-map-marker"></i> ' + prospect.location + '</p>';
						html += '</div>';
					}
				html += '</div>';
				html += '<div class="action-btns">';
					html += '<a href="javascript:;" class="btn btn-xs btn-info unfollow_prospect" data-userid="' + prospect.user_id + '" data-screenname="' + prospect.screen_name + '"><i class="fa fa-thumbs-down"></i> Unfollow</a>';
				html += '</div>';
  			html += '</div>';
		html += '</div>';
	}
	if ( append == true ) {
		$('#unfollow-content').append(html);
	} else {
		$('#unfollow-content').html(html);
	}
	setTimeout(function() {
		$('.img-loading').fadeOut();
	}, 1000);
}
// End of the script

// generate html for lead responses page
function GenerateResponsesCard(responses, append = false) {
	var html = '';
	for ( var i in responses ) {
		var response = responses[i];
		if ( response.profile_image != null && response.screen_name != null && response.full_name != null && response.send_time != null && response.send_to != null ) {
			html += '<div class="col-xs-4 ProspectsLeadCard" id="' + response.send_to + '">';
				if ( response.status == 'unread' ) {
					html += '<div class="unread">' + response.unread_count + '</div>';
				}
				html += '<div class="TweetCard">';
					html += '<div class="col-lg-3">';
						html += '<div class="CardImg">';
							html += '<span class="img-loading"></span>';
							if ( response.profile_image ) {
								html += '<img src="' + response.profile_image + '" alt="' + response.screen_name + '" height="100%" width="100%" class="thumbnail" />';
							} else {
								html += '<img src="' + siteurl + 'images/default_profile_0_400x400.png" alt="' + response.screen_name + '" height="100%" width="100%" class="thumbnail" />';
							}
						html += '</div>';
					html += '</div>';
					html += '<div class="col-lg-8">';
						html += '<div class="row">';
							html += '<p>' + response.full_name + '</p>';
							html += '<small class="text-muted">';
								html += '<i class="fa fa-at"></i>' + response.screen_name + '<br />';
								html += '<i class="fa fa-calendar"></i> ' + response.send_time;
							html += '</small>';
						html += '</div>';
					html += '</div>';
					html += '<a class="btn btn-default viewResponseBtn" data-sendto="' + response.send_to + '" href="#"><i class="fa fa-caret-right"></i></a>';
				html += '</div>';
			html += '</div>';
		}
	}
	if ( append == true ) {
		$('#responses-content').append(html);
	} else {
		$('#responses-content').html(html);
	}
	setTimeout(function() {
		$('.img-loading').fadeOut();
	}, 1000);
}
// End of the script

// Load multiple conversations on right side modal
$(document).on('click', '.viewResponseBtn', function(e) {
	e.preventDefault();

	$('#replies-content').html('');
	var btn = $(this);
	$('.LeadLoader').show();
	$('.viewResponseBtn').removeClass('btn-info');
	btn.addClass('btn-info');
	$('#replies').animate({
		left: 0,
		right: 0,
	}, 400);
	$('#conversations').animate({
		left: '100%',
		right: '-100%',
	}, 400);
	$('#reply-input, #btn-chat').attr('disabled', 'disabled');
	$('#back2replies, #reply-footer').addClass('hide');
	$('#btn-chat').attr({
		'data-tweetid': '',
	});
	$('#reply-input').val('');
	$('#tweetsModal').modal('show');
	setTimeout(function() {
		var send_to = btn.attr('data-sendto');
		$.ajax({
			type 		: 'POST',
			dataType 	: "json",
			async		: false,
			cache		: false,
			url 		: siteurl + 'app-ajax/?case=LoadingSingleReplies',
			data		: "send_to=" + send_to,
			success: function(data) {
				GenerateRepliesCard(data.html);
			}
		});
	}, 500);
});
// End of the script

// Generate html for replies content on right side modal
function GenerateRepliesCard(replies) {
	var html = '';
	for ( var i in replies ) {
		var reply = replies[i];
		if ( reply.tweet_id != null && reply.tweet != null && reply.send_time != null ) {
			html += '<div class="col-xs-12 ProspectsRepliesCard" id="' + reply.tweet_id + '">';
				html += '<a class="viewRepliesBtn" data-tweetid="' + reply.tweet_id + '" data-recipientid="' + reply.recipient_id + '" data-recpcount="' + reply.recp_count + '" data-unreadcount="' + reply.unread_count + '" href="#">';
					if ( reply.status == 'unread' ) {
						html += '<div class="unread-list">' + reply.unread_count + '</div>';
					}
					html += '<div class="RepliesCard">';
						html += '<div class="col-lg-9">';
							html += '<p>' + reply.tweet + '</p>';
						html += '</div>';
						html += '<div class="col-lg-3">';
							html += '<p><small class="text-muted"><i class="fa fa-calendar"></i> ' + reply.send_time + '</small></p>';
						html += '</div>';	
					html += '</div>';
				html += '</a>';
			html += '</div>';
		}
	}
	$('#replies-content').html(html);
	$('.LeadLoader').hide();
}
// End of the script

// Load every replies with sliding effects on right side modal
$(document).on('click', '.viewRepliesBtn', function(e) {
	e.preventDefault();

	$('#conversations-content').html('');
	$('#conversations .LeadLoader').show();
	$('#replies').animate({
		left: '-100%',
		right: '100%',
	}, 400);
	$('#conversations').animate({
		left: 0,
		right: 0,
	}, 400);
	$('#tweetsModal .modal-body').css('bottom', 46);
	$('#back2replies, #reply-footer').removeClass('hide');
	var btn = $(this);
	var tweetid = btn.attr('data-tweetid');
	var recipientid = btn.attr('data-recipientid');
	var recpcount = btn.attr('data-recpcount');
	var unreadcount = btn.attr('data-unreadcount');
	setTimeout(function() {
		$.ajax({
			type 		: 'POST',
			dataType 	: "json",
			async		: false,
			cache		: false,
			url 		: siteurl + 'app-ajax/?case=LoadingSingleConversation',
			data		: "tweetid=" + tweetid,
			success: function(data) {
				GenerateConversationsCard(data.html);
				if ( recpcount == unreadcount ) {
					$('#' + recipientid).find('.unread').remove();
				} else {
					if ( recpcount != 0 ) {
						var dynCount = $('#' + recipientid).find('.unread').text();
						if ( (dynCount - unreadcount) <= 0 ) {
							$('#' + recipientid).find('.unread').remove();
						} else {
							$('#' + recipientid).find('.unread').text(dynCount - unreadcount);
						}
					} else {
						$('#' + recipientid).find('.unread').remove();
					}
				}
				btn.attr('data-unreadcount', 0);
				$('#' + tweetid).find('.unread-list').remove();
				$('#tweetsModal .modal-body').scrollTop($('.chat').height());
				$('#reply-input').focus();
			}
		});
	}, 500);
});
// End of the script

var curr_screenname;
// Generate html for conversations on right side modal
function GenerateConversationsCard(conversations) {
	var html = '';
	html += '<div class="col-xs-12 ProspectsConvCard">';
		html += '<div class="row">';
			html += '<ul class="chat">';
				for ( var i in conversations.tweets ) {
					var conversation = conversations.tweets[i];
					if ( conversation.thumbnail != null && conversation.name != null && conversation.content != null ) {
						html += '<li class="left clearfix">';
							html += '<span class="chat-img pull-left">';
								html += '<img src="' + conversation.thumbnail + '" alt="' + conversation.name + '" class="img-circle" />';
							html += '</span>';
							html += '<div class="chat-body clearfix">';
								html += '<div class="header">';
									html += '<strong class="primary-font">' + conversation.name + '</strong>';
								html += '</div>';
								html += '<p>' + conversation.content + '</p>';
							html += '</div>';
						html += '</li>';
					}
				}
			html += '</ul>';
		html += '</div>';
	html += '</div>';
	$('#conversations-content').html(html);
	$('#conversations .LeadLoader').hide();
	$('#btn-chat').attr({
		'data-tweetid': conversations.tweetid,
		'data-screenname': conversations.screen_name
	});
	$('#reply-input').val('@' + conversations.screen_name + ' ');
	$('#reply-input, #btn-chat').removeAttr('disabled');
	curr_screenname = conversations.screen_name;
}
// End of the script

// Back to multiple replies view on right side modal
$(document).on('click', '#back2replies', function(e) {
	e.preventDefault();

	$('#replies').animate({
		left: 0,
		right: 0,
	}, 400);
	$('#conversations').animate({
		left: '100%',
		right: '-100%',
	}, 400);
	$('#tweetsModal .modal-body').scrollTop(0);
	$('#tweetsModal .modal-body').css('bottom', 0);
	$('#back2replies, #reply-footer').addClass('hide');
	$('#reply-input, #btn-chat').attr('disabled', 'disabled');
});
// End of the script

// Send tweet reply on right side modal
$(document).on('click', '#btn-chat', function(e) {
	e.preventDefault();

	var btn = $(this);
	btn.html('<img src="' + siteurl + 'images/loading.gif" />').attr('disabled', 'disabled');
	var tweetmsg = $("#reply-input").val();
	var tweetid = $(this).attr('data-tweetid');
	var screenname = $(this).attr('data-screenname');
	if ( tweetmsg.length != 0 && tweetmsg.length <= 140 && tweetmsg.indexOf('@' + curr_screenname + ' ') != -1 && tweetmsg != '@' + curr_screenname + ' ' ) {
		$.ajax({
			type 	 : 'POST',
			dataType : "json",
			async	 : false,
			cache	 : false,
			url 	 : siteurl + 'app-ajax/?case=SubmitLeadReplies',
			data 	 : {
				'tweetid' 	 : tweetid,
				'tweetmsg'   : tweetmsg,
				'screenname' : screenname
			},
			success  : function(data) {
				if ( data.html.error ) {
					$('#conversations-content .chat .appended').remove();
					$('#conversations-content .chat').append('<li class="appended clearfix">' + data.html.error + '</li>');
					setTimeout(function() {
						$('#conversations-content .chat .appended').remove();
					}, 3000);
				} else {
					html = '';
					$('#conversations-content .chat .appended').remove();
					var conversation = data.html;
					html += '<li class="left clearfix">';
						html += '<span class="chat-img pull-left">';
							html += '<img src="' + conversation.tweets.thumbnail + '" alt="' + conversation.tweets.name + '" class="img-circle" />';
						html += '</span>';
						html += '<div class="chat-body clearfix">';
							html += '<div class="header">';
								html += '<strong class="primary-font">' + conversation.tweets.name + '</strong>';
							html += '</div>';
							html += '<p>' + conversation.tweets.content + '</p>';
						html += '</div>';
					html += '</li>';
					$('#conversations-content .chat').append(html);
					$('#tweetsModal .modal-body').scrollTop($('.chat').height());
					$('#reply-input').val('@' + screenname + ' ');
				}
				btn.html('Send').removeAttr('disabled');
			}
		}); 
	} else {
		if ( tweetmsg.length == 0 || tweetmsg == '@' + curr_screenname + ' ' ) {
			alert('Please enter your tweet before send.');
		} else if ( tweetmsg.length > 140 ) {
			alert('Please enter your tweet within 140 characters.');
		} else if ( tweetmsg.indexOf('@' + curr_screenname + ' ') == -1 ) {
			alert('Please do not remove the \'@screenname \' from reply box.');
		}
		btn.html('Send').removeAttr('disabled');
	}
});
// End of the script