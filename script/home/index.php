<?php
session_start();
require("../lib/mainconfig.php");
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
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="stylesheet" href="css/owl.transitions.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <!-- Owl Stylesheet -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- Magnific PopUP Stylesheet -->
    <link rel="stylesheet" href="css/isotope.css">
    <!-- Isotope Stylesheet -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- Bootstrap Stylesheet -->
    <link rel="stylesheet" href="css/et-lant-font.css" />
    <!-- Et-Lant Font Stylesheet -->
    <link rel="stylesheet" href="css/3dslider.css" />
    <!-- 3D Slider Stylesheet -->
    <link rel="stylesheet" href="css/animate.css" />
    <!-- Animate Stylesheet -->
    <link rel="stylesheet" href="css/material-design-iconic-font.css" />
    <!-- Iconic Font Stylesheet -->
    <link rel="stylesheet" href="style.php">
    <link rel="stylesheet" href="../css/style.css">
    <!-- Main Style.css Stylesheet -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Resposive.css Stylesheet -->
    <meta name="theme-color" content="#127AFB" />


    <!--EMBED CHAT TAG-->
    <?php echo $data_settings['seo_chat']; ?>
    <!--EMBED CHAT TAG END-->

    
</head>

<body class="apps-craft-solid-color apps-craft-particle">

    <!-- Body Start -->
    <div id="preloader"></div>

    <!-- Main Menu -->
    <header class="apps-craft-main-menu-area header-home" id="apps-craft-menus">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="apps-craft-logo">
                        <a href="#">
							<img src="<?php echo $data_settings['link_logo']; ?>" alt="logo-smm-panel" class="header-logo">
						</a>
                    </div>
                    <!--logo END -->
                </div>

                <div class="col-md-9 col-sm-9 col-xs-6">
                    <div class="apps-craft-menu-and-serach clear-both">
                        <span id="apps-craft-main-menu-icon">
							<div class="la-ball-elastic-dots la-2x item-generate">
							    <div></div>
							    <div></div>
							    <div></div>                    
							</div>
                    	</span>
                        <nav id="apps-craft-menu" class="apps-craft-menu">
                            <ul>
                                <li><a href="#">Home</a></li>
                                <li><a href="./services/">Services</a></li>
                                <?php if($data_settings['terms_on'] == "ON"){ ?> <li><a href="./terms">Terms & Conditions</a></li> <?php } ?>
                                <?php if($data_settings['privacy_on'] == "ON"){ ?> <li><a href="./privacy">Privacy Policy</a></li> <?php } ?>
                            </ul>
                        </nav>
                        <!--menu END -->
                    </div>
                    <!--menu-and-serach END -->
                </div>
            </div>
        </div>
    </header>
    <!--main-menu END -->

    <!-- Welcome Section -->
        <section class="apps-craft-welcome-section apps-craft-welcome-section-v19" id="apps-craft-home" >


        <div class="apps-craft-welcome-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="apps-craft-welcome-tbl">
                            <div class="apps-craft-welcome-tbl-c">
                                <div class="apps-craft-welcome-content text-left">
                                    <h1><?php echo $data_settings['web_slogan']; ?></h1>

                                    <div class="apps-craft-download-store-btn-group">
                                        <a href="<?php echo $cfg_baseurl; ?>/register/" class="apps-craft-btn play-store-btn reg-log-btn"><p class="btn-font">REGISTER</p></a>
                                        <a href="<?php echo $cfg_baseurl; ?>/login/" class="apps-craft-btn play-store-btn reg-log-btn"><p class="btn-font">LOGIN</p></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 hidden-xs">
                        <div class="apps-craft-welcome-tbl bg-image-width">
                            <div class="apps-craft-welcome-tbl-c">
                                <div class="bg-image-div">
                                    <img draggable="false" class="bg-image" src="img/slide.png" alt="Home Gradient Background 1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--welcome-section END -->


    <!-- feature -->
    <section class="apps-craft-feature-section section-padding" id="apps-craft-feature">
        <div class="container">
            <div class="apps-craft-section-heading">
                <h2>FEATURES</h2><br>
            </div>
            <!--section-heading END -->
            <div class="row content-margin-top">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="apps-craft-feature-img text-center" itemscope itemtype="http://schema.org/ImageGallery">
                        <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                            <img src="img/feature-app-screenshort.png" alt="Feature Image">

                            <span class="apps-craft-feature-ico icon-1x" data-bottom="transform:translateX(-103px)" data-top="transform:translateX(-63px);">
								<img src="img/icon/Icon-1.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-2x" data-bottom="transform:translateX(-171px)" data-top="transform:translateX(-131px);">
								<img src="img/icon/ICON-2.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-3x" data-bottom="transform:translateX(-153px)" data-top="transform:translateX(-113px);">
								<img src="img/icon/12.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-4x" data-bottom="transform:translateX(-176px)" data-top="transform:translateX(-136px);">
								<img src="img/icon/ICON-5.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-5x" data-bottom="transform:translateX(-97px)" data-top="transform:translateX(-55px);">
								<img src="img/icon/ICON-7.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-6x" data-bottom="transform:translateY(-130px)" data-top="transform:translateY(-90px);">
								<img src="img/icon/15.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-7x" data-bottom="transform:translateY(-134px)" data-top="transform:translateY(-94px);">
								<img src="img/icon/icon-3.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-8x" data-bottom="transform:translateY(-160px)" data-top="transform:translateY(-120px);">
								<img src="img/icon/ICON--9.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-9x" data-bottom="transform:translateX(-110px)" data-top="transform:translateX(-70px);">
								<img src="img/icon/ICON-11.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-10x" data-bottom="transform:translateY(26px)" data-top="transform:translateY(26px);">
								<img src="img/icon/13.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-11x" data-bottom="transform:translateX(74px)" data-top="transform:translateX(34px);">
								<img src="img/icon/ICon4.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-12x" data-bottom="transform:translateX(160px)" data-top="transform:translateX(120px);">
								<img src="img/icon/ICON-10.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-13x" data-bottom="transform:translateX(150px)" data-top="transform:translateX(110px);">
								<img src="img/icon/ICON-6.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-14x" data-bottom="transform:translateX(80px)" data-top="transform:translateX(40px);">
								<img src="img/icon/15.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-15x" data-bottom="transform:translateX(120px)" data-top="transform:translateX(80px);">
								<img src="img/icon/13.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->

                            <span class="apps-craft-feature-ico icon-16x" data-bottom="transform:translateX(-140px)" data-top="transform:translateX(-100px);">
								<img src="img/icon/17.png" alt="Small Icon">
							</span>
                            <!--feature-ico END -->
                        </figure>
                    </div>
                    <!--feature-img END -->
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="apps-craft-feature-container clear-both">
                        <div class="apps-craft-single-feature">
                            <div class="apps-craft-feature-content wow fadeIn" data-wow-delay="200ms">
                                <i class="zmdi zmdi-facebook zmdi-hc-fw social-box"></i>
                                <h3>Facebook</h3>
                                <p>Facebook likes from India, USA facebook likes, Dubai facebook likes and many other countries.</p>
                            </div>
                            <!--feature-content END -->
                        </div>
                        <!--single-feature END -->
                        <div class="apps-craft-single-feature">
                            <div class="apps-craft-feature-content wow fadeIn" data-wow-delay="400ms">
                                <i class="zmdi zmdi-instagram zmdi-hc-fw social-box"></i>
                                <h3>Instagram</h3>
                                <p>Instagram followers , Premium Instagram followers, Instagram story views, Instagram video Views.</p>
                            </div>
                            <!--feature-content END -->
                        </div>
                        <!--single-feature END -->
                        <div class="apps-craft-single-feature">
                            <div class="apps-craft-feature-content wow fadeIn" data-wow-delay="600ms">
                                <i class="zmdi zmdi-youtube-play zmdi-hc-fw social-box"></i>
                                <h3>Youtube</h3>
                                <p>Youtube subscribers, Youtube likes, Youtube views, Youtube video shares , Youtube trending and much more.</p>
                            </div>
                            <!--feature-content END -->
                        </div>
                        <!--single-feature END -->
                        <div class="apps-craft-single-feature">
                            <div class="apps-craft-feature-content wow fadeIn" data-wow-delay="800ms">
                                <i class="zmdi zmdi-twitter zmdi-hc-fw social-box"></i>
                                <h3>Twitter</h3>
                                <p>Twitter followers, likes, retweets, Twitter followers from India, USA and other countries.</p>
                            </div>
                            <!--feature-content END -->
                        </div>
                        <!--single-feature END -->
                    </div>
                    <!--feature-container END -->
                </div>
            </div>
        </div>
    </section>

     <!-- Subscribe Section -->
     <section class="apps-craft-sub-scribe-section section-padding" id="apps-craft-subscribe">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="apps-craft-subscribe-wraper wow fadeIn check-services-box">
                        <div class="apps-craft-subscribe-content wow fadeIn" data-wow-delay="200ms">
                            <h2 class="box-h2-font">Check out our <span>Fast & Cheap</span> Services.</h2>
                            
                            <div class="apps-craft-download-store-btn-group">
                                <a href="<?php echo $cfg_baseurl; ?>/home/services/" class="abhishek1 apps-craft-btn play-store-btn srvc-btn"><p class="btn-font">SERVICES</p></a>                            
                            </div>
                        </div>
                        <div class="apps-craft-3-bar mc-form top">
                            <img src="img/sub-section-3bar.png" alt="Simple 3 Bars">
                        </div>
                    </div>
                    <!--subscribe-wraper END -->
                </div>
            </div>
        </div>
    </section>
    <!--sub-scribe-section END -->

    <!-- Why Chose Us Section -->
    <section class="apps-craft-why-chose-us-section section-padding" id="apps-craft-chose-us" data-0="background-position:0px 10000px;" data-100000="background-position:0px -50000px;">
        <div class="container">
            <div class="apps-craft-section-heading">
                <h2>Why choose us</h2>
            </div>
            <!--section-heading END -->
            <div class="row content-margin-top">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="apps-craft-why-choose-us-container clear-both">
                        <div class="apps-craft-why-choose-us-container-inner clear-both">
                            <div class="apps-craft-why-chose-single clear-both">
                                <div class="apps-craft-why-chose-ico">
                                    <span class="icon-pricetags apps-craft-round">
										<span class="apps-craft-dash-border"></span>
                                    </span>
                                </div>
                                <!--why-chose-ico END -->

                                <div class="apps-craft-why-chose-txt">
                                    <h3>Exclusive Prices</h3>
                                    <p>Our prices are the cheapest in the market, starting at 0.01$.</p>
                                </div>
                                <!--why-chose-txt END -->
                            </div>
                            <!--why-chose-single END -->
                            <div class="apps-craft-why-chose-single clear-both">
                                <div class="apps-craft-why-chose-ico">
                                    <span class="icon-speedometer apps-craft-round">
										<span class="apps-craft-dash-border"></span>
                                    </span>
                                </div>
                                <!--why-chose-ico END -->

                                <div class="apps-craft-why-chose-txt">
                                    <h3>Delivered Within Minutes</h3>
                                    <p>Our delivery is automated and usually it takes minutes if not seconds to deliver your order.</p>
                                </div>
                                <!--why-chose-txt END -->
                            </div>
                            <!--why-chose-single END -->
                            <div class="apps-craft-why-chose-single clear-both">
                                <div class="apps-craft-why-chose-ico">
                                    <span class="icon-target apps-craft-round"></span>
                                </div>
                                <!--why-chose-ico END -->

                                <div class="apps-craft-why-chose-txt">
                                    <h3>Professional Dashboard</h3>
                                    <p>We have the best and user friendly dashbord in the SMM World! Updated regularly with the best user friendly features.</p>
                                </div>
                                <!--why-chose-txt END -->
                            </div>
                            <!--why-chose-single END -->
                        </div>
                        <!--why-choose-us-container-inner END -->
                    </div>
                    <!--why-choose-us-container END -->
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <figure class="apps-craft-why-chose-img" itemscope itemtype="http://schema.org/ImageGallery">
                        <img src="img/solid-choose-us-screenshort-1.png" class="wow fadeInRight" data-wow-delay=".8s" alt="Why Chose Image Screenshort">
                        <img src="img/solid-choose-us-screenshort-2.png" class="wow fadeInRight" data-wow-delay=".4s" alt="Why Chose Image Screenshort">
                    </figure>
                    <!--why-chose-img END -->
                </div>
            </div>
        </div>
    </section>
    <!--why-chose-us-section END -->


    <!-- Testimonial Section -->
    <section class="apps-craft-testimonial-section section-padding" id="apps-craft-testimonial">
        <div class="container">
            <div class="apps-craft-section-heading white">
                <h2>HAPPY CLIENTS</h2>
            </div>
            <!--section-heading END -->
            <div class="content-margin-top">
                <div class="apps-craft-testimonial-slider-wraper clear-both">
                    <div id="apps-craft-commentor-slider" class="wow fadeIn" data-wow-delay="200ms" itemscope itemtype="http://schema.org/ImageGallery">
                        <div class="apps-craft-testimonial-content clear-both">
                            <div class="apps-craft-testimonial-single clear-both">
                                <div class="apps-craft-commentor-img-continer">
                                    <figure class="apps-craft-commentor-img" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                        <img src="img/commentor-img.png" alt="" class="apps-craft-fadeIn">

                                        <figcaption>
                                            <i class="zmdi zmdi-comment-outline zmdi-hc-fw"></i>
                                        </figcaption>
                                    </figure>
                                    <!--commentor-img END -->
                                </div>
                                <!--commentor-img-continer END -->

                                <div class="apps-craft-rating-and-bio clear-both">
                                    <p><?php echo $data_settings['web_name']; ?> boost my clients' exposure on Youtube and Soundcloud, TSG has made this job a 1000 times easier for me. I cannot thank them enough, I am spending here less than 1% of my earnings.</p>
                                    <div class="apps-craft-rating">
                                        <ul>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star-half zmdi-hc-fw"></i></a></li>
                                        </ul>
                                    </div>
                                    <!--rating END -->

                                    <div class="apps-craft-commentor-bio">
                                        <h3>Sahil Verma - <span>SEO Expert</span></h3>
                                    </div>
                                    <!--commentor-bio END -->
                                </div>
                                <!--ration-and-bio END -->
                            </div>
                            <!--testimonial-single END -->
                        </div>
                        <!--testimonial-content END -->
                        <div class="apps-craft-testimonial-content clear-both">
                            <div class="apps-craft-testimonial-single clear-both">
                                <div class="apps-craft-commentor-img-continer">
                                    <figure class="apps-craft-commentor-img" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                        <img src="img/commentor-img-2.png" alt="">

                                        <figcaption>
                                            <i class="zmdi zmdi-comment-outline zmdi-hc-fw"></i>
                                        </figcaption>
                                    </figure>
                                    <!--commentor-img END -->
                                </div>
                                <!--commentor-img-continer END -->

                                <div class="apps-craft-rating-and-bio clear-both">
                                    <p>I have been using <?php echo $data_settings['web_name']; ?> for a year now, I am very happy with their panel. I never had a problem, and when something wrong occurs they always compensate and more! Best that I have worked with!</p>
                                    <div class="apps-craft-rating">
                                        <ul>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star-half zmdi-hc-fw"></i></a></li>
                                        </ul>
                                    </div>
                                    <!--rating END -->

                                    <div class="apps-craft-commentor-bio">
                                        <h3>Sonali Singh - <span>Backlinks Expert</span></h3>
                                    </div>
                                    <!--commentor-bio END -->
                                </div>
                                <!--ration-and-bio END -->
                            </div>
                            <!--testimonial-single END -->
                        </div>
                        <!--testimonial-content END -->

                        <div class="apps-craft-testimonial-content clear-both">
                            <div class="apps-craft-testimonial-single clear-both">
                                <div class="apps-craft-commentor-img-continer">
                                    <figure class="apps-craft-commentor-img" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                        <img src="img/commentor-img-3.png" alt="">

                                        <figcaption>
                                            <i class="zmdi zmdi-comment-outline zmdi-hc-fw"></i>
                                        </figcaption>
                                    </figure>
                                    <!--commentor-img END -->
                                </div>
                                <!--commentor-img-continer END -->

                                <div class="apps-craft-rating-and-bio clear-both">
                                    <p>I can confirm that <?php echo $data_settings['web_name']; ?> is the best. Ordered huge volumes of YouTube views & likes / Instagram views & likes & comments. Everything was delivered fast and steady with barely any drop. They looked organic and real. I will definitely use these guys as my main source in the future. Thanks again</p>
                                    <div class="apps-craft-rating">
                                        <ul>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star zmdi-hc-fw"></i></a></li>
                                            <li><a href="#"><i class="zmdi zmdi-star-half zmdi-hc-fw"></i></a></li>
                                        </ul>
                                    </div>
                                    <!--rating END -->

                                    <div class="apps-craft-commentor-bio">
                                        <h3>Vaibhav Arora - <span> SMM Reseller</span></h3>
                                    </div>
                                    <!--commentor-bio END -->
                                </div>
                                <!--ration-and-bio END -->
                            </div>
                            <!--testimonial-single END -->
                        </div>
                        <!--testimonial-content END -->
                    </div>
                    <!-- #apps-craft-testimonial-slider END -->
                    <div class="customNavigation">
                        <a class="prev"><i class="zmdi zmdi-long-arrow-left zmdi-hc-fw"></i></a>
                        <a class="next"><i class="zmdi zmdi-long-arrow-right zmdi-hc-fw"></i></a>
                    </div>
                </div>

                <div id="apps-craft-testimonial-thumb" class="text-center wow fadeIn" data-wow-delay="400ms" itemscope itemtype="http://schema.org/ImageGallery">
                    <div class="item">
                        <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                            <img src="img/commentor-img.png" alt="Commentor Thumb">
                        </figure>
                    </div>
                    <div class="item">
                        <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                            <img src="img/commentor-img-2.png" alt="Commentor Thumb">
                        </figure>
                    </div>
                    <div class="item">
                        <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                            <img src="img/commentor-img-3.png" alt="Commentor Thumb">
                        </figure>
                    </div>
                </div>
                <!-- #apps-craft-testimonial-thumb-slider END -->
            </div>
        </div>
    </section>
    <!--testimonial-section END -->

  <!-- Subscribe Section -->
    <section class="apps-craft-sub-scribe-section section-padding" id="apps-craft-subscribe">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="apps-craft-subscribe-wraper wow fadeIn reg-log-box">
                        <div class="apps-craft-subscribe-content wow fadeIn" data-wow-delay="200ms">
                        <img src="img/log.png" alt="<?php echo $data_settings['web_name']; ?>" class="wow fadeInUp reg-log-top-image" data-wow-delay=".2s">
                            <h2 class="box-h2-font"><span>Register</span> or <span>Login</span> to use our Fast & Cheap services.</h2>
                            
                            <div class="apps-craft-download-store-btn-group">
                                <a href="<?php echo $cfg_baseurl; ?>/register/" class="apps-craft-btn play-store-btn reg-log-btn"><p class="btn-font">REGISTER</p></a>
                                <a href="<?php echo $cfg_baseurl; ?>/login/" class="apps-craft-btn play-store-btn reg-log-btn"><p class="btn-font">LOGIN</p></a>
                            </div>
                        </div>
                        <div class="apps-craft-3-bar mc-form top">
                            <img src="img/sub-section-3bar.png" alt="Simple 3 Bars">
                        </div>
                    </div>
                    <!--subscribe-wraper END -->
                </div>
            </div>
        </div>
    </section>
    <!--sub-scribe-section END -->


    <!-- FAQ Section -->
    <section class="apps-craft-faq-section section-padding" id="apps-craft-faq FAQ">
        <div class="container">
            <div class="apps-craft-section-heading">
                <h2>FAQ</h2>
            </div>
            <!--section-heading END -->
            <div class="row content-margin-top">
                <div class="col-md-6 col-sm-6 col-xs-12 faq-box">
                    <div class="apps-craft-accordion wow fadeIn" data-wow-delay="400ms">
                        <div class="panel-group" id="accordion">
                            <?php
                                $faq_query = mysqli_query($db, "SELECT * FROM faq ORDER BY id ASC");
                                $faq_id = 0;
                                while ($data_faq = mysqli_fetch_assoc($faq_query)) { $faq_id++;
                            ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title <?php if($faq_id == 1){ ?>click<?php } ?>">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $faq_id; ?>" class="<?php if($faq_id != 1){ ?>collapsed<?php } ?>">
                                            <?php echo $data_faq['question']; ?>
                                        </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?php echo $faq_id; ?>" class="panel-collapse collapse <?php if($faq_id == 1){ ?>in<?php } ?>">
                                        <div class="panel-body">
                                        <?php echo $data_faq['answer']; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <!--accordion END -->
                    </div>
                </div>
                
                    <!--contact-form-content END -->
                </div>
            </div>
        </div>
    </section>
    <!--faq-section END -->


    <!-- Footer Section -->
    <footer class="apps-craft-footer-section" id="apps-craft-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="apps-craft-footer-logo-and-social">
                        <figure class="apps-craft-footer-logo text-center">
                            <a href="#">
								<img src="<?php echo $data_settings['link_logo']; ?>" alt="<?php echo $data_settings['web_name']; ?>" class="wow fadeInUp header-logo" data-wow-delay=".2s">
							</a>
                        </figure>
                        <!--footer-logo END -->
                        <div class="apps-craft-social-link wow fadeInUp" data-wow-delay=".4s">
                            <ul>
                                <li><a href="<?php echo $data_settings['seo_link_fb']; ?>"><i class="zmdi zmdi-facebook zmdi-hc-fw"></i></a></li>
                                <li><a href="<?php echo $data_settings['seo_link_insta']; ?>"><i class="zmdi zmdi-instagram zmdi-hc-fw"></i></a></li>
                                <li><a href="<?php echo $data_settings['seo_link_tweet']; ?>"><i class="zmdi zmdi-twitter zmdi-hc-fw"></i></a></li>
                            </ul>
                        </div>
                        <!--social-link END -->
                    </div>
                    <!--footer-logo-and-social END -->
                    <div class="apps-craft-footer-menu-and-copyright-txt clear-both">
                        <div class="apps-craft-copyright-txt wow fadeInUp" data-wow-delay=".6s">
                            <p><?php echo $data_settings['web_copyright']; ?></p>
                        </div>
                        <!--copyright-txt END -->
                        <nav class="apps-craft-footer-menu wow fadeInUp" data-wow-delay=".8s">
                            <ul>
                                <li><a href="#">Home</a></li>
                                <li><a href="./services/">Services</a></li>
                                <?php if($data_settings['terms_on'] == "ON"){ ?> <li><a href="./terms">Terms & Conditions</a></li> <?php } ?>
                                <?php if($data_settings['privacy_on'] == "ON"){ ?> <li><a href="./privacy">Privacy Policy</a></li> <?php } ?>
                           </ul>
                        </nav>
                        <!--footer-menu END -->
                    </div>
                    <!--footer-menu-and-copyright-txt END -->
                </div>
            </div>
        </div>
    </footer>
    <!--footer-section END -->

    <!-- js File Start -->

    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <!-- jquery-3.1.1.min.js -->
    <script type="text/javascript" src="js/jquery.ajaxchimp.min.js"></script>
    <!--jquery.ajaxchimp.min.js  -->
    <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
    <!--jquery.easing.1.3.js  -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- bootstrap.min.js -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- owl.carousel.min.js -->
    <script src="js/isotope.pkgd.min.js"></script>
    <!-- isotope.pkgd.min.js -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <!-- jquery.magnific-popup.min.js -->
    <script src="js/skrollr.min.js"></script>
    <!-- skrollr.min.js -->
    <script src="js/utils.js"></script>
    <!-- utils.js -->
    <script src="js/jquery.parallax.js"></script>
    <!-- jquery.parallax.js -->
    <script src="js/wow.js"></script>
    <!-- wow.js -->
    <script src="js/jquery.tubular.1.0.js"></script>
    <!-- jquery.tubular.1.0.js -->
    <script src="js/particles.js"></script>
    <!-- jquery.particles.js -->

    <script src="js/main.js"></script>
    <!-- main.js -->

</body>

</html>