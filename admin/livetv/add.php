<?php
session_start();
require("../../lib/mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);
    if (mysqli_num_rows($check_user) == 0) {
        header("Location: ".$cfg_baseurl."/logout/");
        exit();
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$cfg_baseurl."/logout/");
        exit();
    } else if ($data_user['level'] != "Developers") {
        header("Location: ".$cfg_baseurl);
        exit();
    } else {
        if (isset($_POST['add'])) {
            $post_judul = htmlspecialchars($_POST['judul']);
            $post_judul_singkat = htmlspecialchars($_POST['judul_singkat']);
            $post_konten = mysqli_real_escape_string($db, htmlspecialchars_decode($_POST['konten']));
            $post_image = htmlspecialchars($_POST['image']);
            $post_slider = htmlspecialchars($_POST['slider']);
            $post_url = htmlspecialchars($_POST['url']);
            $post_id_url = generateUrl($post_judul);

            if (empty($post_judul)) {
                $msg_type = "error";
                $msg_content = "Please Fill In All Inputs.";
            } else {
                 $insert_news = mysqli_query($db, "INSERT INTO livetv (id, judul, judul_singkat, konten, url, image, waktu, tanggal, id_url, slider) VALUES ('','$post_judul', '$post_judul_singkat', '$post_konten', '$post_url', '$post_image', '$time','$date','$post_id_url', '$post_slider')");
                if ($insert_news) {
                    $msg_type = "success";
                    $msg_content = "Berhasil ditambahkan";
                } else {
                    $msg_type = "error";
                    $msg_content = "Telah Terjadi Error";
                }
            }
        }
        $page = "blog";
        include("../../lib/header_admin.php");
    }
} else {
    header("Location: ".$cfg_baseurl);
    exit();
}

function generateUrl($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', "-", $string);
    return $string;
}
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

    <title>Tambah LiveTv | <?php echo $data_settings['web_name']; ?></title>

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
              
              <!-- Basic Layout -->
              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Tambah Livetv</h5>
                      
                    </div>
                    <div class="card-body">
                      <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Judul TV</label>
                            <input type="text" class="form-control" name="judul" placeholder="Judul Blog">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Deksripsi Singkat</label>
                            <textarea type="text" class="form-control" name="judul_singkat" placeholder="Judul Singkat"></textarea>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Konten</label>
                              <textarea type="text" name="konten" class="form-control" placeholder="Instagram Follower Sedang perbaikan" /></textarea>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Link Gambar</label>
                              <input type="link" name="image" class="form-control" placeholder="https://engeeks.net/" />
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Link TV</label>
                            <input type="url" class="form-control" name="url" placeholder="Judul Blog">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                              <label class="form-label">Slider</label>
                              <select class="form-control" name="slider">
                                <option value="0">Pilih Salah Satu</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak">Tidak Aktif</option>
                              </select>
                            </div>
                        </div>


                        <div class="pt-4">
                          <button type="submit" name="add" class="btn btn-primary">Tambah</button>
                          <a href="../blog" class="btn btn-warning">Kembali</a>
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
                       Update Link Siara Tv online Bisa di klik <a href="https://github.com/shadiew/list_iptvbaru.git" class="btn btn-primary btn-xs" target="_blank"> Disini</a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
  <?php if ($msg_type == "error") { ?>
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: '<?php echo $msg_content; ?>!',
      timer: 3000
    });
  <?php } elseif ($msg_type == "success") { ?>
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '<?php echo $msg_content; ?>!',
      timer: 3000
    });
  <?php } ?>
</script>
  </body>
</html>

