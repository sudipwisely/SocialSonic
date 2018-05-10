<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="<?php echo SITE_URL; ?>admin/">
                <img width="150" src="<?php echo SITE_URL; ?>admin/img/admin-logo.png" alt="logo" /> 
            </a>
            <div class="nav-collapse">
                <ul class="nav pull-right">
                    <li>
                        <a target="_blank" href="<?php echo SITE_URL; ?>">Visit Social Sonic</a>
                    </li>
                    <?php if ( isset($_SESSION['AdminUserLoggedIn']) ) { ?>
                        <li>
                            <a href="<?php echo SITE_URL; ?>admin/logout/">Logout</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>