<?php
use yii\helpers\Html;
app\assets\WebsiteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="shortcut icon" href="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/ico/favicon.png">
        <?php $this->head() ?>
        <script type="application/javascript">
            var baseUrl = '<?php echo \yii\helpers\BaseUrl::home(); ?>';
            var _csrf = '<?php echo Yii::$app->request->getCsrfToken() ?>';
        </script>
    </head>
    <body id="top">
        <?php $this->beginBody() ?>
        
        <section class="header-top-bar hidden-xs">
            <div class="container">
                <div class="logo">
                    <a href="index.html"><img src="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/logo-top.png" alt=""></a>
                </div>
                <div class="header-top-bar-content">
                    <span class="text-uppercase">latest post</span>
                    <h2><a href="blog-single-sidebar-right.html" target="blank">7 Ways To Advertise Your Business For Free</a></h2>
                </div>
            </div>
        </section>


        <header class="main-navigation">
            <div class="container">
                <div class="menuzord">
                    <a href="index.html" class="menuzord-brand visible-xs"><img src="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/logo-top.png" alt=""></a>
                    <ul class="menuzord-menu">
                        <li class="active"><a href="index.html">Home</a></li>
                        <li><a href="javascript:void(0)">Collections</a>
                            <div class="megamenu">
                                <div class="megamenu-row">
                                    <div class="col3">
                                        <ul class="list-unstyled">
                                            <li><h2>Smart Devices</h2></li>
                                            <li><a href="#">Smart Phones</a></li>
                                            <li><a href="#">Smart Tablates</a></li>
                                            <li><a href="#">Smart Watchs</a></li>
                                            <li><a href="#">Digital Paper</a></li>
                                        </ul>
                                    </div>
                                    <div class="col3">
                                        <ul class="list-unstyled">
                                            <li><h2>Smart Cameras</h2></li>
                                            <li><a href="#">Compact Cameras</a></li>
                                            <li><a href="#">Action Cameras</a></li>
                                            <li><a href="#">Camcorders</a></li>
                                            <li><a href="#">Video Cameras</a></li>
                                        </ul>
                                    </div>
                                    <div class="col3">
                                        <ul class="list-unstyled">
                                            <li><h2>Music System</h2></li>
                                            <li><a href="#">MP3 Players</a></li>
                                            <li><a href="#">Wireless Speakers</a></li>
                                            <li><a href="#">HD Audio</a></li>
                                            <li><a href="#">Headphones</a></li>
                                        </ul>
                                    </div>
                                    <div class="col3">
                                        <ul class="list-unstyled">
                                            <li><h2>Home Theaters</h2></li>
                                            <li><a href="#">Televisions</a></li>
                                            <li><a href="#">DVD Players</a></li>
                                            <li><a href="#">Projectors</a></li>
                                            <li><a href="#">Sound Bars</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="megamenu-row">
                                    <div class="col6">
                                        <a href="#"><img src="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/megameun-item-1.jpg" alt="" class="img-responsive"></a>
                                    </div>
                                    <div class="col6">
                                        <a href="#"><img src="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/megameun-item-2.jpg" alt="" class="img-responsive"></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><a href="javascript:void(0)">Products</a>
                            <ul class="dropdown">
                                <li><a href="#">Product Boxed</a>
                                    <ul class="dropdown">
                                        <li><a href="product-4-column.html">Product 4 Col</a></li>
                                        <li><a href="product-3-column.html">Product 3 Col</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Product Wide</a>
                                    <ul class="dropdown">
                                        <li><a href="product-4-fullwidth.html">Product 4 Col</a></li>
                                        <li><a href="product-3-fullwidth.html">Product 3 Col</a></li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0)">Product Details</a>
                                    <ul class="dropdown">
                                        <li><a href="product-details-right-sidebar.html">Sidebar Right</a></li>
                                        <li><a href="product-details-left-sidebar.html">Sidebar Left</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Second level</a>
                                    <ul class="dropdown">
                                        <li><a href="#">Third level</a></li>
                                        <li><a href="#">Third level</a></li>
                                        <li><a href="#">Third level</a>
                                            <ul class="dropdown">
                                                <li><a href="#">Fourth level</a></li>
                                                <li><a href="#">Fourth level</a></li>
                                                <li><a href="#">Fourth level</a></li>
                                                <li><a href="#">Fourth level</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Third level</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="cupon.html">Cupons</a></li>
                        <li><a href="javascript:void(0)">Pages</a>
                            <div class="megamenu megamenu-half-width">
                                <div class="megamenu-row">
                                    <div class="col6">
                                        <ul class="list-unstyled">
                                            <li><h2>Standard Pages</h2></li>
                                            <li><a href="about.html">About Page</a></li>
                                            <li><a href="creare.html">Career Page</a></li>
                                            <li><a href="team.html">Team</a></li>
                                            <li><a href="product-review.html">Product Review</a></li>
                                        </ul>
                                    </div>
                                    <div class="col6">
                                        <ul class="list-unstyled">
                                            <li><h2>Specialized Pages</h2></li>
                                            <li><a href="privacy-policy.html">Privacy & Policy</a></li>
                                            <li><a href="terms-condition.html">Terms & Condition</a></li>
                                            <li><a href="typography.html">Typograpy</a></li>
                                            <li><a href="404.html">Error 404</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><a href="javascript:void(0)">Blog</a>
                            <ul class="dropdown">
                                <li><a href="#">Standard</a>
                                    <ul class="dropdown ">
                                        <li><a href="blog-right-sidebar.html">Sidebar Right</a></li>
                                        <li><a href="blog-left-sidebar.html">Sidebar Left</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Blog Details</a>
                                    <ul class="dropdown ">
                                        <li><a href="blog-single-sidebar-right.html">Sidebar Right</a></li>
                                        <li><a href="blog-single-sidebar-left.html">Sidebar Left</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                    <ul class="menuzord-menu right-menu menu-right">
                        <li><a href="javascript:void(0)">Smart Products</a>
                            <div class="megamenu">
                                <div class="megamenu-row">
                                    <div class="col3">
                                        <ul class="list-unstyled">
                                            <li><h2>Smart Devices</h2></li>
                                            <li><a href="#">Smart Phones</a></li>
                                            <li><a href="#">Smart Tablates</a></li>
                                            <li><a href="#">Smart Watchs</a></li>
                                            <li><a href="#">Digital Paper</a></li>
                                        </ul>
                                    </div>
                                    <div class="col3">
                                        <ul class="list-unstyled">
                                            <li><h2>Smart Cameras</h2></li>
                                            <li><a href="#">Compact Cameras</a></li>
                                            <li><a href="#">Action Cameras</a></li>
                                            <li><a href="#">Camcorders</a></li>
                                            <li><a href="#">Video Cameras</a></li>
                                        </ul>
                                    </div>
                                    <div class="col3">
                                        <ul class="list-unstyled">
                                            <li><h2>Music System</h2></li>
                                            <li><a href="#">MP3 Players</a></li>
                                            <li><a href="#">Wireless Speakers</a></li>
                                            <li><a href="#">HD Audio</a></li>
                                            <li><a href="#">Headphones</a></li>
                                        </ul>
                                    </div>
                                    <div class="col3">
                                        <ul class="list-unstyled">
                                            <li><h2>Home Theaters</h2></li>
                                            <li><a href="#">Televisions</a></li>
                                            <li><a href="#">DVD Players</a></li>
                                            <li><a href="#">Projectors</a></li>
                                            <li><a href="#">Sound Bars</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="megamenu-row">
                                    <div class="col6">
                                        <a href="#"><img src="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/megameun-item-1.jpg" alt="" class="img-responsive"></a>
                                    </div>
                                    <div class="col6">
                                        <a href="#"><img src="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/megameun-item-2.jpg" alt="" class="img-responsive"></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div> 
        </header>
        <?php
        echo $content;
        ?>
        <footer class="footer-bar">
            <div class="footer-social-section text-center">
                <div class="container">
                    <ul class="list-inline tt-animate ltr">
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-tumblr"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        <li><a href="#"><i class="fa fa-vimeo"></i></a></li>
                        <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa fa-rss"></i></a></li>
                    </ul>
                </div>
            </div> 
            <div class="footer-primary pd-70">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="footer-widget quick-links">
                                <h3 class="mb-20">Quick Links</h3>
                                <ul class="list-unstyled">
                                    <li><a href="about.html" target="_blank">About Us</a></li>
                                    <li><a href="team.html" target="_blank">Meet the team</a></li>
                                    <li><a href="#">Support center</a></li>
                                    <li><a href="#">Press & media</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="footer-widget adverties-links">
                                <h3 class="mb-20">Adverties</h3>
                                <ul class="list-unstyled">
                                    <li><a href="product-review.html" target="_blank">Add Your Product</a></li>
                                    <li><a href="#">Our Price</a></li>
                                    <li><a href="#">How To Advertise</a></li>
                                    <li><a href="terms-condition.html" target="_blank">Terms & Conditions</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="footer-widget products-links">
                                <h3 class="mb-20">Products</h3>
                                <ul class="list-unstyled">
                                    <li><a href="#">All Reviews</a></li>
                                    <li><a href="cupon.html" target="_blank">Discounts & Deals</a></li>
                                    <li><a href="#">Our Favorite Reviews</a></li>
                                    <li><a href="#">Monthly Best</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="footer-widget more-links">
                                <h3 class="mb-20">More</h3>
                                <ul class="list-unstyled">
                                    <li><a href="privacy-policy.html" target="_blank">Privacy & Policy</a></li>
                                    <li><a href="contact.html" target="_blank">Contact Us</a></li>
                                    <li><a href="#">Career</a><span class="footer-menu-position-link">4 Position</span></li>
                                    <li><a href="#">Subscribe</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> 
                </div>
            </div> 
            <div class="footer-copyright text-center">
                <p>All Rights Reserved 2016 Affiliate - +88 1234 567 890 / 446 New Lack, New York. USA</p>
                <p>Made with <i class="fa fa-heart"></i> in <a href="https://www.codxplore.com/">Developed By Akram Hossain</a></p>
            </div> 
        </footer>
        <div id="loader-wrapper" class="loader-on">
            <div id="loader">
                <div class="battery">
                    <span class="battery_item"></span>
                    <span class="battery_item"></span>
                    <span class="battery_item"></span>
                </div>
                <div class="text">Loading ...</div>
            </div>
        </div>
        ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
