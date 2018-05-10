<?php /*! My Account of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL);
}

$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
$Customer = $helper->getCustomerById($Cust_ID);
$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);

$page_title = 'Lead Responses';
require_once(dirname(__FILE__) . "/templates/header.php"); ?>

<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content">
  	<div class="group_content_topbar">
        <h2>
            <i class="fa fa-reply"></i> Lead Responses
            <button class="btn btn-sm btn-primary pull-right" onclick="window.location.reload();" data-toggle="tooltip" data-placement="left" title="Refresh" type="button">
                <i class="fa fa-refresh"></i>
            </button>
        </h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="nano">
                    <div class="nano-content">
                        <div class="crm-loading" style="top:-1px">Fetching Lead Responses<span>.</span><span>.</span><span>.</span></div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row" id="responses">
                                <div class="col-xs-12 text-center" id="Empty_Responses"></div>
                                <div id="responses-content"></div>
                                <div class="loaderMore col-xs-12" id="loader_Responses"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="tweetsModal" class="modal right fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title text-center">
                    <a class="btn btn-default btn-xs pull-left hide" id="back2replies" data-toggle="tooltip" data-placement="left" title="Back" href="#"><i class="fa fa-arrow-left"></i></a>
                    <a class="btn btn-info btn-xs pull-left" id="refreshLists" data-toggle="tooltip" data-placement="right" title="Refresh" href="#"><i class="fa fa-refresh"></i></a>
                    <i class="fa fa-twitter"></i> Tweets
                </h4>
            </div>
            <div class="modal-body">
                <div id="replies">
                    <div class="LeadLoader"></div>
                    <div id="replies-content"></div>
                </div>
                <div id="conversations">
                    <div class="LeadLoader"></div>
                    <div id="conversations-content"></div>
                </div>
            </div>
            <div class="modal-footer hide" id="reply-footer">
                <div class="input-group">
                    <input id="reply-input" disabled="" type="text" class="form-control" placeholder="Type your reply here..." />
                    <span class="input-group-btn">
                        <button class="btn btn-info" disabled="" id="btn-chat">Send</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>