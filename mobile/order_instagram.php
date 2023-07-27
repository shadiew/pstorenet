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
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">

    <meta name="theme-color" content="#ffffff">
    <title><?php echo $data_settings['web_name']; ?> | Order</title>
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords"
        content="<?php echo $data_settings['seo_keywords']; ?>" />

    <!-- favicon -->
    <link rel="icon" type="image/png" href="<?php echo $data_settings['link_fav']; ?>" sizes="32x32">
    <link rel="apple-touch-icon" href="<?php echo $data_settings['link_fav']; ?>">

    <!-- CSS Libraries-->
    <!-- bootstrap v4.6.0 -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!--
        theiconof v3.0
        https://www.theiconof.com/search
     -->
    <link rel="stylesheet" href="assets/css/icons.css">
    <!-- Remix Icon -->
    <link rel="stylesheet" href="assets/css/remixicon.css">
    <!-- Swiper 6.4.11 -->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
    <!-- Owl Carousel v2.3.4 -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- normalize.css v8.0.1 -->
    <link rel="stylesheet" href="assets/css/normalize.css">

    <!-- manifest meta -->
    <link rel="manifest" href="_manifest.json" />
    <?php echo $data_settings['seo_meta']; ?>
    <?php echo $data_settings['seo_analytics']; ?>
</head>


<body class="bg-snow">

    <!-- Start em_loading -->
    <section class="em_loading" id="loaderPage">
        <div class="spinner_flash"></div>
    </section>
    <!-- End. em_loading -->

    <div id="wrapper">
        <div id="content">
            <!-- Start main_haeder -->
            <header class="main_haeder header-sticky multi_item">
                <div class="em_side_right">
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" href="../mobile">
                        <i class="tio-chevron_left size-24 color-text"></i>
                        <span class="color-text size-14">Back</span>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">
                        Pemesanan Instagram
                    </span>
                </div>
                <div class="em_side_right">
                    
                </div>
            </header>
            <!-- End.main_haeder -->

            <section class="padding-t-70 components_page padding-b-30">

                <div class="bg-white padding-20">
                    <form method="POST" autocomplete="off">
                        <div class="form-group">
                            <label>Kategori Layanan</label>
                            <div class="input_group">
                                <select class="form-control" id="category" name="category">
                                    <option value="0">Select one...</option>
                                        <?php
                                            $check_cat = mysqli_query($db, "SELECT * FROM service_cat WHERE tipe = 'Instagram' ORDER BY name ASC");
                                             while ($data_cat = mysqli_fetch_assoc($check_cat)) {
                                             ?>
                                    <option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
                                        <?php
                                            }
                                         ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Layanan</label>
                            <div class="input_group">
                                <select class="form-control" name="service" id="service">
                                    <option value="0">Select category...</option>
                                </select>
                            </div>
                        </div>
                        <div id="note"></div>
                        <div id="input_data"></div>
                        <div class="bg-white padding-20 d-flex">
                            <button type="submit" name="order" class="btn justify-content-center bg-blue rounded-pill btn__default full-width">
                                <span class="color-white">Pesan Sekarang</span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <!-- jquery -->
    <script src="assets/js/jquery-3.6.0.js"></script>
    <!-- popper.min.js 1.16.1 -->
    <script src="assets/js/popper.min.js"></script>
    <!-- bootstrap.js v4.6.0 -->
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Owl Carousel v2.3.4 -->
    <script src="assets/js/vendor/owl.carousel.min.js"></script>
    <!-- Swiper 6.4.11 -->
    <script src="assets/js/vendor/swiper-bundle.min.js"></script>
    <!-- sharer 0.4.0 -->
    <script src="assets/js/vendor/sharer.js"></script>
    <!-- short-and-sweet v1.0.2 - Accessible character counter for input elements -->
    <script src="assets/js/vendor/short-and-sweet.min.js"></script>
    <!-- jquery knob -->
    <script src="assets/js/vendor/jquery.knob.min.js"></script>
    <!-- main.js -->
    <script src="assets/js/main.js" defer></script>
    <!-- PWA app service registration and works js -->
    <script src="assets/js/pwa-services.js"></script>
    
    <script type="text/javascript" src="../js/order_mobile.js"></script>
</body>
</html>
<?php
           
        } else {
            header("Location: " . $cfg_baseurl);
        }
            ?>