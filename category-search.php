<?php /*! Category Search of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
    header('location:' . SITE_URL);
}

$page_title = 'Category Search';
require_once(dirname(__FILE__) . "/templates/header.php");

$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
$Prospect = $helper->getProspectByCustId($Cust_ID);
if ( count($Prospect) > 0 ) {
    if ( $Prospect['Influencers'] == '' ) {
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
            $editBtn = '<button data-toggle="tooltip" data-placement="top" title="Edit Search" class="btn btn-info EditCategorySearch" type="button"><i class="fa fa-edit"></i></button>';
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

                    <form id="frm_category_pipeline" action="<?php echo SITE_URL; ?>twitter-crm/" method="POST" class="<?php echo $readonly ? 'readonly' : '' ?>">
                    	<input type="hidden" name="category_submit" value="<?php echo time(); ?>" />
                        <?php if ( isset($Prospect['Prospect_ID']) ) { ?>
                        	<input type="hidden" id="hidprospectid" name="hidprospectid" value="<?php echo $Prospect['Prospect_ID']; ?>" />
                        <?php  } ?> 
                        <div class="form-group">
                            <h2>Find Prospects Based on the Niche of the Influencers they follow on Twitter</h2>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
								<?php 
                                $SelectCatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencer_categories`");
                                if ( $SelectCatSQL ) {
                                    $SelectCatRow = mysql_num_rows($SelectCatSQL); 
                                } else {
                                    $SelectCatRow = 0;
                                } ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
                                        <label for="category">
                                            Select From Our List of Curated Niches of Influencers Or Create a Custom Search: <br />
                                            <small class="text-muted">(You can select only one category at a time.)</small>
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                        <div class="loader hide"></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 text-right">
                                        <?php echo $editBtn; ?>&nbsp;&nbsp;
                                        <button class="btn btn-success category_submit" type="button"><i class="fa fa-search"></i> <?php echo $button_name; ?></button>
                                    </div>
                                </div>
                                <div class="row">
									<?php if ( $SelectCatRow > 0 ) {
                                        $i = 0; ?>
                                        <?php while ( $CatData = mysql_fetch_assoc($SelectCatSQL) ) {
                                            if ( isset($Prospect['Category']) ) { ?>
                                                <div class="PreCategoryBox">
                                                    <input <?php echo $readonly ? 'disabled' : ''; ?> type="radio" id="category_<?php echo $i; ?>" name="category" class="category" value="<?php echo $CatData['twscrapp_category_id']; ?>" <?php echo $CatData['twscrapp_category_id']==$Prospect['Category']?'checked':''; ?> />
                                                    <label for="category_<?php echo $i; ?>"><?php echo stripcslashes($CatData['twscrapp_category_name']); ?></label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="PreCategoryBox">
                                                    <input <?php echo $readonly ? 'disabled' : ''; ?> type="radio" id="category_<?php echo $i; ?>" name="category" class="category" value="<?php echo $CatData['twscrapp_category_id']; ?>" checked />
                                                    <label for="category_<?php echo $i; ?>"><?php echo stripcslashes($CatData['twscrapp_category_name']); ?></label>
                                                </div>
                                            <?php } ?>
                                        <?php $i++;
                                        } ?>
                                    <?php } ?>
                                    <div class="PreCategoryBox">
                                        <input <?php echo $readonly ? 'disabled' : ''; ?> type="radio" id="category_<?php echo $SelectCatRow; ?>" name="category" class="category" value="own"<?php echo (isset($Prospect['Category']) && $Prospect['Category']==0?'checked':''); ?> />
                                        <label for="category_<?php echo $SelectCatRow; ?>">Create Your Own</label>
                                    </div>
                                </div>
                                <?php if ( $readonly ) { ?>
                                	<input type="hidden" id="catReadonly" name="catReadonly" value="readonly" />
                                <?php } else { ?>
                                    <input type="hidden" id="catReadonly" name="catReadonly" value="editonly" />
                                <?php } ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <?php if ( isset($Prospect['Influencers']) ) { ?>
                                <input type="hidden" name="prev_influencers" id="prev_influencers" value="<?php echo $Prospect['Influencers']; ?>" />
                                <input type="hidden" name="influencer_screenname" id="influencer_screenname" value="<?php echo $Prospect['Influencers']; ?>" />
                            <?php } else { ?>
                                <input type="hidden" name="prev_influencers" id="prev_influencers" />
                                <input type="hidden" name="influencer_screenname" id="influencer_screenname" />
                            <?php } ?>
                            <div class="tags-wrapper" id="div_category_tags">No Category Selected.</div>
                            <div class="col-md-12 text-danger" id="screenErrors"></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7"></div>
                            <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                <div class="loader hide"></div>
                            </div>
                            <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 text-right">
                                <?php echo $editBtn; ?>&nbsp;&nbsp;
                                <button class="btn btn-success category_submit" type="button"><i class="fa fa-search"></i> <?php echo $button_name; ?></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form><br /><br />

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="CategoryModal" tabindex="-1" role="dialog" aria-labelledby="CategoryModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title" id="CategoryModalLabel">Alert</h4>
            </div>
            <div class="modal-body text-center">
            	<span class="glyphicon glyphicon-ban-circle empty-icon"></span><br>
            	<span style="font-size:18px;">Please choose a category.</span>
            </div>
			<div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
       	</div>
    </div>
</div>
<div class="modal fade" id="InfluncerModal" tabindex="-1" role="dialog" aria-labelledby="InfluncerModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title" id="InfluncerModalLabel">Alert</h4>
            </div>
            <div class="modal-body text-center">
            	<span class="glyphicon glyphicon-ban-circle empty-icon"></span><br>
            	<span style="font-size:18px;">Please select atleast one influencer.</span>
            </div>
			<div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
       	</div>
    </div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>