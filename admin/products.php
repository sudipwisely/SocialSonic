<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . "/config/config.php");
if ( !isset($_SESSION['AdminUserLoggedIn']) ) {
	header('location:' . SITE_URL . 'admin/');
	return false;
}
$productCategorySQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "product_categories`");
if ( $productCategorySQL ) {
	$productCategoryCount = mysql_num_rows($productCategorySQL);
} else {
	$productCategoryCount = 0;
}

$productSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "products` ORDER BY `product_ID` DESC");
if ( $productSQL ) {
	$productCount = mysql_num_rows($productSQL);
} else {
	$productCount = 0;
} ?>

<?php $page_name = 'Products'; ?>

<?php include(dirname(__FILE__) . '/templates/header.php'); ?>

<?php include(dirname(__FILE__) . '/templates/topbar.php'); ?>
    
<?php include(dirname(__FILE__) . '/templates/navbar.php'); ?>

<div class="main">
	<div class="main-inner">
    	<div class="container">
        	<div class="row">
            	<div class="span12">

					<?php 
					if ( isset($_SESSION['error_text']) && !empty($_SESSION['error_text']) ) { ?>
						<div class="alert alert-danger session-success">
							<a class="close session_message" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
							<?php echo $_SESSION['error_text']; ?>
						</div> 
					<?php } ?>
					<?php 
					if ( isset($_SESSION['success_text']) && !empty($_SESSION['success_text']) ) { ?>
						<div class="alert alert-success session-success">
							<a class="close session_message" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
							<?php echo $_SESSION['success_text']; ?>
						</div> 
					<?php } ?>
					
                	<div class="widget widget-table action-table">
                        <div class="widget-header">
                            <i class="icon-th-list"></i>
                            <h3>Products</h3>
							<a href="#" style="margin:5px 15px;" class="btn btn-primary pull-right product_modal_button">Add Product</a>
                        </div>
                        <div class="widget-content">
							<div style="padding:15px;">
								<table id="product-category" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th style="text-align:center;" width="10%">Product ID</th>
											<th width="15%">Product Name</th>
											<th width="15%">Category Name</th>
											<th width="15%">Vendor ID</th>
											<th width="25%">Product URL</th>
											<th style="text-align:center;" width="20%">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if ( $productCount > 0 ) {
											$i = 0;
											while ( $resultset = mysql_fetch_assoc($productSQL) ) {
												$i = $i+1; ?>
												<tr>
													<td style="text-align:center;"><strong><?php echo $resultset['product_ID']; ?><strong></td>
													<td><?php echo $resultset['product_name']; ?></td>
													<?php
													$CategorySQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "product_categories` WHERE `category_ID` = ".$resultset['category_ID']."");
													if ( $CategorySQL ) {
														$CategoryCount = mysql_num_rows($CategorySQL);
														if($CategoryCount > 0){
															$category_result = mysql_fetch_assoc($CategorySQL);
															$category_name = $category_result['category_Name'];
														} else {
															$category_name = '';
														}
													} else {
														$CategoryCount = 0;
													} ?>
													<td><?php echo $category_name; ?></td>
													<td><?php echo $resultset['vendor_Id']; ?></td>
													<td><?php echo $resultset['funnel_url']; ?></td>
													<td style="text-align:center;">
														<a href="#" class="btn btn-success edit_product" data-id="<?php echo $resultset['product_ID']; ?>"><i class="btn-icon-only icon-edit"> </i></a>
														<a href="#" class="btn btn-danger delete_product" data-id="<?php echo $resultset['product_ID']; ?>"><i class="btn-icon-only icon-remove"> </i></a>
													</td>
												</tr>
									  		<?php } 
									    } else { ?>
									    	<tr>
                                                <td colspan="6">No products are available.</td>
                                            </tr>
									    <?php } ?>
									</tbody>
								</table>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal hide fade in" id="product_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog text-left">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-twitter-square"></i> Add Product </h4>
            </div>
            <form id="product_form" name="product_form" method="post" class="form-horizontal" action="<?php echo SITE_URL; ?>admin/admin-ajax/?case=Addproduct">
				<div class="modal-body">
					<div class="alert alert-danger hide vad-error"></div>
					<input type="hidden" id="productID" name="productID">
					<fieldset>
						<div class="control-group"> 
							<label for="productName" class="control-label">Product Name<font color="#f00">*</font></label>
							<div class="controls">
								<input type="text" class="span4" id="productName" name="productName" placeholder="Enter Product Name"/>
							</div>
						</div>
						<div class="control-group"> 
							<label for="vendorID" class="control-label">Vendor ID<font color="#f00">*</font></label>
							<div class="controls">
								<input type="text" class="span4" id="vendorID" name="vendorID" placeholder="Enter Vendor ID"/>
							</div>
						</div>
						<div class="control-group"> 
							<label for="categoryName" class="control-label">Category Name<font color="#f00">*</font></label>
							<div class="controls">
								<select class="form-control" id="category_select" name="category_select">
									<option value=''>Select One Category</option>
									<?php if ( $productCategoryCount > 0 ) { 
										while($caregory_resultset = mysql_fetch_assoc($productCategorySQL)){ ?>
											<option value='<?php echo $caregory_resultset['category_ID']; ?>'><?php echo $caregory_resultset['category_Name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select> 
							</div>
						</div>
						<div class="control-group"> 
							<label for="funnelUrl" class="control-label">Product URL<font color="#f00">*</font></label>
							<div class="controls">
								<input type="text" class="span4" id="funnelUrl" name="funnelUrl" placeholder="Enter Funnel URL (http://www.example.com)"/>
							</div>
						</div>
						<div class="control-group"> 
							<label for="productFile" class="control-label">Product Image<font color="#f00">*</font></label>
							<div class="controls">
								<input id="productFile" name="productFile" type="file" class="file" data-show-preview="false">
								<span class="product-image-name pull-right hide"></span>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="modal-footer">
					<span class="post_msg"></span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" name="product_create" id="product_create" class="btn btn-warning">Create</button>
				</div> 
			</form>
        </div>
    </div>
</div>

<div class="modal hide fade in" id="product_modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog text-left">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-twitter-square"></i> Delete Product</h4>
            </div>
			<div class="modal-body">Are you confirming to delete this product?</div>
			<div class="modal-footer">
				<span class="post_msg"></span>
				<button type="button" data-dismiss="modal" class="btn btn-primary product-delete">Delete</button>
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
			</div> 
        </div>
    </div>
</div>

<?php include(dirname(__FILE__) . '/templates/footer.php'); ?>