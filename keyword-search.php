<?php /*! Keyword Search of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
    header('location:' . SITE_URL);
}

$page_title = 'Keyword Search';
require_once(dirname(__FILE__) . "/templates/header.php");

$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
$Prospect = $helper->getProspectByCustId($Cust_ID);
if ( count($Prospect) > 0 ) {
    if ( $Prospect['Talks_About'] == '' ) {
        $readonly = FALSE; 
        $button_name = "Create Campaign";
        $editBtn = '';
    } else {
        if ( isset($_POST['readonly']) ) {
            $readonly = FALSE; 
            $button_name = "Update Campaign";
            $editBtn = '';
        } else {
            $readonly = TRUE; 
            $button_name = "Show Prospects";
            $editBtn = '<button data-toggle="tooltip" data-placement="top" title="Edit Search" class="btn btn-info" type="button" id="EditKeywordSearch"><i class="fa fa-edit"></i></button>';
        }
    }
} else {
    $readonly = FALSE; 
    $button_name = "Create Campaign";
    $editBtn = '';
} ?>

<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content">
	<div class="group_content_topbar">
        <h2><i class="fa fa-tag"></i> <?php echo $page_title; ?></h2>
    </div>
    <div class="row tag_page">
        <div class="nano">
            <div class="nano-content">
                <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">

                    <form id="frm_keyword_pipeline" action="<?php echo SITE_URL; ?>twitter-crm/" method="POST" class="<?php echo $readonly ? 'readonly' : ''; ?>">
                        <input type="hidden" name="keyword_submit" value="<?php echo time(); ?>" />

                        <?php if ( isset($Prospect['Prospect_ID']) ) { ?>
                            <input type="hidden" id="hidprospectid" name="hidprospectid" value="<?php echo $Prospect['Prospect_ID']; ?>" />
                        <?php  } ?> 

                        <div class="form-group">
                            <h2>Set Your Target Preferences</h2>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                        <label for="tags">
                                            Enter keywords your prospect might use in their tweets: <br />
                                            <small class="text-muted">(You can add max. 10 keywords)</small>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                        <div class="loader hide"></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 text-right">
                                        <?php echo $editBtn; ?>&nbsp;&nbsp;
                                        <button data-toggle="tooltip" data-placement="top" title="Submit Search" class="btn btn-success keyword_submit" type="button"><i class="fa fa-search"></i> <?php echo $button_name; ?></button>
                                    </div>
                                </div>
                                <input type="text" <?php echo $readonly ? 'readonly' : ''; ?> class="form-control add_tag" data-tagsid="div_talk_about_tags" data-limit="10" data-storeid="txt_talk_about_tags" data-case="talk_about_tags" style="margin-bottom:5px;" />
                                <small>Hit 'Enter' after writing a keyword.</small>
                            </div>
                            <?php if ( $readonly ) { ?>
                                <input type="hidden" id="catReadonly" name="catReadonly" value="readonly" />
                            <?php } else { ?>
                                <input type="hidden" id="catReadonly" name="catReadonly" value="editonly" />
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
							<?php if ( isset($Prospect['Talks_About']) ) { ?>
                                <input type="hidden" id="txt_talk_about_tags" name="talk_about_tags" value="<?php echo $Prospect['Talks_About']; ?>" />
                            <?php } else {?> 
                                <input type="hidden" id="txt_talk_about_tags" name="talk_about_tags"/>
                            <?php }  ?>
                            <div class="tags-wrapper" id="div_talk_about_tags">Loading...</div>
                        </div>
                        <div class="clearfix"></div>
                    </form><br /><br />

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ProspectAboutModal" tabindex="-1" role="dialog" aria-labelledby="ProspectAboutModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title" id="ProspectAboutModalLabel">Alert</h4>
            </div>
            <div class="modal-body text-center">
            	<span class="glyphicon glyphicon-ban-circle empty-icon"></span><br>
            	<span style="font-size:18px;">You need to enter at least one keyword to start a campaign.</span>
            </div>
			<div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
       	</div>
    </div>
</div>

<div class="modal fade" id="spacialCharModal" tabindex="-1" role="dialog" aria-labelledby="spacialCharModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="spacialCharModalLabel">Alert</h4>
            </div>
            <div class="modal-body text-center">
                <span class="glyphicon glyphicon-ban-circle empty-icon"></span><br>
                <span style="font-size:18px;">Special Characters are not allowed in keyword.</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>