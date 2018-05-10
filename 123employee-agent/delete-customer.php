<?php /*! Change Password of this Application */

require_once(dirname(dirname(__FILE__)) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL);
}

$page_title = 'Delete Customer';
require_once(dirname(dirname(__FILE__)) . "/templates/header.php");
$memberSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers` ORDER BY `Cust_ID` DESC");
if ( $memberSQL ) {
	$memberCount = mysql_num_rows($memberSQL);
} else {
	$memberCount = 0;
} ?>

<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content">
	<div class="group_content_topbar">
        <h2>
        	<i class="fa fa-lock"></i> Delete Customer
        	<button class="btn btn-danger pull-right" id="deleteTriger"><i class="fa fa-trash"></i> Delete</button>
        </h2>
    </div>
    <div class="row">
        <div class="nano">
            <div class="nano-content">
    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		    		<div class="row">
					<div class="widget widget-table action-table">
                        <div class="widget-header">
                        	<div id="msgWrap" class="col-lg-12"></div>
                        </div>
                        <div class="widget-content">
							<div style="padding:0 15px;">
								<table id="customer-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th width="5%"><input type="checkbox"  id="bulkDelete" /></th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>Email</th>
											<th>Register On</th>
										</tr>
									</thead>
								</table>
							</div>
                        </div>
		    		</div>
		   		</div>
		   	</div>
		</div>
	</div>
</div>

<?php require_once(dirname(dirname(__FILE__)) . "/templates/footer.php"); ?>
<?php unset($_SESSION['Resend_Reg']); ?>