<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . "/config/config.php");
if ( !isset($_SESSION['AdminUserLoggedIn']) ) {
	header('location:' . SITE_URL . 'admin/');
	return false;
}
if(isset($_REQUEST['blogID'])){
	$blogSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "blogs` WHERE `Blog_ID` = ".$_REQUEST['blogID']."");
	if ( $blogSQL ) {
		$blogCount = mysql_num_rows($blogSQL);
		if($blogCount > 0){
			$blog_result = mysql_fetch_assoc($blogSQL);
			if($blog_result['Blog_Date'] != ''){
				$blog_date = date('m/d/Y', strtotime($blog_result['Blog_Date']));
				
			} else {
				$blog_date =  '';
			}
		}
	} 
}


?>

<?php $page_name = 'New Blog'; ?>

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
							<a class="close session_message" title="close" aria-label="close" data-dismiss="alert" href="#">×</a><?php echo $_SESSION['error_text']; ?>
						</div> 
					<?php } ?>
					<?php 
					if ( isset($_SESSION['success_text']) && !empty($_SESSION['success_text']) ) { ?>
						<div class="alert alert-success session-success">
							<a class="close session_message" title="close" aria-label="close" data-dismiss="alert" href="#">×</a><?php echo $_SESSION['success_text']; ?>
						</div> 
					<?php } ?>
					
                	<div class="widget widget-table action-table">
                        <div class="widget-header">
                            <i class="icon-th-list"></i>
                            <h3><?php echo (isset($_REQUEST['blogID']) ? 'Edit Blog' : 'New Blog'); ?></h3>
                        </div>
                        <div class="widget-content">
							<div style="padding:15px;">
								<div class="alert alert-danger hide vad-error"></div>
								<form id="new_blog_form" method="post" class="form-horizontal" enctype="multipart/form-data" action="" >
									<fieldset>
										<div class="control-group"> 
											<label for="BlogName" class="control-label">Blog Title<font color="#f00">*</font></label>
											<div class="controls">
												<input type="text" class="span10" id="blogName" name="blogName" placeholder="Enter Blog Title" value="<?php echo (isset($_REQUEST['blogID'])?$blog_result['Blog_Title']:''); ?>"/>
											</div>
										</div>
										<div class="control-group"> 
											<label for="category" class="control-label">Select Category<font color="#f00">*</font></label>
											<div class="controls">
												<select class="span10" id="blogCategory" name="blogCategory">
													<?php 
													$categorySQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "product_categories` ORDER BY `category_ID` DESC");
													if ( $categorySQL ) {
														if ( mysql_num_rows($categorySQL) > 0 ) { ?>
															<option value="">Select Category for your blog</option>
															<?php 
															while ( $resultset = mysql_fetch_assoc($categorySQL) ) { 
																if ( isset($_REQUEST['blogID'])){
																	if ($blog_result['Category_ID'] == $resultset['category_ID']){
																		$selected = 'selected="selected"';
																	} else {
																		$selected = '';
																	}
																} else {
																	$selected = '';
																} ?>
																<option value="<?php echo $resultset['category_ID']; ?>" <?php echo $selected ?>>
																	<?php echo $resultset['category_Name']; ?>
																</option>
															<?php 
															}
														} else { ?>
															<option value="">No Category Available</option>
														<?php 
														}
													} ?>
												</select>
											</div>
										</div>
										<div class="control-group"> 
											<label for="product" class="control-label">Select Product<font color="#f00">*</font></label>
											<div class="controls">
												<select class="span10" id="blogProduct" name="blogProduct">
													<?php 
													$productSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "products` WHERE `category_ID` = " . $blog_result['Category_ID'] . " ORDER BY `product_ID` DESC");
													if ( $productSQL ) {
														if ( mysql_num_rows($productSQL) > 0 ) { ?>
															<option value="">Select Product for your blog</option>
															<?php 
															while ( $resultset = mysql_fetch_assoc($productSQL) ) { 
																if ( isset($_REQUEST['blogID'])){
																	if ($blog_result['Product_ID'] == $resultset['product_ID']){
																		$selected = 'selected="selected"';
																	} else {
																		$selected = '';
																	}
																} ?>
																<option value="<?php echo $resultset['product_ID']; ?>" <?php echo $selected ?>>
																	<?php echo $resultset['product_name']; ?>
																</option>
															<?php 
															}
														} else { ?>
															<option value="">No Products Available</option>
														<?php 
														}
													} ?>
												</select>
											</div>
										</div>
										<div class="control-group"> 
											<label for="BlogContentDesc" class="control-label">Blog Content<font color="#f00">*</font></label>
											<div class="controls">
												<textarea class="span10" name="blogContentDesc" id="blogContentDesc"><?php echo (isset($_REQUEST['blogID'])?$blog_result['Blog_Content']:''); ?></textarea>
											</div>
											<script>
											  CKEDITOR.replace( 'blogContentDesc' );
											</script>
										</div>
										<div class="control-group"> 
											<label for="categoryDesc" class="control-label">Blog Date</label>
											<div class="controls">
												<input type="text" id="blogDate" name="blogDate" class="span4 datepicker" value="<?php echo (isset($_REQUEST['blogID']) ? $blog_date : ''); ?>" placeholder="MM/DD/YYYY" />
											</div>
										</div>
										<div class="control-group"> 
											<div class="controls">
												<input type="hidden" name="blogID" id="blogID" value="<?php echo (isset($_REQUEST['blogID']) ? $_REQUEST['blogID'] : ''); ?>" />
												<button type="button" name="blog_create" id="blog_create" class="btn btn-warning"><?php echo (isset($_REQUEST['blogID']) ? 'Update Blog' : 'Create Blog'); ?></button>&nbsp;&nbsp;&nbsp;&nbsp;
												<a href="<?php echo SITE_URL; ?>admin/blogs/" class="btn btn-default">Cancel</a>
											</div>
										</div>
									</fieldset>
								</form>
							</div>
						</div>
						<div class="modal-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(dirname(__FILE__) . '/templates/footer.php'); ?>