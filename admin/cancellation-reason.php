<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . "/config/config.php");

if ( !isset($_SESSION['AdminUserLoggedIn']) ) {
	header('location:' . SITE_URL . 'admin/');
	return false;
}

$msg = '';
if ( isset($_POST['save']) ) {
	$reason_id   = addslashes($_POST['cancel_id']);
	$reason 	 = addslashes(trim($_POST['reason']));
	
    if ( $reason != false ) {
    	if ( !empty($reason_id) ) {
    		$updateReason = mysql_query("UPDATE `" . DB_PREFIX . "unsubscription_reasons` SET `Reason`= '".$reason."' WHERE `Reason_ID` = $reason_id");
			if($updateReason){
				$msg = '<div class="alert alert-success session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Cancellation Reason successfully updated.</div>';	
			} else {
				$msg = '<div class="alert alert-danger session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Server Busy Try again Later.</div>';
			}		
    	} else {
			$InsertReason = mysql_query("INSERT INTO  `" . DB_PREFIX . "unsubscription_reasons` (`Reason`) VALUES ('".$reason."')");
			if($InsertReason){
				$msg = '<div class="alert alert-success session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Cancellation Reason successfully Created.</div>';	
			} else {
				$msg = '<div class="alert alert-danger session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Server Busy Try again Later.</div>';
			}	
    	}
    } else {
        $msg = '<div class="alert alert-danger session-success"><a class="close" title="close" aria-label="close" data-dismiss="alert" href="#">×</a>Reason Field cannot be blank.</div>';
    }
} ?>

<?php $page_name = 'Cancellation Reason'; ?>

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
                            <h3>Cancellation Reason</h3>
                        </div>
                        <div class="widget-content">
                        	<?php 
							$SelectResSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "unsubscription_reasons` ORDER BY `Reason_ID` DESC");
							if ( $SelectResSQL ) {
								$SelectResRow = mysql_num_rows($SelectResSQL);
							} else {
								$SelectResRow = 0;
							} ?>
                            <div style="padding:15px;">
                                <table id="cancel-reason" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Reason ID</th>
                                            <th>Reason </th>
                                            <th class="td-actions">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php if ( $SelectResRow > 0 ) { ?>
    										<?php while ( $ResData = mysql_fetch_assoc($SelectResSQL) ) { ?>
                                                <tr>
                                                    <td class="Reason_ID"><?php echo $ResData['Reason_ID']; ?></td>
                                                    <td class="Reason_Name"><?php echo stripcslashes($ResData['Reason']); ?></td>
                                                    <td class="td-actions">
                                                    	<input type="hidden" name="influencers" value="<?php echo $influencers; ?>" />
                                                        <a href="#" class="btn btn-small btn-success ResEditBtn">
                                                            <i class="btn-icon-only icon-edit"> </i>
                                                        </a>
                                                        <a href="#" data-href="<?php echo SITE_URL; ?>admin/delete-reason/?Res=<?php echo $ResData['Reason_ID']; ?>" class="btn btn-danger btn-small delete_canc_reason">
                                                            <i class="btn-icon-only icon-remove"> </i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                        	<tr>
                                                <td colspan="3">No Reason set by Admin.</td>
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
                            <h3>Create Cancel Reason</h3>
                        </div>
                        <div class="widget-content">
							<form action="" method="post" id="cancelform">
                            	<input type="hidden" id="cancel_id" name="cancel_id" value="" />
                            	<fieldset>
                                  	<div class="control-group">
                                    	<label class="control-label" for="reason">Cancel Reason<font color="#f00">*</font></label>
                                        <div class="controls">
                                        	<input type="text" class="span4" id="reason" name="reason" />
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

<div class="modal hide fade in" id="ireason_modal_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-twitter-square"></i> Delete Cancellation Reason</h4>
            </div>
            <div class="modal-body">Are you confirming to delete this Reason?</div>
            <div class="modal-footer">
                <span class="post_msg"></span> 
                <button type="button" data-dismiss="modal" class="btn btn-primary delete_caRes">Delete</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div> 
        </div>
    </div>
</div>

<?php include(dirname(__FILE__) . '/templates/footer.php'); ?>
<?php unset($_SESSION['delCat']); ?>