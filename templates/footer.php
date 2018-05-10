<?php /*! Footer of this Application */ ?>

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="<?php echo SITE_URL; ?>app-ajax?case=CustomerLoginProcess" method="post" id="login-form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title text-center" id="loginModalLabel"><img src="<?php echo SITE_URL; ?>images/ss-logo.png" alt="Social Sonic" width="200" /></h4>
				</div>
				<div class="modal-body">
					<div id="Ses-Expire-Msg" class="alert alert-danger text-center hide">
					  	Your session has been expired! Please re login from here.
					</div>
					<div class="form-group">
						<label for="email">Username: </label>
						<input class="form-control" type="text" id="username" name="username" />
					</div>
					<div class="form-group">
						<label for="password">Password: </label>
						<input class="form-control" type="password" id="password" name="password" />
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="remember" name="remember"> Remember me
						</label>
						<span class="pull-right text-danger" id="loginError"></span>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
							<a class="pull-left" href="<?php echo SITE_URL; ?>forgot-password/">Forgot Password</a>
						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							<div class="loader hide"></div>
						</div>
						<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button class="btn btn-primary" type="submit" name="login">Login</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php if ( !isset($_SESSION['Cust_ID']) ) { ?>

		<input type="hidden" id="CkLogin" value="0" />
		<script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo SITE_URL;?>js/bootstrapValidator.min.js"></script>
		<script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery.nanoscroller.min.js"></script>
		<script type="text/javascript" src="<?php echo SITE_URL;?>js/jwplayer.min.js"></script>
		<script type="text/javascript" src="<?php echo SITE_URL;?>js/twitter-app.js?random=<?php echo uniqid(); ?>"></script>
	</body>
	</html>

<?php } else { ?>

				</div>
			</div>
		</div>
		<input type="hidden" id="CkLogin" value="1" />
		<script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/bootstrap.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/bootstrapValidator.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/bootstrap-slider.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery-ui.1.8.20.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery.jqEasyCharCounter.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/tagit.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery.sidr.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/responsive-tab.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/jwplayer.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>admin/js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" src="<?php echo SITE_URL;?>js/twitter-app.js?random=<?php echo uniqid(); ?>"></script>
	</body>
	</html>

<?php } ?>