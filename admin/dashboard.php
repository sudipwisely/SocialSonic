<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . "/config/config.php");
if ( !isset($_SESSION['AdminUserLoggedIn']) ) {
	header('location:' . SITE_URL . 'admin/');
	return false;
} ?>

<?php $page_name = 'Dashboard'; ?>

<?php include(dirname(__FILE__) . '/templates/header.php'); ?>

<?php include(dirname(__FILE__) . '/templates/topbar.php'); ?>
    
<?php include(dirname(__FILE__) . '/templates/navbar.php'); ?>

<div class="main">
	<div class="main-inner">
    	<div class="container">
        	<div class="row">
            	<div class="span12">
					
                	<div class="widget widget-table action-table">
                        <div class="widget-header">
                            <i class="icon-th-list"></i>
                            <h3>Customers</h3>
                        </div>
                        <div class="widget-content">
							<div style="padding:15px;">
								<table id="customers-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th width="5%">#</th>
											<th width="15%">Customer Name</th>
											<th width="10%">Screen Name</th>
											<th width="15%" style="text-align:center;">Sent/Upcoming Tweets</th>
											<th width="15%" style="text-align:center;">Sent/Upcoming DM</th>
											<th width="15%" style="text-align:center;">Running Compaigns</th>
											<th width="15%">APP Status</th>
											<th width="10%" style="text-align:center;">More Details</th>
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

<div class="modal customerModal hide fade in" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog text-left">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4><i class="fa fa-user"></i> Customer Details</h4>
			</div>
			<div class="modal-body" style="word-break: break-all;">
				<ul class="nav nav-tabs" role="tablist">
					<li class="active">
						<a href="#general" data-toggle="tab">General</a>
					</li>
					<li>
						<a href="#twitter" data-toggle="tab">Twitter</a>
					</li>
					<li>
						<a href="#keyword" data-toggle="tab">Keyword</a>
					</li>
					<li>
						<a href="#category" data-toggle="tab">Category</a>
					</li>
					<li>
						<a href="#tweets" data-toggle="tab">Tweets</a>
					</li>
					<li>
						<a href="#dm" data-toggle="tab">Direct Message</a>
					</li>
					<li>
						<a href="#upgrade" data-toggle="tab">Account</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="general"></div>
					<div class="tab-pane" id="twitter"></div>
					<div class="tab-pane" id="keyword"></div>
					<div class="tab-pane" id="category"></div>
					<div class="tab-pane" id="tweets"></div>
					<div class="tab-pane" id="dm"></div>
					<div class="tab-pane" id="upgrade"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include(dirname(__FILE__) . '/templates/footer.php'); ?>