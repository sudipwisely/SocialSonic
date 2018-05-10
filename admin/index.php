<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . "/config/config.php"); ?>

<?php 
if ( isset($_SESSION['AdminUserLoggedIn']) ) {
    header('location:' . SITE_URL . 'admin/dashboard/');
    return false;
}

$msg = '';
if ( isset($_POST['login']) ) {
    $username = addslashes($_POST['username']);
    $password = addslashes($_POST['password']);

    if ( $username == ADMIN_USERNAME && $password == ADMIN_PASSWORD ) {
        $_SESSION['AdminUserLoggedIn'] = true;
        header('location:' . SITE_URL . 'admin/dashboard/');
        return false;
    } else {
        $msg = '<p style="color:#f00">Invalid username or password!</p>';
    }
} ?>

<?php $page_name = 'Admin Login'; ?>

<?php include(dirname(__FILE__) . '/templates/header.php'); ?>

<?php include(dirname(__FILE__) . '/templates/topbar.php'); ?>

<div class="account-container">
    <div class="content clearfix">
        <form action="" method="post" novalidate="">
            <h1>Member Login</h1>		
            <div class="login-fields">
                <p>Please provide your details</p>
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="" placeholder="Username" class="login username-field" required="required" />
                </div>
                <div class="field">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="" placeholder="Password" class="login password-field" required="required" />
                </div>
                <?php echo $msg; ?>
            </div>
            <div class="login-actions">
                <button type="submit" name="login" class="button btn btn-success btn-large">Sign In</button>
            </div>
        </form>
    </div>
</div><br /><br /><br /><br /><br />
    
<?php include(dirname(__FILE__) . '/templates/footer.php'); ?>