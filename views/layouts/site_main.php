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
                <!--                <div class="header-top-bar-content">
                                    <span class="text-uppercase">latest post</span>
                                    <h2><a href="blog-single-sidebar-right.html" target="blank">7 Ways To Advertise Your Business For Free</a></h2>
                                </div>-->
            </div>
        </section>


        <header class="main-navigation">
            <div class="container">
                <div class="menuzord">
                    <a href="index.html" class="menuzord-brand visible-xs"><img src="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/logo-top.png" alt=""></a>
                    <ul class="menuzord-menu">
                        <li class="active"><a href="index.html">Home</a></li>
                        <li><a href="javascript:void(0)">Categories</a>
                            <div class="megamenu megamenu-half-width">
                                <div class="megamenu-row">
                                    <div class="col12">
                                        <?php
                                        $stores = app\helpers\AppHelper::getStores(8);
                                        $categories = app\helpers\AppHelper::getCategories(8);
                                        ?>
                                        <ul class="list-unstyled">
                                            <li><h2>Top Categories</h2></li>
                                            <?php
                                            foreach ($categories as $str) {
                                                ?>
                                                <li><a href="#"><?= $str->name; ?></a></li>
                                                <?php
                                            }
                                            ?>
                                            <li><a href="#">View All</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><a href="coupon.html">Coupons & Deals</a></li>
                        <li><a href="javascript:void(0)">Stores</a>
                            <div class="megamenu megamenu-half-width">
                                <div class="megamenu-row">
                                    <div class="col12">
                                        <ul class="list-unstyled">
                                            <li><h2>Top Stores</h2></li>
                                            <?php
                                            foreach ($stores as $str) {
                                                ?>
                                                <li><a href="#"><?= $str->name; ?></a></li>
                                                <?php
                                            }
                                            ?>
                                                <li><a href="#">View All</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><a href="contact.html">Contact</a></li>
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
                                    <li><a href="#">Support center</a></li>
                                    <li><a href="#">Subscribe</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="footer-widget adverties-links">
                                <h3 class="mb-20">Adverties</h3>
                                <ul class="list-unstyled">
                                    <li><a href="product-review.html" target="_blank">Add Your Product</a></li>
                                    <li><a href="#">How To Advertise</a></li>
                                    <li><a href="terms-condition.html" target="_blank">Terms & Conditions</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="footer-widget products-links">
                                <h3 class="mb-20">Products</h3>
                                <ul class="list-unstyled">
                                    <li><a href="cupon.html" target="_blank">Discounts & Deals</a></li>
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
                                </ul>
                            </div>
                        </div>
                    </div> 
                </div>
            </div> 
            <div class="footer-copyright text-center">
                <p>All Rights Reserved 2016 Affiliate - +88 1234 567 890 / 446 New Lack, New York. USA</p>
                <p>Made with <i class="fa fa-heart"></i> in <a href="http://www.codxplore.com/">Developed By Akram Hossain</a></p>
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
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
