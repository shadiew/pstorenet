<?php
session_start();
require("../lib/mainconfig.php");
    
/* CHECK USER SESSION */  
if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);
    if (mysqli_num_rows($check_user) == 0) {
        header("Location: ".$cfg_baseurl."/logout/");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$cfg_baseurl."/logout/");
    }
    $email = $data_user['email'];
    if ($email == "") {
    header("Location: ".$cfg_baseurl."settings");
    }

    $page = 'manual';
    $title = "Deposit";
    include("../lib/header.php");
    $msg_type = "nothing";


    /* DEPOSIT SUBMISSION HANDLER */
    if (isset($_POST['submit'])) {
        $post_method0 = htmlspecialchars($_POST['method']);
        $post_method = htmlspecialchars(filter_var($post_method0, FILTER_SANITIZE_NUMBER_FLOAT));
        $post_quantity = (float)$_POST['quantity'];
        $post_transid = htmlspecialchars($_POST['transid']);
        $bank = mysqli_query($db, "SELECT * FROM deposit_method where id ='$post_method'");
        $ambil = mysqli_fetch_assoc($bank);
        $bankname = $ambil['name'];
        $rekening = $ambil['data'];
        $penerima = $ambil['note'];
        $rate_method2 = $ambil['rate'];
        $rate_method = strval($rate_method2);
        $qcheckd = mysqli_query($db,"SELECT * FROM history_topup WHERE username = '$sess_username' AND status = 'NO'");
        $countd = mysqli_num_rows($qcheckd);

        $balance_amount = $post_quantity-($post_quantity*($rate_method/100));
        
        $demo = $data_user['status'];
        if ($demo == "Demo") {
            $msg_type = "error";
            $msg_content = "Sorry this feature is not available for Demo users.";
        }
        else if (empty($post_quantity) || empty($post_method) || empty($post_transid)) {
            $msg_type = "error";
            $msg_content = "Please fill in all inputs first.".$post_quantity.$post_method.$post_transid;
        } else if ($countd >= 3) {
            $msg_type = "error";
            $msg_content = "Please Complete the Request for a Previous Deposit to Make a Request for a New Deposit.";   
        }
        else if ($post_quantity < 0.05) {
            $msg_type = "error";
            $msg_content = "Minimum deposit is $0.05";
        } else {

            /* GENERATE DEPOSIT ID */
            $check_highest_id = mysqli_query($db, "SELECT * FROM `history_topup` ORDER BY `id_depo` DESC LIMIT 1");
            $highest_id = mysqli_fetch_array($check_highest_id);
            $id_depo = $highest_id['id_depo'] + 1;
            
            /* UPDATE DEPOSIT HISTORY */
            $insert_topup = mysqli_query($db, "INSERT INTO history_topup (provider, amount, jumlah_transfer, username, user, norek_tujuan_trf, nopengirim, date, time, status, type, id_depo, top_ten, name_method, penerima) VALUES ('$post_method','$balance_amount','$post_quantity','$sess_username','$sess_username','$rekening','$post_transid','$date','$time','NO','WEB','$id_depo','ON', '$bankname', '$penerima')");
            if ($insert_topup == TRUE) {
                $msg_type = "success";
                $msg_content = "<b>Deposit Manual Dibuat.</b><br />Metode: $bankname <br /><b>No.Rekening:</b> $rekening<br /><b>Penerima:</b> $penerima<br /><b>Jumlah Dibayar:</b> Rp. $balance_amount";
                
            } else {
                $msg_type = "error";
                $msg_content = "<b>Failed:</b> System error.";
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

    <title><?php echo $data_settings['web_name']; ?> | Manual Deposit</title>

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
              <div class="alert alert-primary" role="alert"><?php echo $msg_content; ?></div>
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
                      <h5 class="mb-0">Deposit Manual</h5>
                    </div>
                    <div class="card-body">
                      <form method="POST" autocomplete="off" name='autoSumForm'>
                        
                      
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-company">Metode Pembayaran</label>
                          <select class="form-control" name="method" id="method">
                            <option value="0">Pilih Salah Satu</option>
                            <?php
                              $check_paket = mysqli_query($db, "SELECT * FROM deposit_method WHERE name_method = 'MANUAL' ORDER BY id DESC");
                              while ($data_paket = mysqli_fetch_assoc($check_paket)) {
                              ?>
                              <option value="<?php echo $data_paket['id']; ?>"><?php echo $data_paket['name']; ?></option>
                              <?php
                              }
                              ?>
                          </select>
                        </div>
                        
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-company">Jumlah Deposit</label>
                          <input type="number" name="quantity" step="0.0001" class="form-control" placeholder="Rp.150.000" onkeyup="get_total(this.value).value;">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-company">Detail Transaksi</label>
                          <textarea type="text" rows="3" name="transid" class="form-control" placeholder="ID Transaction / Pengirim / Rekening / Email"></textarea>
                        </div>
                        

                        
                        
                        <button type="submit" name="submit" class="btn btn-primary">Deposit Sekarang</button>
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
                      <p>Fitur Deposit Manual adalah fasilitas yang disediakan untuk melakukan penambahan dana secara langsung. Jika Anda ingin mengonfirmasi penambahan dana, silakan hubungi admin. Dengan fitur ini, Anda dapat dengan mudah menambahkan dana yang diinginkan dan menghubungi admin untuk mengkonfirmasikannya.</p>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    
  </body>
</html>

<?php
    
} else {
    header("Location: ".$cfg_baseurl);
}
?>