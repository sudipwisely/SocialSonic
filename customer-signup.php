<?php /*! Customer Signup of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL . 'twitter-crm/');
}

if ( !isset($_REQUEST['inf_field_PostalCode']) && 
	 !isset($_REQUEST['inf_field_FirstName']) && 
	 !isset($_REQUEST['inf_field_Country']) && 
	 !isset($_REQUEST['inf_field_StreetAddress1']) && 
	 !isset($_REQUEST['inf_field_Email']) && 
	 !isset($_REQUEST['inf_field_StreetAddress2']) && 
	 !isset($_REQUEST['inf_field_Country2']) && 
	 !isset($_REQUEST['inf_field_City2']) && 
	 !isset($_REQUEST['inf_field_State']) && 
	 !isset($_REQUEST['inf_field_MiddleName']) && 
	 !isset($_REQUEST['inf_field_State2']) && 
	 !isset($_REQUEST['inf_field_City']) && 
	 !isset($_REQUEST['inf_field_Company']) && 
	 !isset($_REQUEST['inf_field_Address2Street2']) && 
	 !isset($_REQUEST['inf_field_Address2Street1']) && 
	 !isset($_REQUEST['inf_field_LastName']) && 
	 !isset($_REQUEST['inf_field_PostalCode2']) && 
	 !isset($_REQUEST['orderId']) ) {
	header('location:' . SITE_URL . '404/');
}

$page_title = 'Join with SocialSonic';
require_once(dirname(__FILE__) . "/templates/header.php");
$category_details = $helper->getCategory();


$emailxist_SQl = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers` WHERE `Cust_Email` = '" . addslashes($_REQUEST['inf_field_Email']) . "'");
if ( $emailxist_SQl ) {
	if ( mysql_num_rows($emailxist_SQl) > 0 ) { ?>
	  	
	  	<div class="reg-page-bg">
	  		<header class="signup-header">
				<a href="<?php echo SITE_URL; ?>"><img width="200" src="<?php echo SITE_URL; ?>images/ss-logo.png" alt="SocialSonic" /></a>
			</header>
			<div class="signup-body">
				<p class="text-center sucmsg">You have already registered with us, <a href="<?php echo SITE_URL; ?>">Login here</a></p>
			</div>
			<footer class="signup-footer">
				&copy Social Sonic. 2016
			</footer>
		</div>

		<?php 
		return false; 
	} else {
		$selectTempSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "temp_customers` WHERE `Temp_Email` = '" . addslashes($_REQUEST['inf_field_Email']) . "'");
		if ( $selectTempSQL ) {
			if ( mysql_num_rows($selectTempSQL) == 0 ) {
				$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				$subject  = 'Welcome to Social Sonic';
				$body = '<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#FCFCFD url(' . SITE_URL . 'images/body-bg.png) repeat 0 0;border:1px solid rgba(0,0,0,0.15);font-family:Verdana,sans-serif;">
					<tr>
						<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="75" align="center" style="background:rgba(0, 0, 0, 0.15) repeat;border-bottom: 1px solid rgba(0, 0, 0, 0.15);">
										<img width="200" alt="" src="' . SITE_URL . 'images/ss-logo.png" />
									</td>
								</tr>
								<tr>
									<td height="100" align="center">
										<font color="#c31629" size="5">Welcome to Social Sonic</font><br/><br/>
										<font color="#5cb85c" size="2"><span>Thank you for registering with us.</span></font><br /><br />
									</td>
								</tr>
								<tr>
									<td align="left" valign="top" style="padding:0 20px;">
										<font color="#161616" size="2">
											<span>Dear ' . $_REQUEST['inf_field_FirstName'] . ' ' . $_REQUEST['inf_field_LastName'] . ',<br /><br />Please login to your Social Sonic account with the following link:</span>
										</font><br /><br />
									</td>
								</tr>
								<tr>
									<td align="left" valign="top" style="padding-left:20px;">
										<font color="#0e59ac" size="3"><span><a href = ' . $url . '>Please Click Here</a></span></font><br />
										<br />
										<font color="#000" size="2">
											<span>in case you have any questions, please call us on the number below<br /><br />Phone support:<br />1-702-960-4130<br />Thank you for choosing&nbsp;&nbsp;<a href="' . SITE_URL . '" style="color:#0e59ac; line-height:20px;">Social Sonic!</a><br /></span>
										</font><br /><br />
									</td>
								</tr>
								<tr>
									<td align="left" valign="top" style="padding-left:20px;">
										<font color="#000" size="2">
											<span>Regards,<br />The <a href="#" style="color:#0e59ac;line-height:20px;">Social Sonic</a> Team.</span>
										</font><br /><br />
										<font color="#000" size="2">
											<span>P.S: This is a system generated email. Please do not reply.</span><br /><br />
										</font>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
				emailChoose($_REQUEST['inf_field_Email'], $subject, $body, $_REQUEST['inf_field_FirstName'] . ' ' . $_REQUEST['inf_field_LastName']);
				$inserTempSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "temp_customers`(`Temp_Firstname`, `Temp_Lastname`, `Temp_Email`, `Temp_Url`, `Temp_TransID`, `Temp_CreationDate`) VALUES('" . addslashes($_REQUEST['inf_field_FirstName']) . "', '" . addslashes($_REQUEST['inf_field_LastName']) . "', '" . addslashes($_REQUEST['inf_field_Email']) . "', '" . addslashes($url) . "', '" . addslashes($_REQUEST['orderId']) . "', '" . date('Y-m-d H:i:s') . "')");
			}
		}
	}
}
?>

<div class="reg-page-bg">
	<header class="signup-header">
		<a href="<?php echo SITE_URL; ?>"><img width="200" src="<?php echo SITE_URL; ?>images/ss-logo.png" alt="SocialSonic" /></a>
	</header>

	<div class="signup-body">
		<p class="text-center"><i class="fa fa-check-circle"></i> Your payment has been successfully processed.</p>
		<ul class="nav nav-wizard" id="signupTab">
			<li class="active"><a href="#first" data-toggle="tab">Step 1</a></li>
			<li class="hide"><a href="#first_1">Step 1</a></li>

			<li><a href="#second">Step 2</a></li>
			<li class="hide"><a href="#second_1">Step 2</a></li>
			<li class="hide"><a href="#second_2">Step 2</a></li>
			<li class="hide"><a href="#second_3">Step 2</a></li>
			<li class="hide"><a href="#second_4">Step 2</a></li>

			<li><a href="#third">Step 3</a></li>
			<li class="hide"><a href="#third_1">Step 3</a></li>
			<li class="hide"><a href="#third_2">Step 3</a></li>
			<li class="hide"><a href="#third_3">Step 3</a></li>
		</ul>

	    <form id="post_payment_form" method="post" class="form-horizontal" action="<?php echo SITE_URL; ?>app-ajax/?case=PostPaymentFormSubmit">
		<iframe src="https://433608.cpa.clicksure.com/pixel?skip=1&transactionRef=[0]" width="1px" height="1px" frameborder="0"></iframe>
			<div class="tab-content">
				<div class="signup-loading"><div class="loader"></div></div>

	            <div class="tab-pane active" id="first">
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="signup-video">
								<div id="signup"></div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">&nbsp;</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="GoToStep1_1" class="btn btn-block post_user" name="commit" type="button">Next Step</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="first_1">
	            	<span class="use-ss">To use Social Sonic, please complete your details:</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input type="text" class="form-control" id="customer_email" name="customer_email" placeholder="Your Email Address" value="<?php echo isset($_REQUEST['inf_field_Email']) ? $_REQUEST['inf_field_Email'] : ''; ?>" readonly />
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<input type="text" class="form-control" id="customer_firstname" name="customer_firstname" placeholder="Your First Name" value="<?php echo isset($_REQUEST['inf_field_FirstName']) ? $_REQUEST['inf_field_FirstName'] : ''; ?>" />
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<input type="text" class="form-control" id="customer_lastname" name="customer_lastname" placeholder="Your Last Name" value="<?php echo isset($_REQUEST['inf_field_LastName']) ? $_REQUEST['inf_field_LastName'] : ''; ?>" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input class="form-control" id="customer_username" name="customer_username" placeholder="Create a username" type="text" data-minlength="5" />
							<input type="hidden" name="customer_order_id" value="<?php echo isset($_REQUEST['orderId']) ? $_REQUEST['orderId'] : ''; ?>" />
							<input type="hidden" name="Cust_Payment_Type" value="<?php echo isset($_REQUEST['ssid']) ? $_REQUEST['ssid'] : 'normal'; ?>" />
						</div>
					</div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="BackToStep1" class="btn btn-block post_user" type="button">Back to Tutorial</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="GoToStep2" class="btn btn-block post_user" name="commit" type="button">Next Step</button>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<span class="terms" id="firstMsg"></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="second">
					<span class="use-ss">&nbsp;</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h1 class="text-center">Do you have a Twitter Account?</h1>
						</div>
					</div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="NoTwitterAccount" class="btn btn-block post_user" type="button">No</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="HaveTwitterAccount" class="btn btn-block post_user" type="button">Yes</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="second_1">
					<span class="use-ss">Tutorial: Creating a Twitter Account</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="signup-video" style="margin-top:0;">
								<div id="twitter-account"></div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button class="btn btn-block post_user BackToStep2" type="button">Back to Question</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="GoToStep2_2" class="btn btn-block post_user" name="commit" type="button">Next Step</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="second_2">
					<span class="use-ss">Tutorial: Creating a Twitter App</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="signup-video" style="margin-top:0;">
								<div id="twitter-app"></div>
							</div>
							<p style="color:#000;text-shadow:none;">NOTE: The URL you enter in the website and callback URL fields can be same. However, the URL you enter in the said fields must be a domain owned by you which has not been used in creating a Twitter app before.</p>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button class="btn btn-block post_user BackToStep2" type="button">Back to Question</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="GoToStep2_3" class="btn btn-block post_user" name="commit" type="button">Next Step</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="second_3">
					<span class="use-ss">Tutorial: Getting Access Tokens</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="signup-video" style="margin-top:0;">
								<div id="access-token"></div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="BackToStep2_2" class="btn btn-block post_user" type="button">Creating a Twitter App</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="GoToStep2_4" class="btn btn-block post_user" name="commit" type="button">Next Step</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="second_4">
					<span class="use-ss" style="text-align:left;font-size:14px;"><font color="#f00">WARNING:</font> While Copying & Pasting the values on this form from your Twitter App page, please make sure you are copying all the characters, without any spaces. If you enter the wrong values, Social Sonic will not work.</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input class="form-control" id="customer_twitter_consumer_key" name="customer_twitter_consumer_key" placeholder="Your Twitter Consumer Key (API Key)" type="text" />
							<small class="help-block hide twitterError" style="color:#a94442"></small>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input class="form-control" id="customer_twitter_consumer_secret" name="customer_twitter_consumer_secret" placeholder="Your Twitter Consumer Secret (API Secret)" type="text" />
							<small class="help-block hide twitterError" style="color:#a94442"></small>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<input class="form-control" id="customer_twitter_screenname" name="customer_twitter_screenname" placeholder="Owner Name" type="text" />
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<input class="form-control" id="customer_twitter_id" name="customer_twitter_id" placeholder="Your Owner ID" type="text" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input class="form-control" id="customer_twitter_access_token" name="customer_twitter_access_token" placeholder="Your Twitter Access Token" type="text" />
							<small class="help-block hide twitterError" style="color:#a94442"></small>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input class="form-control" id="customer_access_token_secret" name="customer_access_token_secret" placeholder="Your Twitter Access Token Secret" type="text" />
							<small class="help-block hide twitterError" style="color:#a94442"></small>
						</div>
					</div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="BackToStep2_3" class="btn btn-block post_user" type="button">Previous Step</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="GoToStep3" class="btn btn-block post_user" type="button">Next Step</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="third">
					<span class="use-ss">&nbsp;</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h1 class="text-center">Do you have a Clickbank Account?</h1>
						</div>
					</div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="NoClkbnkAccount" class="btn btn-block post_user" type="button">No</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="HaveClkbnkAccount" class="btn btn-block post_user" type="button">Yes</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="third_1">
					<span class="use-ss">Tutorial: Creating a Clickbank Account</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="signup-video" style="margin-top:0;">
								<div id="clkbnk-account"></div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button class="btn btn-block post_user BackToStep3" type="button">Back to Question</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="GoToStep3_2" class="btn btn-block post_user" name="commit" type="button">Next Step</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="third_2">
					<span class="use-ss">Tutorial: Getting Clickbank Hopcode</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="signup-video" style="margin-top:0;">
								<div id="hopcode"></div>
							</div> 
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button class="btn btn-block post_user BackToStep3" type="button">Back to Question</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="GoToStep3_3" class="btn btn-block post_user" name="commit" type="button">Next Step</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="third_3">
					<span class="use-ss">&nbsp;</span>
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input class="form-control" id="customer_hopcode" name="customer_hopcode" placeholder="Your Clickbank Username" type="text">
						</div>
					</div>
	                <div class="form-group">
						<div class="btn-wrap">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="BackToStep3_2" class="btn btn-block post_user" type="button">Previous Step</button>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									<button id="commit" class="btn btn-block post_user" name="commit" type="submit">Submit</button>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<span class="terms" id="signupMsg"></span>
									<span class="terms">By registering you confirm that you agree with our <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br /><br />
				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<span class="questions"><strong>Questions?</strong><br />Please call our award winning support team at <strong>1-702-960-4130</strong></span>
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

<div class="modal fade" id="twitterModal" tabindex="-1" role="dialog" aria-labelledby="twitterModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="twitterModalLabel">How to fill step 2?</h4>
			</div>
			<div class="modal-body">
				<div class="embed-responsive embed-responsive-16by9">
					<iframe width="100%" height="315" src="https://www.youtube.com/embed/r63f51ce84A" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="hopModal" tabindex="-1" role="dialog" aria-labelledby="hopModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="hopModalLabel">How to fill step 3?</h4>
			</div>
			<div class="modal-body">
				<div class="embed-responsive embed-responsive-16by9">
					<iframe width="100%" height="315" src="https://www.youtube.com/embed/r63f51ce84A" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>