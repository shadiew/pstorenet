<?php
session_start();
require("../../../lib/mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."/logout/");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."/logout/");
	}
	include("../../../lib/header.php");
	$msg_type = "nothing";

	$post_target = mysqli_real_escape_string($db, $_GET['id']);
	$check_ticket = mysqli_query($db, "SELECT * FROM tickets WHERE id = '$post_target' AND user = '$sess_username'");
	$data_ticket = mysqli_fetch_array($check_ticket);
	$check_reply = mysqli_query($db, "SELECT * FROM tickets_message WHERE ticket_id = '$post_target'");
	if (mysqli_num_rows($check_ticket) == 0) {
		header("Location: ".$cfg_baseurl."ticket/tickets.php");
	} else {
		mysqli_query($db, "UPDATE tickets SET seen_user = '1' WHERE id = '$post_target'");
		if (isset($_POST['submit'])) {
			$post_message = htmlspecialchars($_POST['message']);
			$antibug = (false === strpbrk($post_message, "#$^*[]';{}|<>~")) ? 'Allowed' : "Allowed";
			if ($data_ticket['status'] == "Closed") {
				$msg_type = "error";
				$msg_content = "Ticket has been closed, please create a new ticket.";
			} else if (empty($post_message)) {
				$msg_type = "error";
				$msg_content = "Please Fill in All Inputs.";
			} else if ($antibug == "Disallowed") {
					$msg_type = "error";
					$msg_content = "The Character You Input Is Not Allowed.";
			} else if (strlen($post_message) > 500) {
				$msg_type = "error";
				$msg_content = "Maximum of 500 characters.";
			} else {
               	$check_staff = mysqli_query($db, "SELECT * FROM staff");
            	$data_staff = mysqli_fetch_assoc($check_staff);
	            $ip = $_SERVER['REMOTE_ADDR'];
         		$last_update = "$date $time";
				$insert_ticket = mysqli_query($db, "INSERT INTO tickets_message (ticket_id, sender, user, username_sender, message, datetime, ip) VALUES ('$post_target', '$sess_username', '$sess_username', '$sess_username', '$post_message', '$last_update', '$ip')");
				$update_ticket = mysqli_query($db, "UPDATE tickets SET last_update = '$last_update' WHERE id = '$post_target'");
				if (mysqli_num_rows($check_reply) > 0) {
					mysqli_query($db, "UPDATE tickets SET status = 'Waiting', seen_admin = '0' WHERE id = '$post_target'");
				}
				if ($insert_ticket == TRUE) {
					$msg_type = "success";
					$msg_content = "Ticket Sent.";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Failed:</b> System error.";
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">

    <meta name="theme-color" content="#ffffff">
    <title><?php echo $data_settings['web_name']; ?> | Balas Pesan</title>
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords" content="<?php echo $data_settings['seo_keywords']; ?>" />
    <?php echo $data_settings['seo_meta']; ?>
    <?php echo $data_settings['seo_analytics']; ?>

    <!-- favicon -->
    <link rel="icon" type="image/png" href="<?php echo $data_settings['link_fav']; ?>" sizes="32x32">
    <link rel="apple-touch-icon" href="<?php echo $data_settings['link_fav']; ?>">

    <!-- CSS Libraries-->
    <!-- bootstrap v4.6.0 -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <!--
        theiconof v3.0
        https://www.theiconof.com/search
     -->
    <link rel="stylesheet" href="../../assets/css/icons.css">
    <!-- Remix Icon -->
    <link rel="stylesheet" href="../../assets/css/remixicon.css">
    <!-- Swiper 6.4.11 -->
    <link rel="stylesheet" href="../../assets/css/swiper-bundle.min.css">
    <!-- Owl Carousel v2.3.4 -->
    <link rel="stylesheet" href="../../assets/css/owl.carousel.min.css">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <!-- normalize.css v8.0.1 -->
    <link rel="stylesheet" href="../../assets/css/normalize.css">

    <!-- manifest meta -->
    <link rel="manifest" href="_manifest.json" />
</head>

<body>

    <!-- Start em_loading -->
    <section class="em_loading" id="loaderPage">
        <div class="spinner_flash"></div>
    </section>
    <!-- End. em_loading -->

    <div id="wrapper">
        <div id="content">
            <!-- Start main_haeder -->
            <?php
						$usernameAbhi = $data_user['username'];
						$ticketUser = $data_ticket['user'];
						if ($usernameAbhi == $ticketUser){ ?>
            <header class="header_tab head_conversation border-b border-b-solid border-snow">
                <div class="main_haeder multi_item">
                    <div class="em_side_right">
                        <a class="btn bg-transparent rounded-circle justify-content-start" href="../">
                            <i class="tio-chevron_left"></i>
                        </a>
                    </div>
                    <div class="item_userChat ml-0">
                        <div class="media">
                            <img class="img-user" src="../../assets/img/persons/avatar.png" alt="">
                            <div class="media-body my-auto">
                                <div class="txt">
                                    <h1><?php echo $data_ticket['user']; ?></h1>
                                    <p class="color-green">Online</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="em_side_right">
                        <!-- Can you add something here.. -->
                    </div>
                </div>
            </header>
            <!-- End.main_haeder -->

            <section class="emPage__conversation padding-t-70 padding-b-80">

            <div class="item_user">
                    <div class="media">
                        <div class="imgProfile">
                            <img src="../../assets/img/persons/avatar.png" alt="">
                        </div>
                        <div class="media-body">
                            <div class="content_sms">
                                <p class="item_msg">
                                    <?php echo nl2br($data_ticket['message']); ?>
                                </p>
                                
                                <div class="time"><?php echo $data_ticket['datetime']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
<?php
	$check_message = mysqli_query($db, "SELECT * FROM tickets_message WHERE ticket_id = '$post_target' ORDER BY `datetime` ASC");
while ($data_message = mysqli_fetch_array($check_message)) {
	if ($data_message['sender'] == "Admin") {
		$msg_alert = "success";
		$msg_notif =  "item_user __me";
		$msg_text = "";
		$msg_sender = $data_message['sender'];
	} else {
		$msg_alert = "info";
		$msg_text = "text-right";
		$msg_notif =  "item_user";
		$msg_sender = $data_message['user'];
	}
?>

                <div class="<?php echo $msg_notif; ?>">
                    <div class="media">
                        <div class="imgProfile">
                            <img src="../../assets/img/persons/avatar.png" alt="">
                        </div>
                        <div class="media-body">
                            <div class="content_sms">
                                <p class="item_msg">
                                    <?php echo nl2br($data_message['message']); ?>
                                </p>
                                
                                <div class="time"><?php echo $data_message['datetime']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
<?php
}
?>
                <div class="env-pb bg-white fixed w-100 bottom-0">
                	<form method="POST">
                    <div class="bk_footer_input emBK__buttonsShare">
                        
                        <div class="form-group m-0">
                            <input type="text" class="form-control" name="message" placeholder="Type a message here">
                        </div>
                        <button type="submit" name="submit" class="btn btn_defSend rounded-10">
                            <svg id="Iconly_Bulk_Send" data-name="Iconly/Bulk/Send" xmlns="http://www.w3.org/2000/svg"
                                width="20" height="20" viewBox="0 0 20 20">
                                <g id="Send" transform="translate(1.667 1.667)">
                                    <path id="Fill_1" data-name="Fill 1"
                                        d="M16.19.482A1.615,1.615,0,0,0,14.581.065L1.173,3.939A1.6,1.6,0,0,0,.02,5.2,1.863,1.863,0,0,0,.855,6.94L5.048,9.5a1.09,1.09,0,0,0,1.341-.159l4.8-4.8a.613.613,0,0,1,.883,0,.629.629,0,0,1,0,.883l-4.809,4.8A1.092,1.092,0,0,0,7.1,11.565l2.562,4.208a1.668,1.668,0,0,0,1.592.774,1.62,1.62,0,0,0,1.358-1.15L16.59,2.09a1.619,1.619,0,0,0-.4-1.608"
                                        transform="translate(0 0)" fill="#fff" />
                                    <path id="Combined_Shape" data-name="Combined Shape"
                                        d="M3.97,6.355a.625.625,0,0,1,0-.883L5.108,4.333a.625.625,0,1,1,.884.884L4.854,6.355a.625.625,0,0,1-.883,0ZM3.317,3.2a.625.625,0,0,1,0-.884L4.455,1.176a.625.625,0,0,1,.884.884L4.2,3.2a.625.625,0,0,1-.883,0ZM.183,2.2a.625.625,0,0,1,0-.884L1.321.182a.625.625,0,0,1,.884.884L1.066,2.2a.625.625,0,0,1-.884,0Z"
                                        transform="translate(0.217 9.952)" fill="#fff" opacity="0.4" />
                                </g>
                            </svg>

                        </button>
                        
                    </div>
                    </form>
                </div>
                <?php
}
?>
            </section>

        </div>
    </div>

    <!-- jquery -->
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <!-- popper.min.js 1.16.1 -->
    <script src="../../assets/js/popper.min.js"></script>
    <!-- bootstrap.js v4.6.0 -->
    <script src="../../assets/js/bootstrap.min.js"></script>

    <!-- Owl Carousel v2.3.4 -->
    <script src="../../assets/js/vendor/owl.carousel.min.js"></script>
    <!-- Swiper 6.4.11 -->
    <script src="../../assets/js/vendor/swiper-bundle.min.js"></script>
    <!-- sharer 0.4.0 -->
    <script src="../../assets/js/vendor/sharer.js"></script>
    <!-- short-and-sweet v1.0.2 - Accessible character counter for input elements -->
    <script src="../../assets/js/vendor/short-and-sweet.min.js"></script>
    <!-- jquery knob -->
    <script src="../../assets/js/vendor/jquery.knob.min.js"></script>
    <!-- main.js -->
    <script src="../../assets/js/main.js" defer></script>
    <!-- PWA app service registration and works js -->
    <script src="../../assets/js/pwa-services.js"></script>
</body>
</html>


<?php
	
} else {
	header("Location: ".$cfg_baseurl);
}
?>