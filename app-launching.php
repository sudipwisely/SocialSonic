<?php 

require_once(dirname(__FILE__) . "/config/config.php");
if ( isset($_GET['cust']) || !empty($_GET['cust']) ) {
	$_SESSION['Cust_ID'] = $_GET['cust'];
}

if ( !isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL);
}

$page_title = 'Launching Your App...';
require_once(dirname(__FILE__) . "/templates/header.php"); ?>

<?php if ( $_GET['ref'] == 1 ) { ?>
	<meta http-equiv="refresh" content="0;<?php echo SITE_URL; ?>change-password/" />
<?php } else { ?>
	<meta http-equiv="refresh" content="0;<?php echo SITE_URL; ?>twitter-crm/" />
<?php } ?>

<style type="text/css">.crm-loading{display:block !important;top:15px;}</style>
<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content" style="height:100%;">
    <div class="crm-loading">Launching Your Twitter CRM<span>.</span><span>.</span><span>.</span></div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>