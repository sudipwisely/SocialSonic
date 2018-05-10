<?php /*! Change Password of this Application */

require_once(dirname(dirname(__FILE__)) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL);
}

$page_title = 'Member Signup';
require_once(dirname(dirname(__FILE__)) . "/templates/header.php");
$memberSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "temp_customers` ORDER BY `Temp_ID` DESC");
if ( $memberSQL ) {
	$memberCount = mysql_num_rows($memberSQL);
} else {
	$memberCount = 0;
} ?>

<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content">
	<div class="group_content_topbar">
        <h2><i class="fa fa-lock"></i> Member SignUp
        <a data-toggle="modal" data-target="#member_modal" href="#" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Member</a>
        </h2>
    </div>
    <div class="row">
        <div class="nano">
            <div class="nano-content">
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		    		<div class="row">
					<div class="widget widget-table action-table">
                        <div class="widget-header">
                        	<div class="col-lg-12"><?php echo isset($_SESSION['Resend_Reg']) ? $_SESSION['Resend_Reg'] : ''; ?></div>
                        </div>
                        <div class="widget-content">
							<div style="padding:0 15px;">
								<table id="temp-customers" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>User Status</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if ( $memberCount > 0 ) {
											$i = 0;
											while ( $resultset = mysql_fetch_assoc($memberSQL) ) {
												$i = $i+1; ?>
												<tr>
													<td><?php echo $resultset['Temp_Firstname']; ?> <?php echo $resultset['Temp_Lastname']; ?></td>
													<td><?php echo $resultset['Temp_Email']; ?></td>
													<td><?php echo $resultset['Temp_Status']=='YES'?'Registered':'Not Registered'; ?></td>
													<td class="text-center">
														<?php if ( $resultset['Temp_Status'] == 'NO' ) { ?>
															<a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#link_modal<?php echo $resultset['Temp_ID']; ?>" href="#">
																<i class="fa fa-link"></i>
															</a>
															<a href="<?php echo SITE_URL; ?>app-ajax/?case=ResendRegURI&User=<?php echo $resultset['Temp_ID']; ?>" class="btn btn-success btn-xs"><i class="fa fa-envelope"></i></a>

															<div class="modal fade in" id="link_modal<?php echo $resultset['Temp_ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																<div class="modal-dialog text-left">
															    	<div class="modal-content">
															        	<div class="modal-header">
															            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
															                <h4><i class="fa fa-link"></i> Member Registration Link</h4>
															            </div>
																		<div class="modal-body">
																			<p style="font-size:12px;word-break:break-all;"><?php echo $resultset['Temp_Url']; ?></p>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																		</div> 
															        </div>
															    </div>
															</div>
														<?php } else { ?>
															<a class="btn btn-danger btn-xs" data-toggle="modal" data-target="#user_modal<?php echo $resultset['Temp_ID']; ?>" href="#">
																<i class="fa fa-lock"></i>
															</a>
															<div class="modal fade in" id="user_modal<?php echo $resultset['Temp_ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																<div class="modal-dialog text-left">
															    	<div class="modal-content">
															        	<div class="modal-header">
															            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
															                <h4><i class="fa fa-lock"></i> Username & Password</h4>
															            </div>
																		<div class="modal-body">
																			<p><strong>Username: </strong> <?php echo $resultset['Temp_UserName']; ?></p>
																			<p><strong>Password: </strong> <?php echo $resultset['Temp_Password']; ?></p>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																		</div> 
															        </div>
															    </div>
															</div>
														<?php } ?>
													</td>
												</tr>
									  		<?php } 
									    } else { ?>
									    	<tr>
                                                <td colspan="5">No Member is available.</td>
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

<div class="modal fade in" id="member_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog text-left">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-user"></i> Add Unregistered Member</h4>
            </div>
            <form id="member_add_form" method="post" action="<?php echo SITE_URL; ?>app-ajax/?case=MemberSignupForm">
				<div class="modal-body">
					<div class="alert alert-danger hide member-error"></div>
					<div class="form-group"> 
						<label for="memFirstname">First Name</label>
						<input type="text" class="form-control" id="memFirstname" name="memFirstname" placeholder="Member First Name" required="" />
					</div>
					<div class="form-group"> 
						<label for="memLastname">Member Last Name</label>
						<input type="text" class="form-control" id="memLastname" name="memLastname" placeholder="Member Last Name" required=""/>
					</div>
					<div class="form-group"> 
						<label for="memberEmail">Member Email</label>
						<input type="text" class="form-control" id="memberEmail" name="memberEmail" placeholder="Member Email ID" required=""/>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" name="member-create-button" id="member-create-button" class="btn btn-warning">Create</button>
				</div> 
			</form>
        </div>
    </div>
</div>

<?php require_once(dirname(dirname(__FILE__)) . "/templates/footer.php"); ?>
<?php unset($_SESSION['Resend_Reg']); ?>