<?php /*! Change Password of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL);
}

$page_title = 'Change Password';
require_once(dirname(__FILE__) . "/templates/header.php"); ?>

<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content">
	<div class="group_content_topbar">
        <h2><i class="fa fa-lock"></i> Change Password</h2>
    </div>
    <div class="row">
        <div class="nano">
            <div class="nano-content">
    			<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		    		<div class="row">
		    			<div class="change-password-wrap">
			    			<form action="<?php echo SITE_URL; ?>app-ajax?case=ChangeAndSkipPassword" method="post" id="change-password-form">
			    				<div class="panel panel-default">
									<div class="panel-body">
										<div class="form-group">
							    			<label for="current_password">Current Password: </label>
											<input class="form-control" type="password" id="current_password" name="current_password" />
										</div>
										<div class="form-group">
							    			<label for="new_password">New Password: </label>
											<input class="form-control" type="password" id="new_password" name="new_password" />
										</div>
										<div class="form-group">
							    			<label for="confirm_password">Confirm Password: </label>
											<input class="form-control" type="password" id="confirm_password" name="confirm_password" />
										</div>
										<span class="pull-left" id="passwordMsg"></span>
									</div>
									<div class="panel-footer">
										<div class="text-right">
											<div class="loader pull-left hide"></div>
											<button class="btn btn-default" type="button" id="skip_password">SKIP</button>&nbsp;&nbsp;&nbsp;
											<button class="btn btn-primary" type="submit" name="change_password">CHANGE PASSWORD</button>
										</div>
									</div>
								</div>
							</form>
						</div>
		    		</div>
		   		</div>
		   	</div>
		</div>
	</div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>