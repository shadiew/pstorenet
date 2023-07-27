<?php
session_start();
require("../../lib/mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);
    if (mysqli_num_rows($check_user) == 0) {
        header("Location: " . $cfg_baseurl . "/logout/");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: " . $cfg_baseurl . "/logout/");
    } else if ($data_user['level'] != "Developers") {
        header("Location: " . $cfg_baseurl);
    } else {
        if (isset($_POST['add'])) {
            $post_convert_rate = $_POST['convet_rate'];
            $post_code = $_POST['code'];
            $post_limit = $_POST['limit'];
            $post_markup = $_POST['markup'];
            $post_delete = $_POST['delete'];

            $check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = '$post_code'");
            $data_provider = mysqli_fetch_assoc($check_provider);

            $p_apikey = $data_provider['api_key'];
            $p_link = $data_provider['link'];
            $p_pin = $data_provider['pin'];
            $p_api_id = $data_provider['api_id'];
            $p_code = $data_provider['code'];
            $p_secret_key = $data_provider['secret_key'];

            // IF USE PIN DO CUSTOM CURL (Eg: DAILYPANEL)
            if (isset($p_pin)) {
                $order_postdata = "pin=$p_pin&api_key=$p_apikey&action=services";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $p_link);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $chresult = curl_exec($ch);
                curl_close($ch);
                $decoded_result = json_decode($chresult, true);
                $servicse = $decoded_result["msg"];
            } elseif (isset($p_api_id)) { //IRVANKEDE
                $order_postdata = "api_id=$p_api_id&api_key=$p_apikey";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $p_link . '/services');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $chresult = curl_exec($ch);
                curl_close($ch);
                $decoded_result = json_decode($chresult, true);
                $servicse = $decoded_result["data"];
            
              } elseif (isset($p_secret_key)) { //Buzzerpanel
                $order_postdata = "api_key=$p_apikey&secret_key=$p_secret_key&action=services";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $p_link);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $chresult = curl_exec($ch);
                curl_close($ch);
                $decoded_result = json_decode($chresult, true);
                $servicse = $decoded_result["data"];

            } else if (!isset($p_api_id) && !isset($p_pin) && $p_code == "SMMTRY") {
                // KHUSUS SMMTRY
                $order_postdata = "api_key=$p_apikey&action=services";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $p_link);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $chresult = curl_exec($ch);
                curl_close($ch);
                $decoded_result = json_decode($chresult, true);
                $servicse = $decoded_result["data"];
            } else {
                $order_postdata = "key=$p_apikey&action=services";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $p_link);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $order_postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $chresult = curl_exec($ch);
                curl_close($ch);
                $servicse = json_decode($chresult, true);
            }

            // var_dump($servicse);
            // die();

            $count = count($servicse);
            $log_count = 0;
            $log_error = 0;

            if ($post_delete == "on") {
                mysqli_query($db, "DELETE FROM `services` WHERE provider = '$post_code'");
            }

            if ($post_limit == "all") {
                $import_limit = $count;
            } else {
                $import_limit = $post_limit;
            }

            $check_highest_sid = mysqli_query($db, "SELECT * FROM `services` ORDER BY `sid` DESC LIMIT 1");
            $highest_sid = mysqli_fetch_array($check_highest_sid);

            $post_sid = $highest_sid['sid'];
            if ($post_code != "MANUAL") {
                for ($i = 0; $i < $import_limit; $i++) {

                    if (isset($p_pin)) { //DAILYPANEL
                        $id = $servicse[$i]['id'];
                        $name = $servicse[$i]['layanan'];
                        $max = $servicse[$i]['maks'];
                        $min = $servicse[$i]['min'];
                        $category = $servicse[$i]['kategori'];
                        $note = $servicse[$i]['keterangan'];
                        $price = $servicse[$i]['harga'];
                        if ($servicse[$i]['status'] == "Tersedia") {
                            $status = 'Active';
                        } else {
                            $status = 'Not Active';
                        }
                    } else if (isset($p_api_id)) { //IRVANKEDE
                        $id = $servicse[$i]['id'];
                        $name = $servicse[$i]['name'];
                        $max = $servicse[$i]['max'];
                        $min = $servicse[$i]['min'];
                        $category = $servicse[$i]['category'];
                        $note = $servicse[$i]['note'];
                        $price = $servicse[$i]['price'];
                        $status = 'Active';

                      } else if (isset($p_secret_key)) { //Buzzerpanel
                        $id = $servicse[$i]['id'];
                        $name = $servicse[$i]['name'];
                        $max = $servicse[$i]['max'];
                        $min = $servicse[$i]['min'];
                        $category = $servicse[$i]['category'];
                        $note = $servicse[$i]['note'];
                        $price = $servicse[$i]['price'];
                        $status = 'Active';

                    } else if (!isset($p_api_id) && !isset($p_pin) && $p_code == "SMMTRY") {
                        // KHUSUS SMMTRY
                        $id = $servicse[$i]['id'];
                        $name = $servicse[$i]['name'];
                        $max = $servicse[$i]['max'];
                        $min = $servicse[$i]['min'];
                        $category = $servicse[$i]['category'];
                        $note = $servicse[$i]['note'];
                        $price = $servicse[$i]['price'];
                        $status = 'Active';
                    } else {
                        $id = $servicse[$i]['service'];
                        $name = $servicse[$i]['name'];
                        $max = $servicse[$i]['max'];
                        $min = $servicse[$i]['min'];
                        $category = $servicse[$i]['category'];
                        $note = $servicse[$i]['more'];
                        $price = $servicse[$i]['rate'];
                        $status = 'Active';
                    }

                    $post_sid++;

                    if (isset($post_markup)) {
                        $myprice = ((100 + $post_markup) / 100) * $price; // price after markup
                    } else {
                        $myprice = $price;
                    }

                    $resultpop = mysqli_query($db, "SELECT * FROM `service_cat` WHERE `code` = '$category'");

                    if (mysqli_num_rows($resultpop) > 0) {
                    } else {
                        mysqli_query($db, "INSERT INTO service_cat (code, name, status) VALUES ('$category', '$category', 'Active')");
                    }

                    // IF CONVERT RATE EXIST MULTIPLY PRICE WITH CONVERT RATE 
                    // ELSE CONTINUE INSERT PRICE WITHOUT MODIFICATION
                    if (isset($post_convert_rate)) {
                        $converted_price = $myprice * $post_convert_rate;
                        $insert_service = mysqli_query($db, "INSERT INTO services (sid, category, service, note, min, max, price, price_provider, status, pid, provider) VALUES ('$post_sid', '$category', '$name', '$note', '$min', '$max', '$converted_price', '$price', '$status', '$id', '$post_code')");
                    } else {
                        $insert_service = mysqli_query($db, "INSERT INTO services (sid, category, service, note, min, max, price, price_provider, status, pid, provider) VALUES ('$post_sid', '$category', '$name', '$note', '$min', '$max', '$myprice', '$price', '$status', '$id', '$post_code')");
                    }

                    if ($insert_service == TRUE) {
                        $log_count++;
                        $log = $log . "<b>SUCCESS:</b> ADDED.</br><b>Service ID:</b> $post_sid</br> <b>Service Name:</b> $name</br><b>Min:</b> " . number_format($min, 0, ',', '.') . "<br /><b>Max:</b> " . number_format($max, 0, ',', '.') . "</br><b>Provider ID:</b> $id<br /><b>Provider Code:</b> " . $post_code . "<hr>";
                    } else {
                        $log_error++;
                        $log = $log . "<b>Failed:</b> Error system." . mysqli_error($db) . "<hr>";
                    }
                }
                $security = '<?php require("security.php"); ?>';
                $log = $security . "<h3>Total Services :" . $count . "<br>Added Services : " . $log_count . "<br>Errors : " . $log_error . "</h3><hr><hr><br><br><br>" . $log;
                file_put_contents('log/index.php', $log);
                mysqli_query($db, "DELETE FROM services WHERE pid = '0' AND sid = '0' AND min = '0' AND max = '0'");

                $msg_type = "success";
                $msg_content = 'Added ' . $log_count . ' services.<br>Total ' . $log_error . ' errors generated.<br>Full log <a href="./log" target="_blank">Here.</a>';
            } else {
                $msg_type = "error";
                $msg_content = "Please choose a different provider.";
            }
        }
        $title = "Grab Services";
        include("../../lib/header_admin.php");
        $page = 'update';
        $pages = 'sosmeds';
?>
<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../../assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Update Layanan Sosmed | <?php echo $data_settings['web_name']; ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/select2/select2.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php
          include("../../lib/sidebar_admin.php");
          ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php
          include("../../lib/navbar.php");
          ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
                 
            <div class="container-xxl flex-grow-1 container-p-y">
              <?php 
                if ($msg_type == "success") {
                ?>   
              <div class="card-body">
                <div class="alert alert-warning" role="alert"><?php echo $msg_content; ?></div>
              </div>
              <?php
                } else if ($msg_type == "error") {
                ?>
              <div class="card-body">
                <div class="alert alert-danger" role="alert"><?php echo $msg_content; ?></div>
              </div>
              <?php
                }
                ?>

              <!-- Basic Layout -->
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Update Layanan SOSMED</h5>
                      
                    </div>
                    <div class="card-body">
                      <form method="POST" autocomplete="off">
                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">API Service</label>
                              <select class="form-control" id="code" name="code">
                                                <option value="<?php
                                                                $checkdb_provider = mysqli_query($db, "SELECT * FROM provider");
                                                                $datadb_provider = mysqli_fetch_assoc($checkdb_provider);
                                                                echo $datadb_provider['code']; ?>">Selected [<?php echo $datadb_provider['code']; ?>]</option>
                                                <?php
                                                while ($datadb_providerLoop = mysqli_fetch_assoc($checkdb_provider)) {
                                                ?>
                                                    <option value="<?php echo $datadb_providerLoop['code']; ?>"><?php echo $datadb_providerLoop['code']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                            </div>
                        </div>

                        <div class="row g-3">
                          <div class="col-md-12">
                            <label class="form-label">Limit</label>
                            <select name="limit" class="form-control">
                                                <option value="0">Pilih Salah Satu</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="75">75</option>
                                                <option value="100">100</option>
                                                <option value="125">125</option>
                                                <option value="150">150</option>
                                                <option value="175">175</option>
                                                <option value="200">200</option>
                                                <option value="225">225</option>
                                                <option value="250">250</option>
                                                <option value="275">275</option>
                                                <option value="300">300</option>
                                                <option value="325">325</option>
                                                <option value="350">350</option>
                                                <option value="375">375</option>
                                                <option value="400">400</option>
                                                <option value="425">425</option>
                                                <option value="450">450</option>
                                                <option value="475">475</option>
                                                <option value="500">500</option>
                                                <option value="525">525</option>
                                                <option value="550">550</option>
                                                <option value="575">575</option>
                                                <option value="600">600</option>
                                                <option value="625">625</option>
                                                <option value="650">650</option>
                                                <option value="675">675</option>
                                                <option value="700">700</option>
                                                <option value="725">725</option>
                                                <option value="750">750</option>
                                                <option value="775">775</option>
                                                <option value="800">800</option>
                                                <option value="825">825</option>
                                                <option value="850">850</option>
                                                <option value="875">875</option>
                                                <option value="900">900</option>
                                                <option value="925">925</option>
                                                <option value="950">950</option>
                                                <option value="975">975</option>
                                                <option value="1000">1000</option>
                                                <option value="all">All</option>
                                            </select>
                          </div>
                        </div>
                        <hr>
                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Markup Harga Jual</label>
                              <input type="number" min="0" class="form-control" id="markup" name="markup" placeholder="Input 10 untuk 10%" value="" disabled>
                              <small>Gunakan angka</small>
                            </div>
                          </div>

                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="markup_check" name="markup_check" />
                          <label class="form-check-label" for="defaultCheck1"> Aktifkan Markup Harga Jual </label>
                        </div>
                        <hr>

                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Convert Mata Uang</label>
                              <input type="number" min="0" class="form-control" id="convet_rate" name="convet_rate" placeholder="Rp.15000" value="" disabled>
                              <small>Cek Harga Rp. Saat ini</small>
                            </div>
                          </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="convert" name="convert" />
                          <label class="form-check-label" for="defaultCheck1"> Aktifkan Convert </label>
                        </div>
                        <hr>                        
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="delete" />
                          <label class="form-check-label"> Hapus Data Layanan Lama Pada <b>API</b> Ini </label>
                        </div>

                        <div class="pt-4">
                          <button type="submit" name="add" class="btn btn-primary">Update Layanan</button>
                          
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Informasi Wesbite</h5>
                      
                    </div>
                    <div class="card-body">
                      <p>
                       Silahkan Update sesuai kebutuhan kamu seperti:
                       <li>API service yang digunakan</li>
                       <li>Markup harga jual. ( Menggunakan Persentase )</li>
                       <li>Jika menggunakan API luar aktifkan convert harga rupiah sesuai kebutuhan</li>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php include("../../lib/footer.php"); ?>
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
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="../../assets/vendor/libs/hammer/hammer.js"></script>
    <script src="../../assets/vendor/libs/i18n/i18n.js"></script>
    <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="../../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../../assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="../../assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="../../assets/vendor/libs/moment/moment.js"></script>
    <script src="../../assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="../../assets/vendor/libs/select2/select2.js"></script>

    <!-- Main JS -->
    <script src="../../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../../assets/js/form-layouts.js"></script>


    <script>
    $('#convert').change(function() {
        if ($('#convert').is(':checked') == true) {
            $('#convet_rate').prop('disabled', false);
        } else {
            $('#convet_rate').val('').prop('disabled', true);
        }
    });

    $('#markup_check').change(function() {
        if ($('#markup_check').is(':checked') == true) {
            $('#markup').prop('disabled', false);
        } else {
            $('#markup').val('').prop('disabled', true);
        }
    });
</script>
  </body>
</html>
<?php
        include("../../lib/footer.php");
    }
} else {
    header("Location: " . $cfg_baseurl);
}
?>

