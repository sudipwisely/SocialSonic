<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . "/config/config.php");
if ( !isset($_SESSION['AdminUserLoggedIn']) ) {
	header('location:' . SITE_URL . 'admin/');
	return false;
}
$productCategorySQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "product_categories` ORDER BY `category_ID` DESC");
if ( $productCategorySQL ) {
	$productCategoryCount = mysql_num_rows($productCategorySQL);
} else {
	$productCategoryCount = 0;
} ?>

<?php $page_name = 'Product Categories'; ?>

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
                            <h3>Product Categories</h3>
							<a href="#" style="margin:5px 15px;" class="btn btn-primary pull-right product_category_modal_button">Add Category</a>
                        </div>
                        <div class="widget-content">
							<div style="padding:15px;">
								<table id="product-category" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th style="text-align:center;" width="10%">Category ID</th>
											<th style="text-align:center;" width="10%">Category Icon</th>
											<th width="20%">Category Name</th>
											<th width="40%">Category Description</th>
											<th style="text-align:center;" width="20%">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if ( $productCategoryCount > 0 ) {
											$i = 0;
											while ( $resultset = mysql_fetch_assoc($productCategorySQL) ) {
												$i = $i+1; ?>
												<tr>
													<td style="text-align:center;"><?php echo $resultset['category_ID']; ?></td>
													<td style="background:#3288AD;text-align:center;">
														<img width="48" height="48" src="<?php echo SITE_URL; ?>admin/uploads/categories/<?php echo $resultset['category_Icon']; ?>" alt="" />
													</td>
													<td><?php echo $resultset['category_Name']; ?></td>
													<td><?php echo $resultset['category_Description']; ?></td>
													<td style="text-align:center;">
														<a href="#" class="btn btn-success edit_product_category" data-id="<?php echo $resultset['category_ID']; ?>"><i class="btn-icon-only icon-edit"> </i></a>
														<a href="#" class="btn btn-danger delete_product_category" data-id="<?php echo $resultset['category_ID']; ?>"><i class="btn-icon-only icon-remove"> </i></a>
													</td>
												</tr>
									  		<?php } 
									    } else { ?>
									    	<tr>
                                                <td colspan="5">No product categories are available.</td>
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

<div class="modal hide fade in" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog text-left">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-twitter-square"></i> Add Product Category</h4>
            </div>
            <form id="product_category_form" method="post" class="form-horizontal" enctype="multipart/form-data" action="<?php echo SITE_URL; ?>admin/admin-ajax/?case=AddproductCategory" >
				<div class="modal-body">
					<div class="alert alert-danger hide vad-error"></div>
					<input type="hidden" id="categoryID" name="categoryID">
					<fieldset>
						<div class="control-group"> 
							<label for="categoryName" class="control-label">Category Name<font color="#f00">*</font></label>
							<div class="controls">
								<input type="text" class="span4" id="categoryname" name="categoryname" placeholder="Enter Category Name"/>
							</div>
						</div>
						<div class="control-group"> 
							<label for="categoryDesc" class="control-label">Category Description</label>
							<div class="controls">
								<textarea class="span4" name="categoryDesc" id="categoryDesc"></textarea>
							</div>
						</div>
						<div class="control-group"> 
							<label for="categoryDesc" class="control-label">Category Icon<font color="#f00">*</font></label>
							<div class="controls">
								<input id="categoryInput" name="categoryInput" type="file" class="file" data-show-preview="false">
								<span style="background-color:#3288AD;" class="image-name pull-right hide"></span>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="modal-footer">
					<span class="post_msg"></span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" name="product_category_create" id="product_category_create" class="btn btn-warning">Create</button>
				</div> 
			</form>
        </div>
    </div>
</div>

<div class="modal hide fade in" id="category_modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog text-left">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-twitter-square"></i> Delete Category</h4>
            </div>
			<div class="modal-body">Are you confirming to delete this category?</div>
			<div class="modal-footer">
				<span class="post_msg"></span>
				<button type="button" data-dismiss="modal" class="btn btn-primary delete">Delete</button>
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
			</div> 
        </div>
    </div>
</div>

<?php include(dirname(__FILE__) . '/templates/footer.php'); ?>