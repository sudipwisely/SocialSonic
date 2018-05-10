<?php /*! Products of this Application */

require_once(dirname(__FILE__) . "/config/config.php");
if ( !isset($_SESSION['Cust_ID']) ) {
   	header('location:' . SITE_URL);
}

$page_title = 'Products';
require_once(dirname(__FILE__) . "/templates/header.php"); ?>

<?php 
$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
$Customer = $helper->getCustomerById($Cust_ID); ?>

<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10 content">
    <div class="group_content_topbar">
        <h2><i class="fa fa-star"></i> Products</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="nano">
                    <div class="nano-content">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bhoechie-tab-container">
                                <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2 bhoechie-tab-menu">
                                    <div class="list-group">
                                        <?php $CategoryCount = 0;
                                        $CategoySQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "product_categories` ORDER BY `category_Name` ASC");
                                        if ( $CategoySQL ) {
                                            $CategoryIDs = array();
                                            $CategorySlugs = array();
                                            $i = 0;
                                            while ( $Category = mysql_fetch_assoc($CategoySQL) ) {
                                                $CategoryIDs[] = $Category['category_ID'];
                                                $CategorySlugs[] = $Category['category_slug']; ?>
                                                <a href="#" class="list-group-item <?php echo $i==0 ? 'active' : ''; ?> text-center">
                                                    <img width="48" height="48" src="<?php echo SITE_URL; ?>admin/uploads/categories/<?php echo $Category['category_Icon']; ?>" alt="" /><br />
                                                    <?php echo $Category['category_Name']; ?>
                                                </a>
                                            <?php $i++;
                                            }
                                        } ?>
                                    </div>
                                </div>
                                <div class="col-xs-9 col-sm-9 col-md-10 col-lg-10 bhoechie-tab">
                                    <div class="row">

                                        <?php 
                                        for ( $i = 0; $i < count($CategoryIDs); $i++ ) { ?>

                                            <div class="bhoechie-tab-content <?php echo $i==0 ? 'active' : ''; ?>">

                                                <?php 
                                                $ProductSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "products` WHERE `category_ID` = " . $CategoryIDs[$i]);
                                                if ( $ProductSQL ) {
                                                    if ( mysql_num_rows($ProductSQL) > 0 ) {
                                                        while ( $Product = mysql_fetch_assoc($ProductSQL) ) { ?>
                                                            
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="card">
                                                                    <div class="card-image">
                                                                        <img class="img-responsive" src="<?php echo SITE_URL; ?>admin/uploads/products/<?php echo $Product['product_image']; ?>">
                                                                        <span class="card-title"><?php echo $Product['product_name']; ?></span>
                                                                    </div>
                                                                    <div class="card-action">
                                                                        <a href="<?php echo $Product['funnel_url']; ?>" target="_blank">Funnel URL</a>
                                                                        <!--<a href="http://<?php //echo $helper->create_slug($Customer['Cust_hopCode']); ?>.<?php //echo $Product['vendor_Id']; ?>.hop.clickbank.net" target="_blank">Affiliate Link</a>-->
                                                                        <a href="<?php echo BLOG_URL; ?><?php echo $helper->create_slug($Customer['Cust_UserName']); ?>/<?php echo $CategorySlugs[$i]; ?>/<?php echo $Product['product_slug']; ?>/" target="_blank">Promotional Link</a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <?php 
                                                        }
                                                    } else { ?>

                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <h2>No Products found in this category.</h2>
                                                        </div>

                                                    <?php 
                                                    }
                                                } ?>
    
                                            </div>

                                        <?php } ?>

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