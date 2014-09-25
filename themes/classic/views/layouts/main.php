<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo $this->pageTitle; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="stylesheet" type="text/css" media="all" href="http://fonts.googleapis.com/css?family=Open+Sans:400,700,400italic,700italic" />
        <link rel="stylesheet" type="text/css" media="all" href="http://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/motioncss.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/motioncss-widgets.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/font-awesome.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/rs-settings.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/switcher.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/magnific-popup.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/style.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/responsive.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/animation.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/colors/color_11.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/ava.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/basic.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/token-input.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/token-input-facebook.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/token-input-mac.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/component.css" />

        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/post_style.css">
            <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/search_style.css">
                <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/documentpage.css">
                    <link rel="icon" type="image/png"  href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/16fv.ico">
                        <!-- javascript -->
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery-1.9.1.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.jcarousel.min.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/g-alert.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.carousello.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.flexslider.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.isotope.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.magnific-popup.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.parallax.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.simpleplaceholder.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.smoothScroll.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.themepunch.revolution.min.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.dropdown-menu.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/navToSelect.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/plugins.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/waypoints.min.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/w-lang.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/w-search.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/w-tabs.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/w-timeline.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/modernizr.custom.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/w-switcher.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.magnific-popup.min.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.tokeninput.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/classie.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/cbpScroller.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.form.min.js"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.paginate.min.js"></script>

                        <!-- Star rating-->
                        <link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/css/ratings.css" />
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.barrating.js"></script>
                        <script type="text/javascript">
                            jQuery(document).ready(function($) {
                                // Get current url
                                // Select an a element that has the matching href and apply a class of 'active'. Also prepend a - to the content of the link
                                var url = window.location.href;
                                $('a[href="' + url + '"]').parent().parent().addClass('active');

                            });
                        </script>
                        <script type="text/javascript">
                            $(function() {
                                $('#example-f').barrating({showSelectedRating: false, readonly: true});
                            });
                        </script>
                        <!-- Pop-up -->
                        <script>
                            $(document).ready(function() {
                                $('.novaCat').on('blur', function(ui, event) {
                                    var valor = $('.novaCat').val();
                                    if (valor) {
                                        $('#novaCategoria').dialog({
                                            modal: true,
                                            resizable: false,
                                            buttons: {
                                                "OK": function() {
                                                    $(this).dialog("close");
                                                }
                                            }
                                        });
                                    }
                                    ;
                                });
                                //formulário popup
                                $('.popup-with-form').magnificPopup({
                                    type: 'inline',
                                    preloader: false,
                                    focus: '#name',
                                    callbacks: {
                                        beforeOpen: function() {
                                            if ($(window).width() < 700) {
                                                this.st.focus = false;
                                            } else {
                                                this.st.focus = '#name';
                                            }
                                        }
                                    }
                                });

                            });
                        </script>
                        <!-- GMap-->
                        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/js/jquery.gmap.min.js"></script>
                        <script type="text/javascript">
                            jQuery(document).ready(function() {
                                jQuery('.w-map-h').gMap({
                                    controls: {
                                        panControl: false,
                                        zoomControl: true,
                                        mapTypeControl: true,
                                        scaleControl: false,
                                        streetViewControl: false,
                                        overviewMapControl: false
                                    },
                                    address: "Đại học Quốc Gia Hà Nội - Xuân Thủy, 144 Xuân Thủy, Dịch Vọng Hậu, Cầu Giấy, Hà Nội, Vietnam",
                                    zoom: 15,
                                    markers: [
                                        {
                                            address: "Đại học Quốc Gia Hà Nội - Xuân Thủy, 144 Xuân Thủy, Dịch Vọng Hậu, Cầu Giấy, Hà Nội, Vietnam",
                                            html: "Trụ sở của chúng tôi",
                                            popup: true
                                        }
                                    ]
                                });
                            });
                        </script>
                        <script type="text/javascript">var switchTo5x = true;</script>

                        <div id="fb-root"></div>
                        <script>(function(d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (d.getElementById(id))
                                    return;
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&appId=1428478800723370&version=v2.0";
                                fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>

                        </head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
                        <body class="l-body home" style="background-color: white">
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0];
                                    if (d.getElementById(id))
                                        return;
                                    js = d.createElement(s);
                                    js.id = id;
                                    js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.0";
                                    fjs.parentNode.insertBefore(js, fjs);
                                }(document, 'script', 'facebook-jssdk'));</script>
                            <style>
                                #fan_page_fb {
                                    position: fixed;
                                    z-index: 1000000;
                                    height: 271px;
                                    width: 300px;
                                    background-color: white;
                                    top: 58%;
                                    left: -300px;
                                    -moz-transition: background 0.1s ease-in;
                                    -o-transition: background 0.1s ease-in;
                                    -webkit-transition: background 0.1s ease-in;
                                    transition: left 1s ease-in;
                                }

                                #button_like_fanpage_fb {
                                    position: absolute;
                                    right: -33%;
                                    top: 19%;
                                    transform: rotate(90deg);
                                    background-color: #429edb;
                                    font-size: 15px;
                                    color: white;
                                    height: 50px;
                                    width: 150px;
                                    font-family: 'Open sans';
                                    text-align: center;
                                    border-radius: 4px 4px 0 0;
                                    -moz-transition: background 0.1s ease-in;
                                    -o-transition: background 0.1s ease-in;
                                    -webkit-transition: background 0.1s ease-in;
                                    transition: left 1s ease-in;
                                    cursor: pointer;
                                }
                            </style>
                            <script>
                                $(document).ready(function() {
                                    $('#button_like_fanpage_fb').click(function(event) {
                                        var pos = $('#fan_page_fb').css('left');
                                        if (pos != '0px') {
                                            pos = $('#fan_page_fb').css('left', '0px');
                                            $('#button_like_fanpage_fb p').html('<div class="g-alert-close"> ✕ </div>');
                                        }
                                        else {
                                            $('#button_like_fanpage_fb p').html('<i class="icon-thumbs-up-alt"></i>&#32;Thích trang<br> của chúng tớ');

                                            pos = $('#fan_page_fb').css('left', '-300px');
                                        }
                                    });
                                });
                            </script>
                            <div id="fan_page_fb">
                                <div id="button_like_fanpage_fb">
                                    <p><i class="icon-thumbs-up-alt"></i>&#32;Thích trang<br> của chúng tớ</p>
                                </div>
                                <div>
                                    <div class="fb-like-box" data-href="https://www.facebook.com/hotrohoctapUET" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
                                </div>
                            </div>

                            <!-- CANVAS -->
                            <div class="l-canvas type_wide col_cont headerpos_fixed headertype_extended">
                                <div class="l-canvas-h">

                                    <!-- HEADER -->
                                    <div class="l-header" style="z-index: 1">
                                        <div class="l-header-h">
                                            <div class="l-subheader at_top" style="background-image: url('<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/demo/header.jpg')">
                                            </div>

                                            <div class="l-subheader at_middle">
                                                <div class="l-subheader-h i-cf">
                                                    <!-- LOGO -->
                                                    <div class="w-logo">
                                                        <div class="w-logo-h">
                                                            <a class="w-logo-link" href="<?php echo Yii::app()->createUrl("listOfSubject") ?>" class="w-nav-anchor level_1">
                                                                <img class="w-logo-img" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/img/logo.jpg" alt="BlueBee" />
                                                                <span class="w-logo-title">
                                                                    <span class="w-logo-title-h">BlueBee</span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>


                                                    <div class="w-search submit_inside">
                                                        <div class="w-search-h">
                                                            <a class="w-search-show" href="javascript:void(0)" style="margin: auto;">
                                                                <i class="icon-search" style="line-height: inherit"></i>
                                                            </a>

                                                            <form class="w-search-form show_hidden" action="<?php echo Yii::app()->createUrl('search') ?>" method="get"  />
                                                            <div class="w-search-input">
                                                                <input type="text" value="" placeholder="Bạn muốn tìm gì ?" id="input_search" name="query"/>
                                                            </div>
                                                            <div class="w-search-submit" >
                                                                <input type="submit" value="Tìm kiếm"/>

                                                            </div>
                                                            <a class="w-search-close" href="javascript:void(0)" title="Đóng tìm kiếm"> &#10005; </a>
                                                            </form>
                                                        </div>
                                                    </div>



                                                    <!-- NAV -->
                                                    <nav class="w-nav ">
                                                        <div class="w-nav-h align_center">
                                                            <div class="w-nav-select">
                                                                <select class="w-nav-select-h">
                                                                </select>
                                                            </div>
                                                            <div class="w-nav-list layout_hor width_auto float_right level_1">
                                                                <div class="w-nav-list-h">
                                                                    <div class="w-nav-item level_1">
                                                                        <div class="w-nav-item-h">
                                                                            <a href="<?php echo Yii::app()->createAbsoluteUrl("listOfSubject") ?>" class="w-nav-anchor level_1 menu-header">
                                                                                <span class="w-nav-icon"><i class="icon-star"></i></span>
                                                                                <span class="w-nav-title">Chương trình đào tạo</span>
                                                                                <span class="w-nav-hint"></span>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-nav-item level_1">
                                                                        <div class="w-nav-item-h">
                                                                            <a href="<?php echo Yii::app()->createAbsoluteUrl("document") ?>" class="w-nav-anchor level_1 menu-header">
                                                                                <span class="w-nav-icon"><i class="icon-star"></i></span>
                                                                                <span class="w-nav-title">Đề thi - Tài liệu</span>
                                                                                <span class="w-nav-hint"></span>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-nav-item level_1">
                                                                        <div class="w-nav-item-h">
                                                                            <a href="<?php echo Yii::app()->createAbsoluteUrl("lab") ?>" class="w-nav-anchor level_1 menu-header">
                                                                                <span class="w-nav-icon"><i class="icon-star"></i></span>
                                                                                <span class="w-nav-title">Lab - Tài liệu nén</span>
                                                                                <span class="w-nav-hint"></span>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="w-nav-item level_1">
                                                                        <div class="w-nav-item-h">
                                                                            <a href="<?php echo Yii::app()->createAbsoluteUrl("share/teacherListPage") ?>" class="w-nav-anchor level_1 menu-header">
                                                                                <span class="w-nav-icon"><i class="icon-star"></i></span>
                                                                                <span class="w-nav-title">Giáo viên</span>
                                                                                <span class="w-nav-hint"></span>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                    if (Yii::app()->session["user_id"] == "") {
                                                                        echo '


        <a style="margin:10px" id="login" href="' . $this->createUrl('welcomePage/fb_login') . '"><i class="icon-facebook"></i> Đăng nhập</a>
    ';
                                                                    } else {
                                                                        echo '
                                                                <div class="w-nav-item level_1">
    <div class="w-nav-item-h">
        <a href="' . Yii::app()->createUrl("user?token=" . Yii::app()->session['token']) . '" class="w-nav-anchor level_ava">
            <img style="border: 5px solid white;"class="ava" src="' .
                                                                        Yii::app()->session['user_avatar']
                                                                        . '"/>
        </a>


        <div class="w-nav-list place_down show_onhover level_2">
            <div class="w-nav-list-h">
                <div class="w-nav-item level_2">
                    <div class="w-nav-item-h">
                        <a href="' . Yii::app()->createUrl('welcomePage/logout') . '" class="w-nav-anchor level_2">Đăng xuất</a>
                    </div>
                </div>
                <!--                                                            <div class="w-nav-item level_2">
                                                                                <div class="w-nav-item-h">
                                                                                    <a href="home-parallax.html" class="w-nav-anchor level_2">Cập nhật thông tin</a>
                                                                                </div>
                                                                            </div>-->

            </div>
        </div>

    </div>
</div>'
                                                                        ;
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                    </nav>

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- MAIN -->
                                    <div style="padding-top: 126px; z-index: 0; position: relative;">
                                        <?php echo $content; ?>
                                    </div>
                                    <!-- /MAIN -->
                                </div>
                            </div>
                            <!-- /CANVAS -->

                            <!-- FOOTER -->
                            <div class="l-footer type_normal" style="margin-top: 50px;">
                                <div class="l-footer-h">
                                    <!-- subfooter: bottom -->
                                    <div class="l-subfooter at_bottom">
                                        <div class="l-subfooter-h i-cf">
                                            <div class="w-copyright">© 2014 All rights reserved. <a href='bluebee-uet.com'>BlueBee Team - K57CA - UET</a></div>

                                            <!-- NAV -->
                                            <nav class="w-nav">
                                                <div class="w-nav-h">
                                                    <div class="w-nav-list layout_hor width_auto float_right level_1">
                                                        <div class="w-nav-list-h">
                                                            <div class="w-nav-item level_1">
                                                                <div class="w-nav-item-h">
                                                                    <a href="<?php echo Yii::app()->createUrl("term") ?>" class="w-nav-anchor level_1">Điều khoản và dịch vụ</a>
                                                                </div>
                                                            </div>
                                                            <div class="w-nav-item level_1">
                                                                <div class="w-nav-item-h">
                                                                    <a href="<?php echo Yii::app()->createUrl("faq") ?>" class="w-nav-anchor level_1">FAQ</a>
                                                                </div>
                                                            </div>
                                                            <div class="w-nav-item level_1">
                                                                <div class="w-nav-item-h">
                                                                    <a href="http://blog.bluebee-uet.com" class="w-nav-anchor level_1">Blog</a>
                                                                </div>
                                                            </div>

                                                            <div class="w-nav-item level_1">
                                                                <div class="w-nav-item-h">
                                                                    <a href="<?php echo Yii::app()->createUrl("aboutUs") ?>" class="w-nav-anchor level_1">Về chúng tôi</a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </nav>
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <!-- /FOOTER -->

                            <a class="w-toplink" href="#"><i class="icon-angle-up" style="line-height: inherit"></i></a>


                        </body>
                        </html>

