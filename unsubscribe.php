<?php /*! Unsubscribe of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
    header('location:' . SITE_URL);
}

$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
$Customer = $helper->getCustomerById($Cust_ID);

$page_title = 'Unsubscription';
require_once(dirname(__FILE__) . "/templates/header.php"); ?>

<div class="col-xs-12 col-sm-9 col-md-10 col-lg-10 content">
    <div class="group_content_topbar">
        <h2><i class="fa fa-flag"></i> Unsubscription</h2>
    </div>
    <div class="row">
        <div class="nano">
            <div class="nano-content">
                
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">Tell us why you want to cancel your subscription?</h4>
                        </div>
                        <div class="panel-body">
                            <form action="<?php echo SITE_URL; ?>app-ajax/?case=UnsubscribeCustomer" id="unsubscribe_form" method="post">
                                <div id="unsubMsg"></div>
                                <?php
                                $check_sql = mysql_query("SELECT * FROM `" . DB_PREFIX . "unsubscriptions` WHERE `Cust_ID` = $Cust_ID LIMIT 0, 1");
                                if ( $check_sql ) {
                                    $numrows = mysql_num_rows($check_sql);
                                } else {
                                    $numrows = 0;
                                }
                                if ( $numrows == 0 ) { ?>

                                    <div class="form-group">
                                        <label class="control-label" for="name">Name<small class="text-danger">*</small></label>
                                        <input type="text" name="name" id="name" class="form-control" value="<?php echo $Customer['Cust_FirstName']; ?> <?php echo $Customer['Cust_LastName']; ?>" readonly />
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email<small class="text-danger">*</small></label>
                                        <input type="email" name="email" id="email" class="form-control" value="<?php echo $Customer['Cust_Email']; ?>" readonly />
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="cancel_reason">Reason of Unsubscription<small class="text-danger">*</small></label>
										<select class="form-control" name="cancel_reason" id="cancel_reason">
                                            <option value="">Select Reason</option>
    										<?php 
    											$cancelReasonSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "unsubscription_reasons`");
    											if($cancelReasonSQL){
    												if(mysql_num_rows($cancelReasonSQL) > 0){
    													while($row = mysql_fetch_assoc($cancelReasonSQL)){
    														echo "<option value=".$row['Reason'].">".$row['Reason']."</option>";
    													}	
    												}
    											}
    										?>  
                                        </select>
                                    </div>
                                    <div class="form-group hide">
                                        <label class="control-label" for="other">Other Reason<small class="text-danger">*</small></label>
                                        <input type="text" name="other" id="other" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="description">Description</label>
                                        <textarea class="form-control" name="description" id="description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info btn-md"><i class="fa fa-arrow-right"></i> Submit Request</button>&nbsp;&nbsp;&nbsp;
                                        <div class="loader hide"></div>
                                    </div>
                                <?php } else { ?>
                                    <div id="unsubMsg">
                                        <div class="alert alert-success alert-dismissible unsu" role="alert"><i class="fa fa-info-circle"></i> You have already submitted your cancel request. Social Sonic team will contact you soon.</div>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
    			</div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                        <ul>
                            <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
                            <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
                            <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply dummy text of the printing</li>
                        </ul>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                        </div>
                    </div>
                </div>

    		</div>
    	</div>
    </div>
</div>

<div class="modal fade in" id="cancel_subscription_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Do You Really cancel the EngageWise subscription. </div>
            <div class="modal-body">Are you sure you want to delete this Traffic Campaign?</div>
            <div class="modal-footer">
                <input type="hidden" name="facebook_user_id" id="facebook_user_id" />
                <button type="button" class="btn btn-default" data-dismiss="modal">No, thanks</button>
                <a class="btn btn-danger btn-ok cancel_confirm">Yes, Cancel subscription</a>
            </div>
        </div>
    </div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>