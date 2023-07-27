<?php
session_start();
require("../lib/mainconfig.php");
$check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
$data_settings = mysqli_fetch_assoc($check_settings);
$msg_type = "nothing";

if (isset($_POST['login'])) {
	$post_username = htmlspecialchars(trim($_POST['username']));
	$post_password = htmlspecialchars(trim($_POST['password']));
	$ip = $_SERVER['REMOTE_ADDR'];
	if (empty($post_username) || empty($post_password)) {
		$msg_type = "error";
		$msg_content = "Please Fill In All Inputs.";
	} else {
		$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
		if (mysqli_num_rows($check_user) == 0) {
			$msg_type = "error";
			$msg_content = "The username you entered is not registered.";
		} else {
			$data_user = mysqli_fetch_assoc($check_user);
			if ($data_user['level'] == "Developers" && $data_user['password'] <> "$post_password") {
			    $ip = $_SERVER['REMOTE_ADDR'];
				$msg_type = "error";
				$msg_content = "The Password You Enter Is Wrong.";
			} else if ($post_password <> $data_user['password']) {
				$msg_type = "error";
				$msg_content = "The Password You Enter Is Wrong.";
			} else if ($data_user['status'] == "Suspended") {
				$msg_type = "error";
				$msg_content = "Account Suspended.";
			} else {
				$_SESSION['user'] = $data_user;
				header("Location: ".$cfg_baseurl);
		}
	}
	}	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords" content="<?php echo $data_settings['seo_keywords']; ?>">
	<title><?php echo $data_settings['web_title']; ?></title>
	<link rel="icon" href="<?php echo $data_settings['link_fav']; ?>" type="image/x-icon">
	<!-- CSS -->
	
	<!--HEADER TAG-->
    <?php echo $data_settings['seo_meta']; ?>
    <!--HEADER TAG END-->

    <!--GTAG TAG-->
    <?php echo $data_settings['seo_analytics']; ?>
	<!--GTAG TAG END-->
	
	<link rel="stylesheet" href="../assets/css/app.css">
	<link rel="stylesheet" href="../css/style.css">
    <meta name="theme-color" content="#127AFB" />
</head>
<body class="light">
<div id="app">
<main>
    <div id="primary" class="p-t-b-100 height-full">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mx-md-auto paper-card login-card-1">
				<?php 
					if ($msg_type == "error") {
				?>
				<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<strong>Failed!</strong> <?php echo $msg_content; ?>
				</div>
				<?php
				}
				?>
                    <div class="text-center">
					<a href="<?php echo $cfg_baseurl; ?>">
							<img src="<?php echo $data_settings['link_logo_dark']; ?>" alt="<?php echo $data_settings['web_name']; ?>" class="login-card-logo">
						</a>
                    </div>
                    <br><br>
                    <form role="form" method="POST">
                        <div class="form-group has-icon"><i class="icon-user"></i>
                            <input type="text" class="form-control form-control-lg" placeholder="Username" name="username">
                        </div>
                        <div class="form-group has-icon"><i class="icon-lock"></i>
                            <input type="password" class="form-control form-control-lg" placeholder="Password" name="password">
                        </div>
                        <input type="submit" class="btn btn-primary btn-lg btn-block blue-bg" value="Login" name="login">
                        <br>
                        <hr>
                        <center><p class="forget-pass">Forgot your password?</p></center>
                        <a href="<?php echo $cfg_baseurl; ?>/register/" class="btn btn-success btn-lg btn-block">
                        <i class="icon-user-plus"></i> Sign Up
						</a>
                        <a href="<?php echo $cfg_baseurl; ?>/forgot/" class="btn btn-danger btn-lg btn-block">
                        <i class="icon-lock"></i> Reset It
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #primary -->
</main>
<div class="control-sidebar-bg shadow white fixed"></div>
</div>
<!--/#app -->
<script src="../assets/js/app.js"></script>
</body>
</html>