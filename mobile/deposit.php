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
  $email = $data_user['email'];
  if ($email == "") {
    header("Location: " . $cfg_baseurl . "settings");
  }


  $check_paket = mysqli_query($db, "SELECT * FROM deposit_method WHERE Active = 'YES' AND name_method = 'TRIPAY'");
  $payment_method = array();

  if (mysqli_num_rows($check_paket) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($check_paket)) {
      array_push($payment_method, $row);
    }
  }

  /* GENERAL WEB SETTINGS */
  $title = "Deposit";
  include("../lib/header.php");
  $msg_type = "nothing";
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
    <title><?php echo $data_settings['web_name']; ?> | Deposit</title>
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords" content="<?php echo $data_settings['seo_keywords']; ?>" />
    <?php echo $data_settings['seo_meta']; ?>
    <?php echo $data_settings['seo_analytics']; ?>

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
                        Payment Method
                    </span>
                </div>

            </header>
            <!-- End.main_haeder -->
            <!-- Start emSection__payment -->
            <section class="emSection__payment">
                <div class="bg-white padding-20" id="bunder">
                    <form name="tripayForm" method="post" action="verify.php">
                            <div class="form-group">
                               
                                <div class="form-group">
                                    <input  class="form-control" placeholder="Rp." id="amount_field" required type="number" name="amount" min="10000" autofocus>
                                </div>
                            </div>
                             <div class="form-check">
                      <?php
                      $check_paket = mysqli_query($db, "SELECT * FROM deposit_method WHERE Active = 'YES' AND name_method = 'TRIPAY' ORDER BY id DESC");
                      while ($data_paket = mysqli_fetch_assoc($check_paket)) {
                      ?>
                        <label class="form-check-label" style="margin-top: 10px; margin-bottom: 10px;">
                          <input class="form-check-input" value="<?php echo $data_paket['code']; ?>" id="<?php echo $data_paket['code']; ?>" name="method" type="radio">
                          <?php echo $data_paket['name']; ?> <img height="25px" src="../assets/img/tripay/<?php echo $data_paket['code']; ?>.webp">
                        </label>
                        <br>
                      <?php
                      }
                      ?>
                    </div>
                    <hr>

                    <div id="information"></div>
                    <hr>
                    <button type="submit" class="btn bg-blue rounded-pill btn__default full-width" value="Deposit" name="deposit">
                        <span class="color-white">Deposit Sekarang</span>
                        <div class="icon rounded-pill">
                            <i class="tio-chevron_right"></i>
                        </div>
                    </button>
                    </form>
                 </div>
                 
            </section>
            
            <!-- End. emSection__payment -->
        </div>
    </div>

    <style> 
    #bunder {
      border-radius: 25px;
      background: #73AD21;
      padding: 20px; 
      width: 100%;
      height: 100%;  
    }
    </style>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      var paymentMethod = <?php echo json_encode($payment_method) ?>;
      var selectedPayment;
      $('input:radio[name="method"]')
        .change(function() {
          var str = "";
          str += $(this).val();
          selectedPayment = paymentMethod.filter(function(payment) {
            return payment.code == str;
          });
          if (selectedPayment.length > 0) {
            $("#information").html("<span>" + selectedPayment[0]?.note + "</span><br><b>Admin Fee: " + selectedPayment[0]?.rate + "</b>");
          } else {
            $("#information").html("<span>Please Select Payment</span>");
          }
        })
        .change();
    });
  </script>
    
</body>

</html>
<?php
  
} else {
  header("Location: " . $cfg_baseurl);
}
?>