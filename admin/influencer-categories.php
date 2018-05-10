<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . "/config/config.php");

if ( !isset($_SESSION['AdminUserLoggedIn']) ) {
	header('location:' . SITE_URL . 'admin/');
	return false;
}

$msg = '';
if ( isset($_POST['save']) ) {
	$twitteroauth = new TwitterOAuth(ADMIN_CONSUMER_KEY, ADMIN_CONSUMER_SECRET, ADMIN_ACCESS_TOKEN, ADMIN_TOKEN_SECRET);
	
	$screen_name_array = array(); $existing_screennames = array();
	$category_id = addslashes($_POST['category_id']);
	$category 	 = addslashes(trim($_POST['category']));
	$influencers = $_POST['influencer'];
    if ( $category != false ) {
    	if ( !empty($category_id) ) {
    	    $myPrevInfluencers = array();
    		$nextCheckCatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers` WHERE `influence_category_id` = $category_id");
    		if ( $nextCheckCatSQL ) {
    			if ( mysql_num_rows($nextCheckCatSQL) > 0 ) {
    				while ( $nextCheckCatData = mysql_fetch_assoc($nextCheckCatSQL) ) {
    					$myPrevInfluencers[$nextCheckCatData['influncer_twitter_id']] = $nextCheckCatData['influncer_twitter_screenname'];
    				}
    			}
    		}
    		foreach ( $influencers as $influencer ) {
    			$influencer = trim($influencer, '@');
    			$screen_name_array[] = $influencer;
    			$checkCatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers` WHERE `influence_category_id` = $category_id AND `influncer_twitter_screenname` = '$influencer'");
    			if ( $checkCatSQL ) {
    				if ( mysql_num_rows($checkCatSQL) == 0 ) {
    					$user_details 	= $twitteroauth->get('users/show', array("screen_name" => urlencode($influencer)));
                        if ( !isset($user_details->errors) ) {
    					   mysql_query("INSERT INTO `" . DB_PREFIX . "influencers`(`user_id`, `influence_category_id`, `influncer_twitter_id`, `influncer_twitter_screenname`, `influncer_twitter_bio`, `influencer_website`, `influencer_profile_pic`) VALUES('', $category_id, '" . $user_details->id_str . "', '" . $user_details->screen_name . "', '" . addslashes($user_details->description) . "', '" . $user_details->url . "', '" . $user_details->profile_image_url . "')");
                        } else {
                            $msg = '<div class="alert alert-danger session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Twitter API violation occured, please try again after 15 min.</div>';
                        }
    				}
    			}
    		}
    		if ( count($myPrevInfluencers) > 0 ) {
    			foreach ($myPrevInfluencers as $key => $value) {
    				if ( ! in_array($value, $screen_name_array) ) {
    					mysql_query("DELETE FROM `" . DB_PREFIX . "influencers` WHERE `influncer_twitter_id` = '$key'");
    				}
    			}
    		}	
    		$msg = '<div class="alert alert-success session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Category is successfully updated.</div>';		
    	} else {
    		$checkCatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencer_categories` WHERE `twscrapp_category_name` = '" . addslashes($category) . "'");
    		if ( $checkCatSQL ) {
    			if ( mysql_num_rows($checkCatSQL) == 0 ) {
    				$catSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "influencer_categories`(`twscrapp_category_name`) VALUES ('" . addslashes($category) . "')");
    				$category_id = mysql_insert_id();
    				foreach ( $influencers as $influencer ) {
    					$influencer = trim($influencer, '@');
    					$screen_name_array[] = $influencer;

    					$user_details 	= $twitteroauth->get('users/show', array("screen_name" => urlencode($influencer)));
                        if ( !isset($user_details->errors) ) {
    					   mysql_query("INSERT INTO `" . DB_PREFIX . "influencers`(`user_id`, `influence_category_id`, `influncer_twitter_id`, `influncer_twitter_screenname`, `influncer_twitter_bio`, `influencer_website`, `influencer_profile_pic`) VALUES('', $category_id, '" . $user_details->id_str . "', '" . $user_details->screen_name . "', '" . addslashes($user_details->description) . "', '" . $user_details->url . "', '" . $user_details->profile_image_url . "')");
                        } else {
                            $msg = '<div class="alert alert-danger session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Twitter API violation occured, please try again after 15 min.</div>';
                        }
    				}
    				$msg = '<div class="alert alert-success session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>A new category is successfully added.</div>';
    			} else {
    				$msg = '<div class="alert alert-danger session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Category name is already exist.</div>';
    			}
    		} else {
    			$msg = '<div class="alert alert-danger session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Category is not created, try again.</div>';
    		}
    	}
    } else {
        $msg = '<div class="alert alert-danger session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Category Name cannot be blank.</div>';
    }
} ?>

<?php $page_name = 'Influencer Categories'; ?>

<?php include(dirname(__FILE__) . '/templates/header.php'); ?>

<?php include(dirname(__FILE__) . '/templates/topbar.php'); ?>
    
<?php include(dirname(__FILE__) . '/templates/navbar.php'); ?>

<div class="main">
	<div class="main-inner">
    	<div class="container">
        	<div class="row">
                <div class="span12" id="mssgArea">
                    <?php if ( isset($_SESSION['delCat']) ) { ?>
                        <div class="alert alert-success session-success">
                            <a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>
                            <?php echo $_SESSION['delCat']; ?>
                        </div>
                    <?php } elseif ( $msg ) { ?>
                        <?php echo $msg; ?>
                    <?php } ?>
                </div>
            	<div class="span7">
                	<div class="widget widget-table action-table">
                        <div class="widget-header">
                            <i class="icon-th-list"></i>
                            <h3>Influencer Categories</h3>
                        </div>
                        <div class="widget-content">
                        	<?php 
							$SelectCatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencer_categories` ORDER BY `twscrapp_category_id` DESC");
							if ( $SelectCatSQL ) {
								$SelectCatRow = mysql_num_rows($SelectCatSQL);
							} else {
								$SelectCatRow = 0;
							} ?>
                            <div style="padding:15px;">
                                <table id="influencer-category" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Category ID</th>
                                            <th>Category Name</th>
                                            <th class="td-actions">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php if ( $SelectCatRow > 0 ) { ?>
    										<?php while ( $CatData = mysql_fetch_assoc($SelectCatSQL) ) { ?>
                                                <tr>
                                                    <td class="Cat_ID"><?php echo $CatData['twscrapp_category_id']; ?></td>
                                                    <td class="Cat_Name"><?php echo stripcslashes($CatData['twscrapp_category_name']); ?></td>
                                                    <td class="td-actions">
                                                    	<?php $influencers = '';
    													$InflunceSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers` WHERE `influence_category_id` = " . $CatData['twscrapp_category_id']);
    													if ( $InflunceSQL ) {
    														while ( $InfluenceData = mysql_fetch_assoc($InflunceSQL) ) {
    															$influencers .= $InfluenceData['influncer_twitter_screenname'] . ',';
    														}
    														$influencers = substr($influencers, 0, -1);
    													} ?>
                                                    	<input type="hidden" name="influencers" value="<?php echo $influencers; ?>" />
                                                        <a href="#" class="btn btn-small btn-success InfEditBtn">
                                                            <i class="btn-icon-only icon-edit"> </i>
                                                        </a>
                                                        <a href="#" data-href="<?php echo SITE_URL; ?>admin/delete-category/?Cat=<?php echo $CatData['twscrapp_category_id']; ?>" class="btn btn-danger btn-small delete_influ_category">
                                                            <i class="btn-icon-only icon-remove"> </i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                        	<tr>
                                                <td colspan="3">No influencer categories are available.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
            	<div class="span5">
                	<div class="widget">
                    	<div class="widget-header">
                        	<i class="icon-list-alt"></i>
                            <h3>Create Category</h3>
                        </div>
                        <div class="widget-content">
							<form action="" method="post" id="categoryform">
                            	<input type="hidden" id="category_id" name="category_id" value="" />
                            	<fieldset>
                                  	<div class="control-group">
                                    	<label class="control-label" for="category">Category Name<font color="#f00">*</font></label>
                                        <div class="controls">
                                        	<input type="text" class="span4" id="category" name="category" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                    	<label class="control-label" for="influencer">Influencer Name<font color="#f00">*</font></label>
                                        <div class="controls">
                                        	<ul name="influencer[]" id="demo4"></ul>
                                            <small>Hit 'Enter/Tab/Comma/Space' after writing an Influencer Name (Max 10 Influencers).</small>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                    	<button type="submit" class="btn btn-primary" name="save">Save</button> 
                                        <button type="reset" class="btn" id="cancel">Cancel</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal hide fade in" id="icategory_modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-twitter-square"></i> Delete Category</h4>
            </div>
            <div class="modal-body">Are you confirming to delete this category?</div>
            <div class="modal-footer">
                <span class="post_msg"></span>
                <button type="button" data-dismiss="modal" class="btn btn-primary delete_infCat">Delete</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div> 
        </div>
    </div>
</div>

<?php include(dirname(__FILE__) . '/templates/footer.php'); ?>
<?php unset($_SESSION['delCat']); ?>