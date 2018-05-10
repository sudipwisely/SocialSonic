<?php /*! Header of this Application */

if ( !isset($_SESSION['Cust_ID']) ) { ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="description" content="<?php echo SITE_TITLE; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />

        <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_TITLE; ?></title>
        
        <link rel="shortcut icon" href="<?php echo SITE_URL; ?>images/SocialSonic-Icon.png" />

        <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
        <meta name="description" content="" />
        <link rel="canonical" href="<?php echo LOGIN_REDIRECT_URL; ?>" />
        <meta name="author" content="<?php echo SITE_TITLE; ?>" />
        
        <meta property="og:title" content="" />
        <meta property="og:description" content="" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo LOGIN_REDIRECT_URL; ?>" />
        <meta property="og:image" content="<?php echo SITE_TITLE; ?>images/SocialSonic-Logo.png" />
        <meta property="og:site_name" content="<?php echo SITE_TITLE; ?>" />

        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrapValidator.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrap-nav-wizard.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/font-awesome.min.css" type="text/css" />

        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/fonts.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/outer.css" type="text/css" />

        <?php if ( $_SERVER['PHP_SELF'] == '/customer-signup.php') { ?>
            <!-- Facebook Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '1817806488506518');
            fbq('track', 'Purchase', {value: '997.00', currency: 'USD'});
            </script>
            <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1817806488506518&ev=Purchase&cd[value]=997.00&cd[currency]=USD&noscript=1" /></noscript>
            <!-- DO NOT MODIFY -->
            <!-- End Facebook Pixel Code -->
        <?php } ?>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    </head>
    <body class="animated fadeIn">
        <input type="hidden" class="site_url" name="site_url" value="<?php echo SITE_URL; ?>" />

<?php } else { ?>

    <!DOCTYPE html>
    <html lang="en" class="no-js">
    <head>
        <meta charset="utf-8" />
        <meta name="description" content="<?php echo SITE_TITLE; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />

        <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_TITLE; ?></title>

        <link rel="shortcut icon" href="<?php echo SITE_URL; ?>images/SocialSonic-Icon.png" />

        <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
        <meta name="description" content="" />
        <link rel="canonical" href="<?php echo LOGIN_REDIRECT_URL; ?>" />
        <meta name="author" content="<?php echo SITE_TITLE; ?>" />
        
        <meta property="og:title" content="" />
        <meta property="og:description" content="" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo LOGIN_REDIRECT_URL; ?>" />
        <meta property="og:image" content="<?php echo SITE_TITLE; ?>images/SocialSonic-Logo.png" />
        <meta property="og:site_name" content="<?php echo SITE_TITLE; ?>" />

        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrapValidator.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrap-nav-wizard.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrap-slider.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/font-awesome.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/jquery.sidr.dark.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/tagit-stylish-yellow.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>admin/css/jquery.dataTables.min.css" type="text/css" />

        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/fonts.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/inner.css?random=<?php echo uniqid(); ?>" type="text/css" />
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/inner-responsive.css" type="text/css" />

        <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    </head>
    <body class="animated fadeIn">
        <input type="hidden" class="site_url" name="site_url" value="<?php echo SITE_URL;?>" />
        <input type="hidden" class="access_token" name="access_token" value="<?php echo isset($access_token) ? $access_token : ''; ?>" />

        <div class="container-fluid">
            <div class="row">
                <div class="main">
                
                    <?php require_once(dirname(__FILE__) . "/sidepanel.php"); ?>

<?php } ?>