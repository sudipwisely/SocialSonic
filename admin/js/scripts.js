var admin_url = $('#admin_url').val();
$(document).ready(function() {
	$('.datepicker').datepicker({
		format: 'mm/dd/yyyy',
		startDate: '0d'
	}).on('changeDate', function(ev){
	    $(this).datepicker('hide');
	});

	if ( $('#blogContentDesc').length > 0 ) {
	    var ckeditor = CKEDITOR.instances['blogContentDesc'];
	    ckeditor.on('focus', function(){
	        $('.datepicker').datepicker('hide');
	    });
	}

	var admin_url = $('#admin_url').val();
	var table = $('#customers-table').DataTable({
		"processing": true,
		"serverSide": true,
		"ajax": admin_url + "admin-ajax/?case=GetAllCustomersForAdminDashboard",
		"columnDefs": [{
			"targets": 3,
			"orderable": false
		}, {
			"targets": 4,
			"orderable": false
		}, {
			"targets": 5,
			"orderable": false
		}, {
			"targets": 6,
			"orderable": false
		}, {
			"targets": -1,
			"orderable": false,
			"data": null,
			"defaultContent": '<a href="#" class="btn btn-success MoreDetailsBtn"><i class="btn-icon-only icon-arrow-right"></i></a>'
		}]
	});
	$('#customers-table tbody').on('click', '.MoreDetailsBtn', function(e) {
		e.preventDefault();

		$('.dataTables_processing').show();
		var data = table.row($(this).parents('tr')).data();
		$.ajax({
			type: 'GET',
			url: admin_url + 'admin-ajax/?case=CustomerModalData',
			data: 'Cust_ID=' + data[0],
			dataType: "json",
			success: function(response) {
				$('#general').html(response.general);
				$('#twitter').html(response.twitter);
				$('#keyword').html(response.keyword);
				$('#category').html(response.category);
				$('#tweets').html(response.tweets);
				$('#dm').html(response.dm);
				$('#upgrade').html(response.upgrade);
				$('#customerModal').modal('show');
				$('.dataTables_processing').hide();
			}
		});
	});

});

$(document).on('click', '.upgrade_cust_status', function(e) {
	e.preventDefault();

	var custid = $(this).data('custid');
	$.ajax({
		type: 'GET',
		url: admin_url + 'admin-ajax/?case=UpgradeCustomerStatus',
		data: 'Cust_ID=' + custid,
		dataType: "json",
		success: function(response) {
			window.location.href = admin_url + "dashboard/";
		}
	});
});

$(function() {
	$('#categoryform').submit(function() {
		if ( $('#category').val() == false ) {
			$('#mssgArea').html('<div class="alert alert-danger session-success"><a class="close session_message" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Category Name cannot be blank.</div>');
			return false;
		}
		var influencers = $('#demo4 li').text();
		if ( influencers == false ) {
			$('#mssgArea').html('<div class="alert alert-danger session-success"><a class="close session_message" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Influencer Name cannot be blank.</div>');
			return false;
		} else if ( !influencers.match(/^[A-Za-z0-9_@]+$/i) ) {
			$('#mssgArea').html('<div class="alert alert-danger session-success"><a class="close session_message" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Influencer Name cannot contain any special characters.</div>');
			return false;
		}
	});
})

$(function () {
	var admin_url = $('#admin_url').val();
	$('.delete_influ_category').click(function(e) {
		e.preventDefault();

		var href = $(this).data('href'); 
		$('.delete_infCat').attr('id', href);
		$('#icategory_modal_delete').modal('show');
		$('.delete_infCat').click(function(e) {
			e.preventDefault();

			window.location.href = $(this).attr('id');
	    });
	});
});

$(function () {
	var admin_url = $('#admin_url').val();
	$('.delete_canc_reason').click(function(e) {
		e.preventDefault();

		var href = $(this).data('href'); 
		$('.delete_caRes').attr('id', href);
		$('#ireason_modal_delete').modal('show');
		$('.delete_caRes').click(function(e) {
			e.preventDefault();

			window.location.href = $(this).attr('id');
	    });
	});
});

$(function () {
	$('#demo4').tagit({
		maxTags		: 10,
		triggerKeys : ['enter'],
		select		: true
	});
});

$(function() {
	$('.ResEditBtn').click(function(e) {
		e.preventDefault();
		
		var parent = $(this).parents('tr');
        var Reason_ID = parent.find('.Reason_ID').text();
		var Reason_Name = parent.find('.Reason_Name').text();
		
		$('#cancel_id').val(Reason_ID);
		$('#reason').val(Reason_Name);
    });
});

$(function() {
	$('.InfEditBtn').click(function(e) {
		e.preventDefault();
		
		var parent = $(this).parents('tr');
        var Cat_ID = parent.find('.Cat_ID').text();
		var Cat_Name = parent.find('.Cat_Name').text();
		var influencers = parent.find('input[name="influencers"]').val();
		
		$('#category_id').val(Cat_ID);
		$('#category').val(Cat_Name).attr('readonly', 'readonly');
		$('#demo4').tagit("reset")
		$('#demo4').tagit('add', influencers);
    });
});

$(function() {
	$('.product_category_modal_button').click(function(e) {
		e.preventDefault();
		
		if($('#categoryname').val() != false){
			$('#categoryname').val('');
		}
		if($('#categoryDesc').val() != false){
			$('#categoryDesc').val('');
		}
		if($('.vad-error').removeClass('hide')){
			$('.vad-error').html('');
			$('.vad-error').addClass('hide');
		}
		$('#category_modal').modal('show');
    });
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('#product_category_form').ajaxForm({
		beforeSend: function(xhr, opts) {
			var text = '';
			var categoryID = $('#categoryID').val();
			var categoryname = $('#categoryname').val();
			var categoryInput = $('#categoryInput').val();
			var formdata = $('#product_category_form').serialize();
			if ( categoryname == false ) {
				text = "Category Name cannot be blank."; 
				$('.vad-error').removeClass('hide');
				$('.vad-error').html(text);
				xhr.abort();
				return false;
			} else if ( /^[a-zA-Z0-9- &+'!]*$/.test(categoryname) == false ) {
				text = "Category cannot contains illegal characters.";
				$('.vad-error').removeClass('hide');
				$('.vad-error').html(text);
				xhr.abort();
				return false;
			}
			if ( categoryID == '' ) {
				if ( categoryInput == false ) {
					text = "Category Icon cannot be blank."; 
					$('.vad-error').removeClass('hide');
					$('.vad-error').html(text);
					xhr.abort();
					return false;
				} else {
					var extension = categoryInput.split('.').pop().toUpperCase();
					if ( extension != "PNG" && extension != "JPG" && extension != "GIF" && extension != "JPEG" && extension != "BMP" ) {
						text = "This is a invalid type of file."; 
						$('.vad-error').removeClass('hide');
						$('.vad-error').html(text);
						xhr.abort();
						return false;
					} else if ( $("#categoryInput")[0].files[0].fileSize > 1048576 ) {
						text = "You can not upload file more than 1 MB."; 
						$('.vad-error').removeClass('hide');
						$('.vad-error').html(text);
						xhr.abort();
						return false;
					}
				}
			} else {
				if ( categoryInput != false ) {
					var extension = categoryInput.split('.').pop().toUpperCase();
					if ( extension != "PNG" && extension != "JPG" && extension != "GIF" && extension != "JPEG" && extension != "BMP" ) {
						text = "This is a invalid type of file."; 
						$('.vad-error').removeClass('hide');
						$('.vad-error').html(text);
						xhr.abort();
						return false;
					} else if ( $("#categoryInput")[0].files[0].fileSize > 1048576 ) {
						text = "You can not upload file more than 1 MB."; 
						$('.vad-error').removeClass('hide');
						$('.vad-error').html(text);
						xhr.abort();
						return false;
					}
				}
			}
		},
		complete: function(xhr) {
			var data = xhr.responseText;
			$('#category_modal').modal('hide');
			window.location.href = admin_url + "product-categories/";
		}
	});
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('.edit_product_category').click(function(e) {
		e.preventDefault();

		$('#category_modal h4').text('Edit Product Category');
		$('#product_category_create').text('Update');
		$.ajax({
			type: 'GET',
			url: admin_url + 'admin-ajax/?case=GetproductCategory',
			data: 'category_id=' + $(this).data('id'),
			dataType: "json",
			success: function(data) {
				$('#categoryname').val(data[1]);
				$('#categoryDesc').val(data[2]);
				$('#categoryID').val(data[0]);
				$('.image-name').removeClass('hide');
				$('.image-name').html('<img src="' + admin_url + 'uploads/categories/' + data[3] + '" width="60" height="60" />');
				$('#category_modal').modal('show');
			}
		});
    });
});

$(function() {
	$('#category_modal').on('hidden', function () {
		$('#category_modal h4').text('Add Product Category');
		$('#product_category_create').text('Create');
		$('#categoryID').val('');
	    $('#product_category_form')[0].reset();
	    $('.image-name').empty();
	});
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('.delete_product_category').click(function(e) {
		e.preventDefault();

		$.ajax({
			type: 'GET',
			url: admin_url + 'admin-ajax/?case=GetproductCategory',
			data: 'category_id=' + $(this).data('id'),
			dataType: "json",
			success: function(data) {
				$('.delete').attr('data-id', data[0]);
				$('#category_modal_delete').modal('show');
			}
		});
    });
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('.delete').click(function(e) {
		e.preventDefault();

		$.ajax({
			type: 'GET',
			url: admin_url + 'admin-ajax/?case=DeleteproductCategory',
			data: 'category_id=' + $(this).data('id'),
			success: function(data) {
				window.location.href = admin_url + "product-categories/"
			}
		});
    });
});

$(function() {
	$('.product_modal_button').click(function(e) {
		e.preventDefault();

		if ( $('#productName').val() != false ) {
			$('#productName').val('');
		}
		if ( $('#category_select').val() != false ) {
			$('#category_select').val('');
		}
		if ( $('#funnelUrl').val() != false ) {
			$('#funnelUrl').val('');
		}
		if ( $('#vendorID').val() != false ) {
			$('#vendorID').val('');
		}
		if ( $('.vad-error').removeClass('hide') ) {
			$('.vad-error').html('');
			$('.vad-error').addClass('hide')
		}
		$('#product_modal').modal('show');
    });
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('#product_form').ajaxForm({
		beforeSend: function(xhr, opts) {
			var text = '';
			var productID = $('#productID').val();
			var productname = $('#productName').val();
			var vendorID = $('#vendorID').val();
			var categoryname = $('#category_select').val();
			var funnelUrl = $('#funnelUrl').val();
			var productFile = $('#productFile').val();
			if ( productname == false ) {
				text = "Product Name cannot be blank."; 
				$('.vad-error').removeClass('hide');
				$('.vad-error').html(text);
				xhr.abort();
				return false;
			}
			if ( vendorID == false ) {
				text = "Please mention Product Vendor ID.";
				$('.vad-error').removeClass('hide');
				$('.vad-error').html(text);
				xhr.abort();
				return false;
			}
			if ( categoryname == false ) {
				text = "Product should be present in any of the category.";
				$('.vad-error').removeClass('hide');
				$('.vad-error').html(text);
				xhr.abort();
				return false;
			}
			var re = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
			if ( funnelUrl == false ) {
				text = "Please specify your funnel URL.";
				$('.vad-error').removeClass('hide');
				$('.vad-error').html(text);
				xhr.abort();
				return false;
			} else if ( !re.test(funnelUrl) ) { 
			    text = "Please enter a valid funnel URL.";
				$('.vad-error').removeClass('hide');
				$('.vad-error').html(text);
				xhr.abort();
				return false;
			}
			if ( productID == false ) {
				if ( productFile == false ) {
					text = "Product Image cannot be blank."; 
					$('.vad-error').removeClass('hide');
					$('.vad-error').html(text);
					xhr.abort();
					return false;
				} else {
					var extension = productFile.split('.').pop().toUpperCase();
					if ( extension != "PNG" && extension != "JPG" && extension != "GIF" && extension != "JPEG" && extension != "BMP" ) {
						text = "This is a invalid type of file."; 
						$('.vad-error').removeClass('hide');
						$('.vad-error').html(text);
						xhr.abort();
						return false;
					}
				}
			} else {
				if ( productFile != false ) {
					var extension = productFile.split('.').pop().toUpperCase();
					if ( extension != "PNG" && extension != "JPG" && extension != "GIF" && extension != "JPEG" && extension != "BMP" ) {
						text = "This is a invalid type of file."; 
						$('.vad-error').removeClass('hide');
						$('.vad-error').html(text);
						xhr.abort();
						return false;
					}
				}
			}
		},
		complete: function(xhr) {
			var data = xhr.responseText;
			$('#product_modal').modal('hide');
			window.location.href = admin_url + "products/";
		}
	});
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('.edit_product').click(function(e) {
		e.preventDefault();

		$('#product_modal h4').text('Edit Product');
		$('#product_create').text('Update');
		$.ajax({
			type: 'GET',
			url: admin_url + 'admin-ajax/?case=Getproduct',
			data: 'product_id=' + $(this).data('id'),
			dataType: "json",
			success: function(data) {
				$('#productID').val(data[0]);
				$('#category_select').val(data[1]);
				$('#vendorID').val(data[2]);
				$('#productName').val(data[3]);
				$('.product-image-name').removeClass('hide');
				$('.product-image-name').html('<img src="' + admin_url + 'uploads/products/' + data[4] + '" width="60" height="60" />');
				$('#product_modal').modal('show');
				$('#funnelUrl').val(data[5]);
			}
		});
    });
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('.delete_product').click(function(e) {
		e.preventDefault();

		$.ajax({
			type: 'GET',
			url: admin_url + 'admin-ajax/?case=Getproduct',
			data: 'product_id=' + $(this).data('id'),
			dataType: "json",
			success: function(data) {
				$('.product-delete').attr('data-id', data[0]);
				$('#product_modal_delete').modal('show');
			}
		});
    });
});

$(function() {
	$('#product_modal').on('hidden', function () {
		$('#product_modal h4').text('Add Product');
		$('#product_create').text('Create');
		$('#productID').val('');
	    $('#product_form')[0].reset();
	    $('.product-image-name').empty();
	});
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('.product-delete').click(function(e) {
		e.preventDefault();

		$.ajax({
			type: 'GET',
			url: admin_url + 'admin-ajax/?case=Deleteproduct',
			data: 'product_id=' + $(this).data('id'),
			success: function(data) {
				window.location.href = admin_url + "products/";
			}
		});
    });
});

$(function(){
	var admin_url = $('#admin_url').val();
	$('#blog_create').click(function(e) {
		e.preventDefault();

		var text;
		if ( $('#blogName').val() == false ) {
			$('.vad-error').removeClass('hide');
			text = "Please enter the blog title.";
			$('.vad-error').html(text);
			return false;
		} else if ( $('#blogCategory').val() == false ) {
			$('.vad-error').removeClass('hide');
			text = "Please choose one category";
			$('.vad-error').html(text);
			return false;
		} else if ( $('#blogProduct').val() == false ) {
			$('.vad-error').removeClass('hide');
			text = "Please choose one product";
			$('.vad-error').html(text);
			return false;
		} else if ( $("#cke_blogContentDesc iframe").contents().find("body").text() == false ) { 
			$('.vad-error').removeClass('hide');
			text = "Please write the blog";
			$('.vad-error').html(text);
			return false;
		} else {
			var editor = CKEDITOR.instances['blogContentDesc'].getData();
			$.ajax({
				type: 'POST',
				url: admin_url + 'admin-ajax/?case=AddNewBlog',
				data: $('#new_blog_form').serialize() + '&ckdata=' + escape(editor),
				success: function(data) {
					window.location.href = admin_url + "blogs/";
				}
			});
		}
	});
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('.delete-blog').click(function(e) {
		e.preventDefault();

		$.ajax({
			type: 'POST',
			url: admin_url + 'admin-ajax/?case=GetBlog',
			data: 'blog_id=' + $(this).data('id'),
			dataType: "json",
			success: function(data) {
				$('.blog-delete').attr('data-id', data[0]);
				$('#blog_modal_delete').modal('show');
			}
		});
    });
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('.blog-delete').click(function(e) {
		e.preventDefault();

		$.ajax({
			type: 'POST',
			url: admin_url + 'admin-ajax/?case=Deleteblog',
			data: 'blog_id=' + $(this).data('id'),
			success: function(data) {
				window.location.href = admin_url + "blogs/";
			}
		});
    });
});

$(function() {
	var admin_url = $('#admin_url').val();
	$('#blogCategory').change(function() {
		$.ajax({
			type: 'GET',
			url: admin_url+'admin-ajax/?case=GetProductsByCategory',
			data: 'cat_id=' + $(this).val(),
			success: function(data) {
				var html = '<option value="">Select Product for your blog</option>';
				var productsData = data.split('###');
				for ( var i=0; i<productsData.length; i++ ) {
					var products = productsData[i].split('<=>');
					if ( products[0] && products[1] ) {
						html += '<option value="' + products[0] + '">' + products[1] + '</option>';
					}
				}
				$('#blogProduct').html(html);
			}
		});
	});
});

$(function() {
	$('#cancel').click(function(e) {
        e.preventDefault();

        $('#category_id').val('');
		$('#categoryform')[0].reset();
		$('#category').removeAttr('readonly');
		$('#demo4').tagit("reset");
    });
});

$(function() {
	$('.session_message').click(function(e) {
		e.preventDefault();

		ResetErrorSession();
    });
});

setInterval(ResetErrorSession, 10000);
function ResetErrorSession(){
	var admin_url = $('#admin_url').val();
	$.ajax({
		type: 'GET',
		url: admin_url + 'admin-ajax/?case=ExpireErrorReport',
		success: function(data) {
			if($('.container').find('.session-success').length > 0){
				$('.container').find('.session-success').addClass('hide');
			}
		}
	});
}