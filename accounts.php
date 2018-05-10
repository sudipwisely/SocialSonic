<?php /*! My Account of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL);
}

$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
$Customer = $helper->getCustomerById($Cust_ID);
$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
$account  = $twitteroauth->get('users/show', array("user_id" => $Customer['Cust_Twitter_ID']));

$page_title = 'My Account';
require_once(dirname(__FILE__) . "/templates/header.php"); ?>

<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content">
  	<div class="group_content_topbar">
        <h2><i class="fa fa-user"></i> My Account</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="tweets-userboxWrap">
                        <img width="100%" class="img-responsive" src="<?php echo str_replace('_normal', '', $account->profile_image_url); ?>" alt="<?php echo $account->name; ?>" />
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <h2><?php echo $account->name; ?><br /><small>@<?php echo $account->screen_name; ?></small></h2>
                            <hr />
                            <table width="100%" class="text-center">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">TWEETS</td>
                                        <td class="text-muted">FOLLOWING</td>
                                        <td class="text-muted">FOLLOWERS</td>
                                    </tr>
                                    <tr>
                                        <td class="text-info"><font size="5"><?php echo $account->statuses_count; ?></font></td>
                                        <td class="text-info"><font size="5"><?php echo $account->friends_count; ?></font></td>
                                        <td class="text-info"><font size="5"><?php echo $account->followers_count; ?></font></td>
                                    </tr>
                                </tbody>
                            </table><br />
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-9 col-lg-9">
                    <div class="row">
                        <div class="nano">
                            <div class="nano-content">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 accounts-page">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
                                        <li role="presentation"><a href="#twitter" aria-controls="twitter" role="tab" data-toggle="tab">Twitter</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="general">
                                            <h3>General Account Settings</h3>
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td width="30%">Name</td>
                                                        <td width="70%">
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                    <?php echo $Customer['Cust_FirstName']; ?> <?php echo $Customer['Cust_LastName']; ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Username</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                    <?php echo $Customer['Cust_UserName']; ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Password</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">********</div>
                                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
                                                                    <a href="<?php echo SITE_URL; ?>change-password/" class="btn btn-xs btn-default small">
                                                                        <i class="fa fa-edit"></i> Change
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                    <?php echo $Customer['Cust_Email']; ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Clickbank Hop Code</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                                                    <span class="Cust_Value"><?php echo $Customer['Cust_hopCode']; ?></span>
                                                                    <input type="text" name="Cust_hopCode" id="Cust_hopCode" class="form-control Cust_Field hide" value="<?php echo $Customer['Cust_hopCode']; ?>" />
                                                                </div>
                                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
                                                                    <a href="#" class="btn btn-xs btn-info AccountSaveBtn hide small"><i class="fa fa-check"></i> Save</a>&nbsp;
                                                                    <a href="#" class="btn btn-xs btn-default AccountEditBtn small"><i class="fa fa-edit"></i> Change</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="twitter">
                                            <h3>Twitter Details Settings</h3>
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td>Consumer Key (API Key)</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                                                    <span class="Cust_Value"><?php echo $Customer['Cust_API_Key']; ?></span>
                                                                    <input type="text" name="Cust_API_Key" id="Cust_API_Key" class="form-control Cust_Field hide" value="<?php echo $Customer['Cust_API_Key']; ?>" />
                                                                </div>
                                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
                                                                    <a href="#" class="btn btn-xs btn-info AccountSaveBtn hide small"><i class="fa fa-check"></i> Save</a>&nbsp;
                                                                    <a href="#" class="btn btn-xs btn-default AccountEditBtn small"><i class="fa fa-edit"></i> Change</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Consumer Secret (API Secret)</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                                                    <span class="Cust_Value"><?php echo $Customer['Cust_API_Secret']; ?></span>
                                                                    <input type="text" name="Cust_API_Secret" id="Cust_API_Secret" class="form-control Cust_Field hide" value="<?php echo $Customer['Cust_API_Secret']; ?>" />
                                                                </div>
                                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
                                                                    <a href="#" class="btn btn-xs btn-info AccountSaveBtn hide small"><i class="fa fa-check"></i> Save</a>&nbsp;
                                                                    <a href="#" class="btn btn-xs btn-default AccountEditBtn small"><i class="fa fa-edit"></i> Change</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Owner (Screen Name)</td>
                                                        <td><?php echo $Customer['Cust_Screen_Name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Owner ID (Twitter ID)</td>
                                                        <td><?php echo $Customer['Cust_Twitter_ID']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Access Token</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                                                    <span class="Cust_Value"><?php echo $Customer['Cust_Access_Token']; ?></span>
                                                                    <input type="text" name="Cust_Access_Token" id="Cust_Access_Token" class="form-control Cust_Field hide" value="<?php echo $Customer['Cust_Access_Token']; ?>" />
                                                                </div>
                                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
                                                                    <a href="#" class="btn btn-xs btn-info AccountSaveBtn hide small"><i class="fa fa-check"></i> Save</a>&nbsp;
                                                                    <a href="#" class="btn btn-xs btn-default AccountEditBtn small"><i class="fa fa-edit"></i> Change</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Access Token Secret</td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                                                    <span class="Cust_Value"><?php echo $Customer['Cust_Token_Secret']; ?></span>
                                                                    <input type="text" name="Cust_Token_Secret" id="Cust_Token_Secret" class="form-control Cust_Field hide" value="<?php echo $Customer['Cust_Token_Secret']; ?>" />
                                                                </div>
                                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
                                                                    <a href="#" class="btn btn-xs btn-info AccountSaveBtn hide small"><i class="fa fa-check"></i> Save</a>&nbsp;
                                                                    <a href="#" class="btn btn-xs btn-default AccountEditBtn small"><i class="fa fa-edit"></i> Change</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
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
        </div>
    </div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/footer.php"); ?>