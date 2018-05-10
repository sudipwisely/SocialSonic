<?php /*! Sidebar navigation of this Application */

$Customer = $helper->getCustomerById($_SESSION['Cust_ID']);
$page_name = basename($_SERVER["SCRIPT_FILENAME"], '.php'); ?>
<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2 sidebar">
	<div data-spy="affix" data-offset-top="60" data-offset-bottom="200">
    	<div class="mobile-menu-logo">
            <div id="mobile-header">
                <a id="responsive-menu-button" href="#sidr-main">
                    <div class="sb-toggle-left">
                        <div class="navicon-line"></div>
                        <div class="navicon-line"></div>
                        <div class="navicon-line"></div>
                    </div>
                </a>
            </div>
            <div class="mobile-logo text-center">
                <a href="<?php echo SITE_URL; ?>">
                    <img src="<?php echo SITE_URL; ?>images/ss-logo.png" alt="SocialSonic" />
                </a>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="logo text-center">
            	<a href="<?php echo SITE_URL; ?>">
                	<img width="32" src="<?php echo SITE_URL;?>images/SocialSonic-Icon.png" alt="" /><br />
                    <img width="150" src="<?php echo SITE_URL;?>images/SocialSonic-Text.png" alt="SocialSonic" />
                </a>
            </div>
        </div>
        <div class="side_nav" id="navigation">
            <div class="row">
    			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					<li class="divider"></li>
    				<li>
    				    <a tabindex="-1" target = '_blank' href="<?php echo SITE_URL; ?>tutorials/"><i class="fa fa-university"></i> Tutorials</a>
    				</li>
                    <li class="divider"></li>
    				<li <?php if ( $page_name == "twitter-crm" ) { ?>class="active"<?php } ?>>
    				    <a tabindex="-1" href="<?php echo SITE_URL; ?>twitter-crm/"><i class="fa fa-twitter"></i> Twitter CRM</a>
    				</li>
    				<li class="divider"></li>
                    <li <?php if ( $page_name == "keyword-search" ){ ?>class="active"<?php } ?>>
                        <a tabindex="-1" href="<?php echo SITE_URL; ?>keyword-search/"><i class="fa fa-search"></i> Keyword Search</a>
                    </li>
    				<li class="divider"></li>
                    <li <?php if ( $page_name == "category-search" ){ ?>class="active"<?php } ?>>
                        <a tabindex="-1" href="<?php echo SITE_URL; ?>category-search/"><i class="fa fa-user"></i> Category Search</a>
                    </li>
                    <li class="divider"></li>
                    <li <?php if ( $page_name == "lead-responses" ){ ?>class="active"<?php } ?>>
                        <a tabindex="-1" href="<?php echo SITE_URL; ?>lead-responses/"><i class="fa fa-reply"></i> Lead Responses</a>
                    </li>
                    <li class="divider"></li>
    			</ul>
            </div>
			<div class="clearfix"></div>
			<div class="footer">
				<p style="margin-top:25px;">&copy; <font color="#17719A">Social</font> <font color="#f48204">Sonic</font> 2016</p>
			</div>
        </div>
    </div>
</div>

<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 topbar">
	<ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo SITE_URL; ?>faq/"><i class="fa fa-info-circle"></i> FAQ</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> SETTINGS <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo SITE_URL; ?>products/"><i class="fa fa-star"></i> Products</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="<?php echo SITE_URL; ?>accounts/"><i class="fa fa-user"></i> My Account</a></li>
                <?php if ( $Customer['Cust_Payment_Type'] == 'trip' ) { ?>
                    <li>
                        <a target="_blank" href="https://123employee.infusionsoft.com/app/orderForms/e982d81f-89ac-4837-92ba-93a500eea89d">
                            <i class="fa fa-cogs"></i> Upgrade
                        </a>
                    </li>
                <?php } ?>
                <li><a href="<?php echo SITE_URL; ?>change-password/"><i class="fa fa-lock"></i> Change Password</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="<?php echo SITE_URL; ?>logout/"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</div>