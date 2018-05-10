<?php /*! Twitter CRM of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
    header('location:' . SITE_URL);
}

$page_title = 'Twitter CRM';
require_once(dirname(__FILE__) . "/templates/header.php");

global $mongoDb;

$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
$Customer = $helper->getCustomerById($Cust_ID);
$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);

if ( isset($_POST['keyword_submit']) ) {
	if ( isset($_POST['talk_about_tags']) ) {
		if ( isset($_POST['hidprospectid']) ) {
			$Prospect_ID = $_POST['hidprospectid'];
            if ( $_POST['catReadonly'] == 'editonly' ) {
                $delM = $mongoDb->prospect_keywords->remove(array('prospect_id' => $Prospect_ID, 'search_user_id' => $Cust_ID, 'status' => 'pending'), array("w" => 0));
                mysql_query("UPDATE `" . DB_PREFIX . "card_count` SET `Keyword_Pipeline` = 0 WHERE `Cust_ID` = " . $Cust_ID);
            }
		} else {
			$Prospect_ID = 0;
		}
		$Prospect_ID = $process->manageKeywordCategorySearch($Prospect_ID, $_POST['talk_about_tags'], '', '', 'keyword');
	}
	$Coming_From = 'keyword';
    $process->getKeywordCRMResult();
} elseif ( isset($_POST['category_submit']) ) {
	if($Customer['Cust_Payment_Type'] != 'trip'){
		$influencers = ''; $myInfluencers = array(); $myinfs = array();
		if ( isset($_POST['category']) ) {
			if ( isset($_POST['hidprospectid']) ) {
				$Prospect_ID = $_POST['hidprospectid'];
			} else {
				$Prospect_ID = 0;
			}
			$prev_influencers = explode(',', $_POST['prev_influencers']);
			if ( $_POST['category'] == 'own' ) {
				foreach ( $_POST['myInfluencers'] as $influencer ) {
					$user_details  = $twitteroauth->get('users/show', array("screen_name" => $influencer));
					$influencer_id = $user_details->id_str;
					$myinfs[] = $influencer_id;
					$influencers .= $influencer_id . ',';
				}
				$influencers = substr($influencers, 0, -1);
				$category = 0;
				$myInfluencers = $_POST['myInfluencers'];
			} else {
				$influencers = $_POST['influencer_screenname'];
				$category = $_POST['category'];
				$myinfs = explode(',', $_POST['influencer_screenname']);
				foreach ( $myinfs as $influencer ) {
					$user_details  = $twitteroauth->get('users/show', array("user_id" => $influencer));
					$myInfluencers[] = $user_details->screen_name;
				}
			}
			if ( isset($_POST['hidprospectid']) ) {
				if ( $_POST['catReadonly'] == 'editonly' ) {
					foreach ( $prev_influencers as $prev ) {
						if ( !in_array($prev, $myinfs) ) {
							$delM = $mongoDb->prospect_influencers->remove(array('prospect_id' => $Prospect_ID, 'influncer_id' => $prev, 'search_user_id' => $Cust_ID, 'status' => 'pending'), array("w" => 0));
						}
					}
				}
			}
			$Prospect_ID = $process->manageKeywordCategorySearch($Prospect_ID, '', $category, $influencers, 'category');
			$process->storeOwnCategories($myInfluencers, $Prospect_ID, $influencers);
		}
		
	}
	$Coming_From = 'category';
} else {
	$Prospect = $helper->getProspectByCustId($Cust_ID);
	if ( count($Prospect) > 0 ) {
		$Prospect_ID = $Prospect['Prospect_ID'];
	} else {
		$Prospect_ID = 0;
	}
	$Coming_From = 'followers';
}

$CheckUserProspectSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = $Cust_ID");
if ( $CheckUserProspectSQL ) {
    $CheckUserProspectData = mysql_fetch_assoc($CheckUserProspectSQL);
    $dataKeyword = $CheckUserProspectData['Talks_About'];
    $dataCategory = $CheckUserProspectData['Influencers'];
} else {
    $dataKeyword = '';
    $dataCategory = '';
} ?>

<input type="hidden" id="prospect_id" value="<?php echo $Prospect_ID; ?>" />
<input type="hidden" id="coming_from" value="<?php echo $Coming_From; ?>" />
<input type="hidden" id="customer_status" value="<?php echo $Customer['Cust_Payment_Type']; ?>" />
<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content">
	<div class="group_content_topbar">
        <h2><i class="fa fa-twitter"></i> Twitter CRM</h2>
    </div>
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="tabbable responsive" style="margin-top:15px;">
                <ul class="nav nav-tabs" role="tablist" id="twitter-tab">
                    <li role="presentation" <?php echo isset($_POST['keyword_submit']) ? 'class="active"' : '' ?>>
                        <a class="tab-link" href="#prospect-finder-pipeline">Keyword Pipeline</a>
                    </li>
					<li role="presentation" <?php echo isset($_POST['category_submit']) ? 'class="active"' : '' ?>>
                        <a class="tab-link" href="#category-pipeline">Category Pipeline</a>
                    </li>
                    <li role="presentation">
                        <a class="tab-link" href="#saved-filters">Saved Filters</a>
                    </li>
                    <li role="presentation" <?php echo (isset($_POST['keyword_submit']) || isset($_POST['category_submit'])) ? '' : 'class="active"' ?>>
                        <a class="tab-link" href="#prospects">Prospects</a>
                    </li>
                    <li role="presentation">
                        <a class="tab-link" href="#existing-followers">Followers</a>
                    </li>
                    <li role="presentation" >
                        <a class="tab-link" href="#set-direct-message">Set Direct Message</a>
                    </li>
                    <li role="presentation">
                        <a class="tab-link" href="#show-websites">Download Leads</a>
                    </li>
                    <li role="presentation">
                        <a class="tab-link" href="#unfollow">Unfollow</a>
                    </li>
                    <div class="pull-right">
                        <a href="#" id="filter-handle" class="btn btn-danger" data-toggle="modal" data-target="#FilterModal">
                            <i class="fa fa-filter"></i> Filter Prospects
                        </a>
                    </div>
                </ul>

                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane <?php echo isset($_POST['keyword_submit']) ? "active" : '' ?>" id="prospect-finder-pipeline">
                    	<div class="crm-loading">Fetching Keyword Results<span>.</span><span>.</span><span>.</span></div>
                        <div class="buttons-actions">
                        	<div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <form name="editprospect" id="edit-prospect" method="post" action="<?php echo SITE_URL; ?>keyword-search/">
                                        <input id="readonly" name="readonly" value="readonly" type="hidden" >
                                    </form>
                                    <?php if ( !empty($dataKeyword) ) { ?>
                                        <button data-toggle="tooltip" data-placement="top" title="Back to Search" class="btn btn-danger pull-left" id="editBack" type="button" onclick="document.editprospect.submit();">
                                            <i class="fa fa-search"></i> Edit Search
                                        </button>
                                    <?php } else { ?>
                                        <a href="<?php echo SITE_URL; ?>keyword-search/" data-toggle="tooltip" data-placement="top" title="Create Search" class="btn btn-danger pull-left">
                                            <i class="fa fa-search"></i> Create Search
                                        </a>
                                    <?php } ?>
                                    <span class="result-count" id="KeywordFilterCount"></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><span class="twmessage"></span></div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <button data-toggle="tooltip" data-placement="top" title="Toggle between Bio & Tweet" type="button" class="btn btn-info alterbiotalk">
                                    	<span class="bioIcon hide"><i class="fa fa-leaf"></i></span>
                                        <span class="talkIcon"><i class="fa fa-comments"></i></span>
                                    </button>&nbsp;&nbsp;
                                    <button data-toggle="tooltip" data-placement="top" title="Refresh" type="button" class="btn btn-primary refreshdata">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="nano">
		                        <div class="nano-content">
                                    <div class="emptyResult col-xs-12" id="Empty_Keywords"></div>
	                                <div class="user-results" id="prospect-finder-pipeline-content"></div>
                                    <div class="loaderMore col-xs-12" id="loader_Keywords"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
					<div role="tabpanel" class="tab-pane <?php echo isset($_POST['category_submit']) ? "active" : '' ?>" id="category-pipeline">
						<div class="crm-loading">Fetching Category Results<span>.</span><span>.</span><span>.</span></div>
						<div class="buttons-actions">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<form name="editcategory" id="edit-category" method="post" action="<?php echo SITE_URL; ?>category-search/">
										<input id="readonly" name="readonly" value="readonly" type="hidden" >
									</form>
                                    <?php if ( !empty($dataCategory) ) { ?>
    									<button data-toggle="tooltip" data-placement="top" title="Back to Search" class="btn btn-danger pull-left" id="editBack" type="button" onclick="document.editcategory.submit();">
    										<i class="fa fa-search"></i> Edit Search
    									</button>
                                    <?php } else { ?>
                                        <a href="<?php echo SITE_URL; ?>category-search/" data-toggle="tooltip" data-placement="top" title="Create Search" class="btn btn-danger pull-left">
                                            <i class="fa fa-search"></i> Create Search
                                        </a>
                                    <?php } ?>
                                    <span class="result-count" id="CategoryFilterCount"></span>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><span class="twmessage"></span></div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <button data-toggle="tooltip" data-placement="top" title="Refresh" type="button" class="btn btn-primary refreshdata">
                                        <i class="fa fa-refresh"></i>
                                    </button>
								</div>
							</div>
						</div>
                        <div class="row">
                            <div class="nano">
								<div class="nano-content">
                                    <div class="emptyResult col-xs-12" id="Empty_Category"></div>
									<div class="user-results" id="category-pipeline-content"></div>
                                    <div class="loaderMore col-xs-12" id="loader_Category"></div>
								</div>
							</div>
                        </div>
					</div>

                    <div role="tabpanel" class="tab-pane" id="saved-filters">
                        <div class="crm-loading">Fetching Saved Filters<span>.</span><span>.</span><span>.</span></div>
                        <div class="row">
                            <div class="nano">
                                <div class="nano-content">
                                    <div class="col-xs-12" id="saved-filters-content">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Filter Name</th>
                                                    <th class="text-center">Pipeline Type</th>
                                                    <!--th class="text-center">Result Count</th-->
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="emptyResult col-xs-12" id="Empty_SavedFilters"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane <?php echo isset($_POST['keyword_submit']) || isset($_POST['category_submit']) ? '' : 'active' ?>" id="prospects">
                    	<div class="crm-loading">Fetching Prospects Results<span>.</span><span>.</span><span>.</span></div>
                        <div class="buttons-actions">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <span class="result-count" id="ProspectsFilterCount"></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"></div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        	        <button data-toggle="tooltip" data-placement="top" title="Send Tweets" type="button" class="btn btn-success sendtweet" data-role="multiple">
                                        <i class="fa fa-twitter"></i>
                                    </button>&nbsp;&nbsp;
                                    <button data-toggle="tooltip" data-placement="top" title="Refresh" type="button" class="btn btn-primary refreshdata">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="nano">
		                        <div class="nano-content">
                                    <div class="emptyResult col-xs-12" id="Empty_Prospects"></div>
                                	<div class="user-results" id="prospects-content"></div>
                                    <div class="loaderMore col-xs-12" id="loader_Prospects"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane" id="existing-followers">
                    	<div class="crm-loading">Fetching Followers Results<span>.</span><span>.</span><span>.</span></div>
                        <div class="buttons-actions">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <span class="result-count" id="FollowersFilterCount"></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"></div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        	        <button data-toggle="tooltip" data-placement="top" title="Send Tweets" type="button" class="btn btn-success sendtweet" data-role="single">
                                        <i class="fa fa-twitter"></i>
                                    </button>&nbsp;&nbsp;
                        	        <button data-toggle="tooltip" data-placement="top" title="Send Direct Message" type="button" class="btn btn-warning directmessage">
                                        <i class="fa fa-envelope"></i>
                                    </button>&nbsp;&nbsp;
                                    <button data-toggle="tooltip" data-placement="top" title="Refresh" type="button" class="btn btn-primary refreshdata">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="nano">
		                        <div class="nano-content">
                                    <div class="emptyResult col-xs-12" id="Empty_Followers"></div>
                                	<div class="user-results" id="existing-followers-content"></div>
                                    <div class="loaderMore col-xs-12" id="loader_Followers"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane" id="set-direct-message">
                    	<div class="crm-loading">Showing Direct Message Form<span>.</span><span>.</span><span>.</span></div>
                        <div class="row">
                            <div class="nano">
		                        <div class="nano-content">
                                	<div class="col-xs-12" id="set-direct-message-content">
                                        <form method="post" name="twitter-directcomment-form" class="twitter-directcomment-form animated fadeIn">
                                        	<input type="hidden" value="<?php echo $Prospect_ID; ?>" name="prospect_id">
                                            <?php 
                                            $j = 0;
                                            $sql1 = "SELECT * FROM `" . DB_PREFIX . "direct_message` WHERE `user_id` = '" . $_SESSION['Cust_ID'] . "' AND `prospect_id` = " . $Prospect_ID;
                                            $result1 = mysql_query($sql1);
                                            if ( $result1 ) {
                                                $result_count = mysql_num_rows($result1);
                                            } else {
                                                $result_count = 0;
                                            } ?>
                                            <div class="form-group prospect_directmessages_div">
                                                <div class="alert alert-warning max_directmessages hide" role="alert">You can schedule a max. of 4 messages.</div>
                                                <label>Enter Your Direct Messages: </label>
                                                <?php if ( $result_count > 0 ) { ?>
                                                    <?php while ( $rec = mysql_fetch_assoc($result1) ) {
                                                        if ( $j == 0 ) { ?>
                                                            <span class="form-rows">
                                                                <textarea name="direct_messages[]" rows="5" class="form-control prospect_messages"><?php echo $rec['direct_message'];?></textarea>
                                                                <small class="dm-msg-<?php echo $j; ?>">The first message would be sent 15 min after the target profile has followed you.</small>
                                                            </span> 
                                                        <?php } else { ?>
                                                            <span class="form-rows">
                                                                <textarea name="direct_messages[]" rows="5" class="form-control prospect_messages"><?php echo $rec['direct_message'];?></textarea>
                                                                <?php if ( $j == 1 ) { ?>
                                                                    <small class="dm-msg dm-msg-<?php echo $j; ?>">The second message would be sent 1 day after the target profile has followed you.</small>
                                                                <?php } elseif ( $j == 2 ) { ?>
                                                                    <small class="dm-msg dm-msg-<?php echo $j; ?>">The third message would be sent 2 days after the target profile has followed you.</small>
                                                                <?php } elseif ( $j == 3 ) { ?>
                                                                    <small class="dm-msg dm-msg-<?php echo $j; ?>">The fourth message would be sent 3 days after the target profile has followed you.</small>
                                                                <?php } ?>																				
                                                                <a onClick="remove_dm_field(this, event);" class="pull-right" href="#">Remove</a>
                                                            </span>
                                                        <?php 
                                                        } 
                                                        $j++; 
                                                    }
                                                } else { ?>
                                                	<span class="form-rows">
                                                        <textarea name="direct_messages[]" rows="5" class="form-control prospect_messages"></textarea>
	                                                    <small class="dm-msg-0">The first message would be sent 15 min after the target profile has followed you.</small>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                            <div class="form-group">
                                                <a href="javascript:;" class="btn btn-default btn-sm pull-left add_another_messages">
                                                    <i class="fa fa-plus-sign"></i> Create Direct Message Drip
                                                </a>
                                                <button type="submit" name="prospect_message_button" class="btn btn-warning btn-md pull-right prospect_message_button">
                                                    <i class="fa fa-save"></i> SUBMIT
                                                </button> 
                                                <span class="pull-right pdm_msg"></span>
                                            </div>
                                        </form><br /><br />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane" id="show-websites">
                    	<div class="crm-loading">Fetching Websites Results<span>.</span><span>.</span><span>.</span></div>
                        <div class="buttons-actions">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <span class="result-count" id="WebsitesFilterCount"></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"></div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <button data-toggle="tooltip" data-placement="top" title="Download CSV" type="submit" class="btn btn-success" id="downloadCSV">
                                        <i class="fa fa-download"></i>
                                    </button>&nbsp;&nbsp;
                                    <button data-toggle="tooltip" data-placement="top" title="Refresh" type="button" class="btn btn-primary refreshdata">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="nano">
		                        <div class="nano-content">
                                   	<div class="col-xs-12" id="show-websites-content">
                                        <form id="showWebsites" method="post" action="<?php echo SITE_URL; ?>app-ajax/?case=DownloadWebsitesCSV">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="10"><input type="checkbox" id="checkAll" /></th>
                                                        <th>Fullname</th>
                                                        <th>Screenname</th>
                                                        <th>Bio</th>
                                                        <th>Location</th>
                                                        <th>Website</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </form> 
                                    </div>
                                    <div class="emptyResult col-xs-12" id="Empty_Websites"></div>
                                    <div class="loaderMore col-xs-12" id="loader_Websites"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="unfollow">
                        <div class="crm-loading">Fetching Unfollow Prospects<span>.</span><span>.</span><span>.</span></div>
                        <div class="buttons-actions">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <span class="result-count" id="UnFollowFilterCount"></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <span class="unflwmessage"></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <button data-toggle="tooltip" data-placement="top" title="Refresh" type="button" class="btn btn-primary refreshdata">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="nano">
                                <div class="nano-content">
                                    <div class="emptyResult col-xs-12" id="Empty_Unfollow"></div>
                                    <div class="user-results" id="unfollow-content"></div>
                                    <div class="loaderMore col-xs-12" id="loader_Unfollow"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="DirectMessageCRM" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog text-left">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-twitter-square"></i> Tweet</h4>
            </div>
            <div class="LimitProspects hide">
            	<div class="modal-body">
	            	<p><span class="glyphicon glyphicon-info-sign empty-icon"></span><br />You can select only <span id="limitSpan">10</span> prospect(s).</p>
                </div>
            </div>
            <div class="NoProspects hide">
            	<div class="modal-body">
	            	<p><span class="glyphicon glyphicon-ban-circle empty-icon"></span><br />No Prospects.</p>
                </div>
            </div>
            <div class="NoCheckedProspects hide">
            	<div class="modal-body">
	            	<p><span class="glyphicon glyphicon-ban-circle empty-icon"></span><br />No Prospects are selected.</p>
                </div>
            </div>
            <div class="MessageForm hide">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message">Enter Your Message: </label>
                        <textarea name="message" id="message" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                	<span class="dmmessage"></span>
                    <button type="button" name="message_submit" id="message_submit" class="btn btn-warning">SEND</button>
                </div>
            </div>
            <div class="TweetMessageForm hide">
                <div class="modal-body">
                    <div id="additionalbox"></div>
                </div>
                <div class="modal-footer">
                    <span class="post_msg"></span>
                    <button type="button" name="post_message_submit" id="post_message_submit" class="btn btn-warning">SEND</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="WebsitesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-info-circle"></i> Prospect Selection</h4>
            </div>
            <div class="modal-body">
                <div class="NoCheckedWebsites">
                    <div class="modal-body">
                        <p><span class="glyphicon glyphicon-ban-circle empty-icon"></span><br>Select at least one prospect to download!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="RemoveProspectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-info-circle"></i> Remove Prospect</h4>
            </div>
            <div class="modal-body">
                <div class="RemoveProspectMsg">
                    <p><span class="glyphicon glyphicon-trash empty-icon"></span><br />Are you sure you want to remove this prospect?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                <button type="button" id="remove_prospect_yes" class="btn btn-danger">YES</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="FilterModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-filter"></i> Filter Your Prospects</h4>
            </div>
            <div class="modal-body content">
                <p>You can save upto 5 filters for filtering prospects on keyword and category pipeline.<br />But you can filter your prospects as many time as you want.</p>
                <form class="form-horizontal" role="form" id="filterForm" method="post">
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="filter_name">Filter Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="filter_name" name="filter_name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="bio_field">Bio</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bio_field" name="bio_field" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="talks_about">Talk About</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="talks_about" name="talks_about" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="followers">Followers</label>
                        <div class="col-sm-9">
                            <div class="form-control text-center">
                                <b class="FollowersMin">0</b>
                                <input class="form-control followers" type="text" data-slider-min="0" data-slider-max="1000000" data-slider-step="100" data-slider-value="[0, 1000000]" id="followers" name="followers" /> 
                                <b class="FollowersMax">10M</b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="following">Following</label>
                        <div class="col-sm-9">
                            <div class="form-control text-center">
                                <b class="FollowingMin">0</b>
                                <input class="form-control following" type="text" data-slider-min="0" data-slider-max="1000000" data-slider-step="100" data-slider-value="[0, 1000000]" id="following" name="following" /> 
                                <b class="FollowingMax">10M</b>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="follows">Follows</label>
                        <div class="col-sm-9">
                            <?php 
                            $ProspectSQL = mysql_query("SELECT `Influencers` FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = $Cust_ID LIMIT 0, 1");
                            if ( $ProspectSQL ) {
                                $InfluencersData = mysql_fetch_assoc($ProspectSQL);
                                $InfluencerIDs = explode(',', $InfluencersData['Influencers']); ?>
                                <select class="form-control" id="follows" name="follows">
                                    <option value="">Select Follows</option>

                                    <?php foreach ( $InfluencerIDs as $InfID ) {
                                        $SelectInfSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers` WHERE `influncer_twitter_id` = '$InfID' GROUP BY `influncer_twitter_id` ORDER BY `influence_id` DESC");
                                        if ( $SelectInfSQL ) {
                                            $SelectInfRow = mysql_num_rows($SelectInfSQL);
                                        } else {
                                            $SelectInfRow = 0;
                                        } ?>

                                        <?php if ( $SelectInfRow > 0 ) { ?>
                                            <?php while ( $InfData = mysql_fetch_assoc($SelectInfSQL) ) { ?>
                                                <option value="<?php echo $InfData['influncer_twitter_id']; ?>">
                                                    <?php echo $InfData['influncer_twitter_screenname']; ?>
                                                </option>
                                            <?php } ?>
                                        <?php } else { ?>
                                        
                                        <?php } ?>

                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="location">Location</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="location" name="location" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <span id="FilterMsgBox"></span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div id="filterLoader" class="pull-left hide loader"></div>
                        <button type="button" class="btn btn-info SearchFilterBtn" data-rel="SearchOnly"><i class="fa fa-search"></i> Search</button>
                        <button type="button" id="SaveSearchBtn" class="btn btn-danger hide SearchFilterBtn" data-rel="SaveSearch"><i class="fa fa-save"></i> Save & Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="RemoveFilterModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-info-circle"></i> Remove Filter</h4>
            </div>
            <div class="modal-body">
                <div class="RemoveProspectMsg">
                    <p><span class="glyphicon glyphicon-trash empty-icon"></span><br />Are you sure you want to remove this filter?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                <button type="button" id="remove_filter_yes" class="btn btn-danger">YES</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="ReplyTweetsMsgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4>Reply</h4>
            </div>
            <div class="modal-body">
                <textarea class="form-control" id="ReplyMsgBox" rows="1"></textarea>
            </div>
            <div class="modal-footer">
                <span class="pull-left" id="ReplyTweetMsg"></span>
                <button type="button" class="btn btn-info tweet-action pull-right" id="TweetReplySendBtn"><i class="fa fa-reply"></i> Reply</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="LimitedDMModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4>Limited Edition Direct Message</h4>
            </div>
			<div class="modal-body">
                <p>This feature is not available to you. Upgrade to get the full version of SocialSonicCRM</p>
            </div>
            <div class="modal-footer">
                <span class="pull-left" id="ReplyTweetMsg"></span>
                <button type="button" data-dismiss="modal" class="btn btn-info pull-right"><i class="fa fa-reply"></i> OK</button>
            </div>
        </div>
    </div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>