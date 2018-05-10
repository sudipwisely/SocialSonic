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

$blogSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "blogs` ORDER BY `Blog_ID` DESC");
if ( $blogSQL ) {
	$blogCount = mysql_num_rows($blogSQL);
} else {
	$blogCount = 0;
} ?>

<?php $page_name = 'Buzznews Blogs'; ?>

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
                            <h3>Buzznews Blogs</h3>
							<a href="<?php echo SITE_URL; ?>admin/new-blog/" style="margin:5px 15px;" class="btn btn-primary pull-right">Add New Blog</a>
                        </div>
                        <div class="widget-content">
							<div style="padding:15px;">
								<table id="product-category" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th style="text-align:center;" width="10%">Blog ID</th>
											<th width="20%" class="no-sort">Blog Name</th>
											<th width="20%" class="no-sort">Product Name</th>
											<th width="30%" class="no-sort">Creation Date</th>
											<th style="text-align:center;" width="30%" class="no-sort">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if ( $blogCount > 0 ) {
											$i = 0;
											while ( $resultset = mysql_fetch_assoc($blogSQL) ) {
												$i = $i+1; ?>
												<tr>
													<td style="text-align:center;"><strong><?php echo $i; ?><strong></td>
													<td><?php echo $resultset['Blog_Title']; ?></td>
													<?php
													$productSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "products` WHERE `product_ID` = " . $resultset['Product_ID']);
													if ( $productSQL ) {
														$productCount = mysql_num_rows($productSQL);
														if($productCount > 0){
															$product_result = mysql_fetch_assoc($productSQL);
															$product_name = $product_result['product_name'];
														} else {
															$product_name = '';
														}
													} else {
														$productCount = 0;
													} ?>
													<td><?php echo $product_name; ?></td>
													<td><?php echo (isset($resultset['Blog_Date'])? date('m/d/Y', strtotime($resultset['Blog_Date'])):''); ?></td>
													<td style="text-align:center;">
														<a href="<?php echo SITE_URL."admin/new-blog/?blogID=".$resultset['Blog_ID']; ?>" class="btn btn-success"><i class="btn-icon-only icon-edit"> </i></a>
														<a href="#" class="btn btn-danger delete-blog" data-id="<?php echo $resultset['Blog_ID']; ?>"><i class="btn-icon-only icon-remove"> </i></a>
													</td>
												</tr>
									  		<?php } 
									    } else { ?>
									    	<tr>
									    		<td colspan="5">No blogs are available.</td>
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
<div class="modal hide fade in" id="blog_modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog text-left">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-twitter-square"></i> Delete Blog</h4>
            </div>
			<div class="modal-body">Are you confirming to delete this blog?</div>
			<div class="modal-footer">
				<span class="post_msg"></span>
				<button type="button" data-dismiss="modal" class="btn btn-primary blog-delete">Delete</button>
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
			</div> 
        </div>
    </div>
</div>

<?php include(dirname(__FILE__) . '/templates/footer.php'); ?>