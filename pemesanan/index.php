<?php
session_start();
require("../lib/mainconfig.php");

/* CHECK USER SESSION */
if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);
    if (mysqli_num_rows($check_user) == 0) {
        header("Location: " . $cfg_baseurl . "/logout/");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: " . $cfg_baseurl . "/logout/");
    }

    include("../lib/header.php");
    $msg_type = "nothing";


    /* NEW ORDER HANDLER */
    if (isset($_POST['order'])) {
        $post_service = htmlspecialchars($_POST['service']);
        if (isset($_POST['comments'])) {
            $post_quantity = htmlspecialchars($_POST['quantity']);
            $post_comments = htmlspecialchars($_POST['comments']);
        } else if (isset($_POST['custom_mentions'])) {
            $post_quantity = htmlspecialchars($_POST['quantity']);
            $post_custom_mentions = str_replace(array("\r", "\n"), "\r\n", $_POST['custom_mentions']);
        } else {
            $post_quantity = htmlspecialchars($_POST['quantity']);
        }
        $post_comment = urlencode($_POST['comments']);
        $post_comment = str_replace('%5Cr%5Cn', "\r\n", $post_comment);
        $post_custom_mentions = urlencode($_POST['custom_mentions']);
        $post_custom_mentions = str_replace('%5Cr%5Cn', "\r\n", $post_custom_mentions);
        $post_custom_link = trim($_POST['custom_link']);
        $post_link = htmlspecialchars(trim($_POST['link']));
        $post_category = htmlspecialchars($_POST['category']);
        $post_notes = htmlspecialchars($_POST['notes']);
        $check_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_service' AND status = 'Active'");
        $data_service = mysqli_fetch_assoc($check_service);

        $check_orders = mysqli_query($db, "SELECT * FROM orders WHERE link = '$post_link' AND status IN ('Pending','Processing')");
        $data_orders = mysqli_fetch_assoc($check_orders);
        $rate = $data_service['price'] / 1000;
        $rate2 = $data_service['price_provider'] / 1000;
        $price = $rate * $post_quantity;
        $price_provider = $rate2 * $post_quantity;
        $service = $data_service['service'];
        $provider = $data_service['provider'];
        $post_category = $data_service['category'];
        $pid = $data_service['pid'];

        $check_highest_oid = mysqli_query($db, "SELECT * FROM `orders` ORDER BY `oid` DESC LIMIT 1");
        $highest_oid = mysqli_fetch_array($check_highest_oid);
        $oid = $highest_oid['oid'] + 1;

        $check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = '$provider'");
        $data_provider = mysqli_fetch_assoc($check_provider);


        if ($post_category == "IGF") {
            $id = file_get_contents("https://instagram.com/" . $post_link . "?__a=1");
            $id = json_decode($id, true);
            $start_count = $id['graphql']['user']['edge_followed_by']['count'];
        } else if ($post_category == "IGL") {
            $id = file_get_contents("" . $post_link . "?__a=1");
            $id = json_decode($id, true);
            $start_count = $id['graphql']['shortcode_media']['edge_media_preview_like']['count'];
        } else if ($post_category == "IGV") {
            $id = file_get_contents("" . $post_link . "?__a=1");
            $id = json_decode($id, true);
            $start_count = $id['graphql']['shortcode_media']['video_view_count'];
        } else {
            $start_count = "0";
        }
        if (empty($post_service) || empty($post_link) || empty($post_quantity)) {
            $msg_type = "error";
            $msg_content = "<script>swal('Error!', 'Please fill in the input.', 'error');</script> Please fill in the input.";
        } else if (mysqli_num_rows($check_service) == 0) {
            $msg_type = "error";
            $msg_content = "<script>swal('Error!', 'Service not found.', 'error');</script> Service not found.";
        } else if ($post_quantity < $data_service['min']) {
            $msg_type = "error";
            $msg_content = "<script>swal('Error!', 'The minimum number of orders is " . $data_service['min'] . ".', 'error');</script>The minimum number of orders is " . $data_service['min'] . ".";
        } else if ($post_quantity > $data_service['max']) {
            $msg_type = "error";
            $msg_content = "<script>swal('Error!', 'The maximum number of orders is " . $data_service['max'] . ".', 'error');</script>The maximum number of orders is " . $data_service['max'] . ".";
        } else if ($data_user['balance'] < $price) {
            $msg_type = "error";
            $msg_content = "<script>swal('Error!', 'Your balance is insufficient to make this purchase.', 'error');</script>Your balance is insufficient to make this purchase.";
        } else {

            // api data
            $api_link = $data_provider['link'];
            $api_key = $data_provider['api_key'];
            $api_id = $data_provider['api_id'];
            $pin = $data_provider["pin"];
            $code = $data_provider["code"];
            $secret_key = $data_provider["secret_key"];
            // end api data

            if ($provider == "MANUAL" || empty($provider)) {

                /* NEW ORDER MANUALLY */

                $api_postdata = "";
                $poid = $oid;
            } else {

                /* NEW ORDER VIA API */

                if (isset($pin)) {
                    //Check if have PIN (Dailypanel)
                    $api_postdata = "pin=$pin&api_key=$api_key&action=order&service=$pid&target=$post_link&quantity=$post_quantity&custom_comment=$post_comment&custom_link=$post_custom_link&usernames=$post_custom_mentions";
                }elseif (isset($secret_key)) { //BUZERPANEL
                    $api_postdata = "api_key=$api_key&action=order&secret_key=$secret_key&service=$pid&data=$post_link&quantity=$post_quantity&custom_comments=$post_comment&custom_link=$post_custom_link";

                } elseif (isset($api_id)) {
                    //IRVANKEDE
                    $api_link = $api_link . '/order';
                    $api_postdata = "api_id=$api_id&api_key=$api_key&service=$pid&target=$post_link&quantity=$post_quantity&custom_comments=$post_comment&custom_link=$post_custom_link";
                } elseif (!isset($api_id) && !isset($pin) && $code == "SMMTRY") {
                    //SMMTRY
                    $api_postdata = "api_key=$api_key&action=order&service=$pid&data=$post_link&quantity=$post_quantity&custom_comments=$post_comment&custom_link=$post_custom_link";
                } else {
                    $api_postdata = "key=$api_key&action=add&service=$pid&link=$post_link&quantity=$post_quantity&comments=$post_comment&username=$post_custom_link&usernames=$post_custom_mentions";
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_link);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $chresult = curl_exec($ch);
                curl_close($ch);
                $json_result = json_decode($chresult);

                // var_dump($json_result);
                // die();

                if (isset($pin)) {
                    //Check if have PIN (Dailypanel)
                    $poid = $json_result->msg->order_id;
                } elseif (isset($secret_key)) { //BUZZERPANEL
                    $poid = $json_result->data->id;
                    
                } else if (isset($api_id)) {
                    //Check if have api_id (IRVANKEDE)
                    $poid = $json_result->data->id;
                } elseif (!isset($api_id) && !isset($pin) && $code == "SMMTRY") {
                    //SMMTRY
                    $poid = $json_result->data->id;
                } else {
                    $poid = $json_result->order;
                }

                $type = "- Rp";
                $check_highest_oid = mysqli_query($db, "SELECT * FROM `orders` ORDER BY `oid` DESC LIMIT 1");
                $highest_oid = mysqli_fetch_array($check_highest_oid);
                $oid = $highest_oid['oid'] + 1;
            }
?>

            <!-- CLEAR POST DATA ON REFRESH -->
            <script>
                history.pushState({}, "", "")
            </script>

    <?php

            if (empty($poid)) {
                $msg_type = "error";
                $msg_content = "<script>swal('Error!', 'Server Maintenance.', 'error');</script> Server Maintenance.";
            } else {

                /* BALANCE DEDUCTION */

                $update_user = mysqli_query($db, "UPDATE users SET balance = balance-$price WHERE username = '$sess_username'");
                if ($update_user == TRUE) {

                    $check_balance = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
                    $data_balance = mysqli_fetch_assoc($check_balance);
                    $temp_balance = rupiah($data_balance['balance']);


                    /* BALANCE HISTORY */

                    $insert_order = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, clearance, msg, date, time, type) 
                                                        VALUES ('$sess_username', 'Cut Balance', '$price', '$temp_balance', 'Balance deducted for purchase $post_quantity $service OID : $oid', '$date', '$time', '$type')");
                    $insert_order = mysqli_query($db, "INSERT INTO orders (oid, poid, user, service, link, quantity, remains, start_count, price, price_provider, status, date, time, provider, place_from, top_ten) 
                                                        VALUES ('$oid', '$poid', '$sess_username', '$service', '$post_link', '$post_quantity', '$post_quantity', '$start_count', '$price', '$price_provider', 'Pending', '$date', '$time', '$provider', 'WEB', 'ON')");
                    $insert_order = mysqli_query($db, "INSERT INTO profit (oid, poid, user, service, link, quantity, remains, start_count, price, price_provider, status, date, time, provider, place_from, datetime) 
                                                        VALUES ('$oid', '$poid', '$sess_username', '$service', '$post_link', '$post_quantity', '$post_quantity', '$start_count', '$price', '$price_provider', 'Pending', '$date', '$time', '$provider', 'WEB', '$date $time')");
                    if ($insert_order == TRUE) {
                        $msg_type = "success";
                        $msg_content = "<script>swal('Success!', 'Your order was successfully placed.', 'success');</script><b>Order Received.</b><br /><b>Service:</b> $service<br /><b>Details:</b> $post_link<br /><b>Quantity:</b> " . number_format($post_quantity) . "<br><b>Price:</b> " . rupiah($price);
                    } else {
                        $msg_type = "error";
                        $msg_content = "<script>swal('Error!', 'Error system (2).', 'error');</script> Error system (2).";
                    }
                } else {
                    $msg_type = "error";
                    $msg_content = "<script>swal('Error!', 'Error system (1).', 'error');</script> Error system (1).";
                }
            }
        }
    }

    /* GENERAL WEB SETTINGS */

    $check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
    $data_settings = mysqli_fetch_assoc($check_settings);
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

    <title><?php echo $data_settings['web_name']; ?> | Pemesanan Sosmed</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $cfg_baseurl; ?>/assets/img/favicon/favicon.ico" />

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
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/select2/select2.css" />

    <!-- Page CSS -->

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

        <?php include("../lib/sidebar_user.php"); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php include("../lib/navbar_user.php"); ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
            	<?php
									if ($msg_type == "success") {
									?>
              <div class="alert alert-primary" role="alert">Berhasil! Pesanan Anda Sedang Diproses Server</div>
              <?php
									} else if ($msg_type == "error") {
									?>
							<div class="alert alert-danger" role="alert">Error! <?php echo $msg_content; ?></div>
							<?php
									}
									?>
              <!-- Basic Layout -->
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Form Pemesanan</h5>
                    </div>
                    <div class="card-body">
                      <form method="POST" autocomplete="off">
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">Kategori Layanan</label>
                          <select class="form-control" id="category" name="category">
													<option value="0">Pilih Salah Satu...</option>
													<?php
													$check_cat = mysqli_query($db, "SELECT * FROM service_cat WHERE status = 'Active' ORDER BY name ASC");
													while ($data_cat = mysqli_fetch_assoc($check_cat)) {
													?>
														<option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
													<?php
													}
													?>
												</select>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-company">Layanan</label>
                          <select class="form-control" name="service" id="service">
													<option value="0">Pilih Salah Satu...</option>
												</select>
                        </div>

                        <div id="note" class="mb-3">
												</div>
												<div id="input_data" class="mb-3">
												</div>
                        
                        
                        <button type="submit" name="order"  class="btn btn-primary">Pesan Sekarang</button>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Informasi Website</h5>
                      
                    </div>
                    <div class="card-body">
                      <p>Kami dengan senang hati menginformasikan bahwa semua sistem kami telah ditingkatkan dan kini beroperasi secara otomatis selama 24 jam sehari, tanpa henti. <br><br>Kami mengundang Anda untuk memesan layanan kami dengan nyaman. Namun, mohon diperhatikan bahwa dalam melakukan pemesanan, penting untuk memeriksa dan memastikan data target yang Anda masukkan benar-benar akurat, karena kesalahan input tidak dapat dibatalkan.<br><br> Selain itu, harap pastikan saldo akun Anda mencukupi untuk memproses pesanan Anda.<br><br>

											Terima kasih atas kepercayaan dan kerjasama Anda.</p>
                    </div>
                  </div>
                </div>
              </div>

             
            </div>
            <!-- / Content -->

            <!-- Footer -->
           <?php include("../lib/footer_user.php"); ?>
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
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/moment/moment.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="<?php echo $cfg_baseurl; ?>/assets/vendor/libs/select2/select2.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?php echo $cfg_baseurl; ?>/assets/js/form-layouts.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<script type="text/javascript" src="../js/order.js"></script>
  </body>
</html>




<?php
			
		} else {
			header("Location: " . $cfg_baseurl);
		}
			?>