<?php
session_start();
require("../../lib/mainconfig.php");
$msg_type = "nothing";
$page = 'ticket';
if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."/logout/");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."/logout/");
	}
	include("../../lib/header.php");
	$msg_type = "nothing";

	$post_target = mysqli_real_escape_string($db, $_GET['id']);
	$check_ticket = mysqli_query($db, "SELECT * FROM tickets WHERE id = '$post_target' AND user = '$sess_username'");
	$data_ticket = mysqli_fetch_array($check_ticket);
	$check_reply = mysqli_query($db, "SELECT * FROM tickets_message WHERE ticket_id = '$post_target'");
	if (mysqli_num_rows($check_ticket) == 0) {
		header("Location: ".$cfg_baseurl."/ticket/tickets.php");
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
			} else if (strlen($post_message) > 2500) {
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

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo $cfg_baseurl; ?>/assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Balas Ticket| <?php echo $data_settings['web_name']; ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $data_settings['link_fav']; ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/swiper/swiper.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/css/pages/cards-advance.css" />
    <!-- Helpers -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <?php include("../../lib/sidebar_user.php"); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php include("../../lib/navbar_user.php"); ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
            
              <div class="row">
                <!-- Orders tabs-->
                <div class="col-md-6 col-lg-4 col-xl-12 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex justify-content-between pb-2 mb-1">
                      <div class="card-title mb-1">
                        <h5 class="m-0 me-2">Subjek: <?php echo $data_ticket['subject']; ?></h5>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="nav-align-top">
                        
                        <div class="tab-content pb-0">
                          <div class="tab-pane fade show active" id="navs-justified-new" role="tabpanel">
                            <ul class="timeline timeline-advance mb-2 pb-1">
                            <?php
						$usernameAbhi = $data_user['username'];
						$ticketUser = $data_ticket['user'];
						if ($usernameAbhi == $ticketUser){ ?>
                              <li class="timeline-item ps-4 border-left-dashed">
                                <span class="timeline-indicator timeline-indicator-success">
                                  <i class="ti ti-circle-check"></i>
                                </span>
                                <div class="timeline-event ps-0 pb-0">
                                  <div class="timeline-header">
                                    <small class="text-success text-uppercase fw-semibold"><?php echo $data_ticket['datetime']; ?></small>
                                  </div>
                                  <h6 class="mb-0"><?php echo $data_ticket['user']; ?></h6>
                                  <p class="text-muted mb-0"><?php echo nl2br($data_ticket['message']); ?></p>
                                </div>
                              </li>
                              <?php
                                    $check_message = mysqli_query($db, "SELECT * FROM tickets_message WHERE ticket_id = '$post_target' ORDER BY `datetime` ASC");
                                while ($data_message = mysqli_fetch_array($check_message)) {
                                    if ($data_message['sender'] == "Admin") {
                                        $msg_alert = "primary";
                                        $msg_icon = "mail";
                                        $msg_text = "";
                                        $msg_sender = $data_message['sender'];
                                    } else {
                                        $msg_alert = "danger";
                                        $msg_icon = "circle-check";
                                        $msg_text = "text-right";
                                        $msg_sender = $data_message['user'];
                                    }
                                ?>
                              <li class="timeline-item ps-4 border-left-dashed">
                                <span class="timeline-indicator timeline-indicator-<?php echo $msg_alert; ?>">
                                  <i class="ti ti-<?php echo $msg_icon; ?>"></i>
                                </span>
                                <div class="timeline-event ps-0 pb-0">
                                  <div class="timeline-header">
                                    <small class="text-<?php echo $msg_alert; ?> text-uppercase fw-semibold"><?php echo $data_message['sender']; ?></small>
                                  </div>
                                  <h6 class="mb-0"><?php echo $data_message['datetime']; ?></h6>
                                  <p class="text-muted mb-0"><?php echo nl2br($data_message['message']); ?></p>
                                </div>
                              </li>
                              <?php
                                }
                                ?>
                            </ul>
                            
                            <div class="row">
                                <form method="POST">
                                    <div class="row g-3">
                                    <div class="col-lg-11 col-sm-10 col-8">
                                    <textarea class="form-control" rows="2" name="message" placeholder="Balas Pesanan Kamu"></textarea>
                                    </div>
                                    <div class="col-lg-1 col-sm-2 col-4">
                                    <button class="btn btn-primary" name="submit" type="submit">Kirim Pesan</button>
                                    </div>
                                </form> 
                            </div>
                            <div class="clearfix"></div>
                            <?php
                                }
                                ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Orders tabs -->

                

                
                <!-- View sales -->
              </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php include("../../lib/footer_user.php"); ?>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/swiper/swiper.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/cards-advance.js"></script>
    <?php
if ($msg_type == "success") {
  echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
  echo '<script>
          swal("Success", "' . $msg_content . '", "success");
        </script>';
} else if ($msg_type == "error") {
  echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
  echo '<script>
          swal("Error", "' . $msg_content . '", "error");
        </script>';
}
?>
  </body>
</html>
<?php
	
} else {
	header("Location: ".$cfg_baseurl);
}
?>