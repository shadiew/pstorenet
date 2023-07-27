<?php
session_start();
require("../lib/mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
    $sess_username = $_SESSION['user']['username'];
    $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
    $data_user = mysqli_fetch_assoc($check_user);
    if (mysqli_num_rows($check_user) == 0) {
        header("Location: ".$cfg_baseurl."/logout/");
    } else if ($data_user['status'] == "Suspended") {
        header("Location: ".$cfg_baseurl."/logout/");
    } else if ($data_user['level'] != "Developers") {
        header("Location: ".$cfg_baseurl);
    } else {
        if (isset($_GET['id_url'])) {
            $post_id_url = $_GET['id_url'];
            $check_target = mysqli_query($db, "SELECT * FROM livetv WHERE id_url = '$post_id_url'");
            $data_target = mysqli_fetch_assoc($check_target);
            if (mysqli_num_rows($check_target) == 0) {
                header("Location: ".$cfg_baseurl."mobile/");
            } else {
                $title = "Service Details";
                include("../lib/header.php");
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
    <title><?php echo $data_settings['web_name']; ?> | Play Livetv</title>
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
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<style>
    #videoPlayer {
      width: 100%;
      height: auto;
    }
  </style>
<body>

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
                    <a class="rounded-circle d-flex align-items-center text-decoration-none" onclick="goBack()">
                        <i class="tio-chevron_left size-24 color-text"></i>
                        <span class="color-text size-14">Back</span>
                    </a>
                </div>
                <div class="title_page">
                    <span class="page_name">
                        <!-- Something here.. -->
                    </span>
                </div>
                <div class="em_side_right">
                
                </div>
            </header>
            <!-- End.main_haeder -->

            <!-- Start emPage__detailsBlog -->
            <section class="emPage__detailsBlog">
                <div class="emheader_cover">
                    <div class="cover">
                        <img src="<?php echo $data_target['image']; ?>" alt="<?php echo $data_target['judul']; ?>">
                        <span class="item_category">Software</span>
                    </div>
                    <div class="title">
                        <h1 class="head_art"><?php echo $data_target['judul']; ?></h1>
                        
                    </div>
                </div>
                <div class="embody__content">
                    <p>
                       <?php echo $data_target['judul_singkat']; ?>
                    </p>
                    
                    <div class="row">
                        <div class="col-12">
                            <video id="videoPlayer" controls></video>
                            <div id="qualityButtons"></div>
                        </div>
                        
                    </div>
                    <p>
                        <?php echo $data_target['konten']; ?>
                    </p>
                    
                </div>
            </section>
            <!-- End. emPage__detailsBlog -->

            


        </div>


        <!-- Start searchMenu__hdr -->
        <section class="searchMenu__hdr">
            <form>
                <div class="form-group">
                    <div class="input_group">
                        <input type="search" class="form-control" placeholder="type something here...">
                        <i class="ri-search-2-line icon_serach"></i>
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn_meunSearch -close __removeMenu">
                <i class="tio-clear"></i>
            </button>
        </section>
        <!-- End. searchMenu__hdr -->

        

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
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      var video = document.getElementById("videoPlayer");
      var qualityButtons = document.getElementById("qualityButtons");

      if (Hls.isSupported()) {
        var hls = new Hls();
        hls.loadSource('<?php echo $data_target['url']; ?>');
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, function() {
          video.play();
          createQualityButtons(hls);
        });
      } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = '<?php echo $data_target['url']; ?>';
        video.addEventListener('canplay', function() {
          video.play();
        });
      }

      function createQualityButtons(hls) {
        if (hls.levels && hls.levels.length > 1) {
          var levels = hls.levels;
          for (var i = 0; i < levels.length; i++) {
            var level = levels[i];
            var button = document.createElement('button');
            button.innerHTML = level.height + 'p';
            button.className = 'quality-button';
            button.addEventListener('click', function(levelIndex) {
              return function() {
                hls.currentLevel = levelIndex;
              };
            }(i));
            qualityButtons.appendChild(button);
          }
        }
      }
    });
  </script>
  <script>
    var previousPage = null;

    function goBack() {
      if (previousPage) {
        window.location.href = previousPage;
      } else {
        alert('Halaman sebelumnya tidak tersedia.');
      }
    }

    // Menyimpan halaman saat ini saat halaman dimuat
    window.onload = function() {
      previousPage = sessionStorage.getItem('previousPage');
      sessionStorage.setItem('previousPage', document.referrer);
    };
  </script>
</body>
</html>
<?php
                
            }
        } else {
            header("Location: ".$cfg_baseurl."/mobile/");
        }
    }
} else {
    header("Location: ".$cfg_baseurl);
}
?>