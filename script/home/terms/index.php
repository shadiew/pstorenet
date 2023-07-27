<?php
session_start();
require("../../lib/mainconfig.php");
$check_settings = mysqli_query($db, "SELECT * FROM settings WHERE id = '1'");
$data_settings = mysqli_fetch_assoc($check_settings);
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo $data_settings['web_description']; ?>">
    <meta name="keywords" content="<?php echo $data_settings['seo_keywords']; ?>">
    <title><?php echo $data_settings['web_title']; ?></title>
    <link rel="icon" href="<?php echo $data_settings['link_fav']; ?>" type="image/x-icon">

    <!--HEADER TAG-->
    <?php echo $data_settings['seo_meta']; ?>
    <!--HEADER TAG END-->

    <!--GTAG TAG-->
    <?php echo $data_settings['seo_analytics']; ?>
    <!--GTAG TAG END-->

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="../css/owl.theme.css">
    <link rel="stylesheet" href="../css/owl.transitions.css">
    <link rel="stylesheet" href="../css/owl.carousel.css">
    <!-- Owl Stylesheet -->
    <link rel="stylesheet" href="../css/magnific-popup.css">
    <!-- Magnific PopUP Stylesheet -->
    <link rel="stylesheet" href="../css/isotope.css">
    <!-- Isotope Stylesheet -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <!-- Bootstrap Stylesheet -->
    <link rel="stylesheet" href="../css/et-lant-font.css" />
    <!-- Et-Lant Font Stylesheet -->
    <link rel="stylesheet" href="../css/3dslider.css" />
    <!-- 3D Slider Stylesheet -->
    <link rel="stylesheet" href="../css/animate.css" />
    <!-- Animate Stylesheet -->
    <link rel="stylesheet" href="../css/material-design-iconic-font.css" />
    <!-- Iconic Font Stylesheet -->
    <link rel="stylesheet" href="../style.php">
    <link rel="stylesheet" href="../../css/style.css">
    <!-- Main Style.css Stylesheet -->
    <link rel="stylesheet" href="../css/responsive.css">
    <!-- Resposive.css Stylesheet -->
    <meta name="theme-color" content="#127AFB" />


    <!--EMBED CHAT TAG-->
    <?php echo $data_settings['seo_chat']; ?>
    <!--EMBED CHAT TAG END-->

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    
</head>

<body class="apps-craft-solid-color apps-craft-particle">

    <!-- Body Start -->
    <div id="preloader"></div>

    <!-- Main Menu -->
    <header class="apps-craft-main-menu-area" id="apps-craft-menus header-home">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="apps-craft-logo">
                        <a href="<?php echo $cfg_baseurl; ?>/home">
							<img src="<?php echo $data_settings['link_logo']; ?>" alt="logo-smm-panel" class="header-logo">
						</a>
                    </div>
                    <!-- logo END -->
                </div>

                <div class="col-md-9 col-sm-9 col-xs-6">
                    <div class="apps-craft-menu-and-serach clear-both">
                        <span id="apps-craft-main-menu-icon">
							<div class="la-ball-elastic-dots la-2x item-generate nav-white">
							    <div></div>
							    <div></div>
							    <div></div>                    
							</div>
                    	</span>
                        <nav id="apps-craft-menu" class="apps-craft-menu apps-craft-footer-menu">
                            <ul><li><a href="../">Home</a></li>
                                <li><a href="../services">Services</a></li>
                                <?php if($data_settings['terms_on'] == "ON"){ ?> <li><a href="#">Terms & Conditions</a></li> <?php } ?>
                                <?php if($data_settings['privacy_on'] == "ON"){ ?> <li><a href="../privacy">Privacy Policy</a></li> <?php } ?>
                            </ul>
                        </nav>
                        <!-- menu END -->
                    </div>
                    <!-- menu-and-serach END -->
                </div>
            </div>
        </div>
    </header>
    <!-- main-menu END -->

    <!-- Welcome Section -->
        <section class="apps-craft-welcome-section apps-craft-welcome-section-v19 height-50" id="apps-craft-home" >


        <div class="apps-craft-welcome-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 width-100p">
                        <div class="apps-craft-welcome-tbl height-500">
                            <div class="apps-craft-welcome-tbl-c srvc-title-down">
                                <div class="apps-craft-welcome-content apps-craft-welcome-content-abhi text-center width-100p">
                                    <h1><?php echo $data_settings['web_slogan']; ?></h1>
                                    <h1>Terms & Conditions</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- welcome-section END -->

    <!-- Fun Factory -->
    <div class="apps-craft-fun-factory-section" id="fun-factory">
        <div class="container panel panel-default table-box-services pages-box">                                                                                   
            <?php echo $data_settings['terms_ins']; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="apps-craft-footer-section" id="apps-craft-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="apps-craft-footer-logo-and-social">
                        <figure class="apps-craft-footer-logo text-center">
                            <a href="<?php echo $cfg_baseurl; ?>/home">
								<img src="<?php echo $data_settings['link_logo']; ?>" alt="<?php echo $data_settings['web_name']; ?>" class="wow fadeInUp header-logo" data-wow-delay=".2s">
							</a>
                        </figure>
                        <!-- footer-logo END -->
                        <div class="apps-craft-social-link wow fadeInUp" data-wow-delay=".4s">
                            <ul>
                                <li><a href="<?php echo $data_settings['seo_link_fb']; ?>"><i class="zmdi zmdi-facebook zmdi-hc-fw"></i></a></li>
                                <li><a href="<?php echo $data_settings['seo_link_insta']; ?>"><i class="zmdi zmdi-instagram zmdi-hc-fw"></i></a></li>
                                <li><a href="<?php echo $data_settings['seo_link_tweet']; ?>"><i class="zmdi zmdi-twitter zmdi-hc-fw"></i></a></li>
                            </ul>
                        </div>
                        <!-- social-link END -->
                    </div>
                    <!-- footer-logo-and-social END -->
                    <div class="apps-craft-footer-menu-and-copyright-txt clear-both">
                        <div class="apps-craft-copyright-txt wow fadeInUp" data-wow-delay=".6s">
                            <p><?php echo $data_settings['web_copyright']; ?></p>
                        </div>
                        <!-- copyright-txt END -->
                        <nav class="apps-craft-footer-menu wow fadeInUp" data-wow-delay=".8s">
                            <ul><li><a href="../">Home</a></li>
                                <li><a href="../services">Services</a></li>
                                <?php if($data_settings['terms_on'] == "ON"){ ?> <li><a href="#">Terms & Conditions</a></li> <?php } ?>
                                <?php if($data_settings['privacy_on'] == "ON"){ ?> <li><a href="../privacy">Privacy Policy</a></li> <?php } ?>
                            </ul>
                        </nav>
                        <!-- footer-menu END -->
                    </div>
                    <!-- footer-menu-and-copyright-txt END -->
                </div>
            </div>
        </div>
    </footer>
    <!-- footer-section END -->

    <!-- js File Start -->

    <script type="text/javascript" src="../js/jquery-3.1.1.min.js"></script>
    <!-- jquery-3.1.1.min.js -->
    <script type="text/javascript" src="../js/jquery.ajaxchimp.min.js"></script>
    <!--jquery.ajaxchimp.min.js  -->
    <script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
    <!--jquery.easing.1.3.js  -->
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!-- bootstrap.min.js -->
    <script src="../js/owl.carousel.min.js"></script>
    <!-- owl.carousel.min.js -->
    <script src="../js/isotope.pkgd.min.js"></script>
    <!-- isotope.pkgd.min.js -->
    <script src="../js/jquery.magnific-popup.min.js"></script>
    <!-- jquery.magnific-popup.min.js -->
    <script src="../js/skrollr.min.js"></script>
    <!-- skrollr.min.js -->
    <script src="../js/utils.js"></script>
    <!-- utils.js -->
    <script src="../js/jquery.parallax.js"></script>
    <!-- jquery.parallax.js -->
    <script src="../js/wow.js"></script>
    <!-- wow.js -->
    <script src="../js/jquery.tubular.1.0.js"></script>
    <!-- jquery.tubular.1.0.js -->
    <script src="../js/particles.js"></script>
    <!-- jquery.particles.js -->

    <script src="../js/main.js"></script>
    <!-- main.js -->

</body>

</html>