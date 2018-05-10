<?php /*! Forgot Password of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL . 'twitter-crm/');
}

$page_title = 'Forgot Password';
require_once(dirname(__FILE__) . "/templates/header.php"); ?>

<div class="reg-page-bg">
	<header class="signup-header">
		<a href="<?php echo SITE_URL; ?>"><img width="200" src="<?php echo SITE_URL; ?>images/ss-logo.png" alt="SocialSonic" /></a>
	</header>

	<div class="signup-body">
		<p>&nbsp;</p>
		<ul class="nav nav-wizard">
			<li><a>Forgot Your Password ?</a></li>
		</ul>

	    <form id="forgot_password_form" method="post" class="form-horizontal" action="<?php echo SITE_URL; ?>app-ajax/?case=ForgotPasswordProcess">
			<div class="tab-content">
	            <div class="tab-pane active">
	            	<span class="use-ss">Please complete the form to reset your password:</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input type="text" class="form-control" id="your_email" name="your_email" placeholder="Your Email Address" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input class="form-control" id="your_username" name="your_username" placeholder="Your Username" type="text" data-minlength="5" />
						</div>
					</div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<button class="btn btn-block post_user" name="submit" type="submit">Submit</button>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<span class="terms" id="pswdMsg"></span>
									<span class="terms">After submitting the form you will receive a email with your new login details.</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<footer class="signup-footer">
		&copy Social Sonic. 2016
	</footer>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>