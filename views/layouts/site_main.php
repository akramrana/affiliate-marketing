<?php

use yii\helpers\Html;

$controller = $this->context->action->controller->id;
$method = $this->context->action->id;
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
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132803749-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-132803749-1');
        </script>
    </head>
    <body id="top">
        <?php $this->beginBody() ?>

        <section class="header-top-bar hidden-xs">
            <div class="container">
                <div class="logo">
                    <a href="<?= yii\helpers\Url::to(['site/index']); ?>"><img src="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/logo-top.png" alt=""></a>
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
                    <a href="<?= yii\helpers\Url::to(['site/index']); ?>" class="menuzord-brand visible-xs">
                        <img src="<?php echo \yii\helpers\BaseUrl::home(); ?>theme/assets/img/logo-top.png" alt="logo">
                    </a>
                    <ul class="menuzord-menu">
                        <li <?php echo ($method == 'index') ? 'class="active"' : ""; ?>><a href="<?= yii\helpers\Url::to(['site/index']); ?>">Home</a></li>
                        <li <?php echo ($method == 'categories') ? 'class="active"' : ""; ?>><a href="javascript:void(0)">Categories</a>
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
                                                <li><a href="<?= yii\helpers\Url::to(['site/coupons-deals', 'id' => $str->category_id, 'type' => 'c', 'name' => clean($str->name)]); ?>"><?= $str->name; ?></a></li>
                                                <?php
                                            }
                                            ?>
                                            <li><a href="<?= yii\helpers\Url::to(['site/categories']); ?>">View All</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li <?php echo ($method == 'coupons-deals') ? 'class="active"' : ""; ?>><a href="<?= yii\helpers\Url::to(['site/coupons-deals']); ?>">Coupons & Deals</a></li>
                        <li <?php echo ($method == 'stores') ? 'class="active"' : ""; ?>><a href="javascript:void(0)">Stores</a>
                            <div class="megamenu megamenu-half-width">
                                <div class="megamenu-row">
                                    <div class="col12">
                                        <ul class="list-unstyled">
                                            <li><h2>Top Stores</h2></li>
                                            <?php
                                            foreach ($stores as $str) {
                                                ?>
                                                <li><a href="<?= yii\helpers\Url::to(['site/coupons-deals', 'id' => $str->store_id, 'type' => 's', 'name' => clean($str->name)]); ?>"><?= $str->name; ?></a></li>
                                                <?php
                                            }
                                            ?>
                                            <li><a href="<?= yii\helpers\Url::to(['site/stores']); ?>">View All</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li <?php echo ($method == 'contact') ? 'class="active"' : ""; ?>><a href="<?= yii\helpers\Url::to(['site/contact']); ?>">Contact</a></li>
                    </ul>
                </div>
            </div> 
        </header>
        <?php
        echo $content;
        ?>
        <section class="pdt-100 pdb-70 product-reviews solitude-bg">
            <div class="container">
                <div class="next-section-link white-bg text-center">
                    <span data-target=".product-reviews" class="scroll-to-target"><i class="fa fa-arrow-down"></i></span>
                </div>
                <div class="page-title text-center mb-40">
                    <h2>Popular Shops</h2>
                </div> 
                <div class="row">
                    <?php
                    $creativeAds = app\helpers\AppHelper::getCreativeAds(12);
                    foreach ($creativeAds as $ca) {
                        ?>
                        <div class="col-lg-4 col-sm-6 mb-30">
                            <div class="product-wrapper text-center" itemscope="" itemtype="http://schema.org/Product">
                                <?php
                                echo $ca->content;
                                ?>
                            </div> 
                        </div>  
                        <?php
                    }
                    ?>
                </div> 
            </div>
        </section>
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
                                    <li><a href="<?= yii\helpers\Url::to(['site/cms', 'id' => 1, 'title' => 'about-us']); ?>">About Us</a></li>
                                    <li><a href="<?= yii\helpers\Url::to(['site/cms', 'id' => 2, 'title' => 'terms-conditions']); ?>">Terms & Conditions</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="footer-widget adverties-links">
                                <h3 class="mb-20">Adverties</h3>
                                <ul class="list-unstyled">
                                    <li><a href="<?= yii\helpers\Url::to(['site/cms', 'id' => 3, 'title' => 'add-your-product']); ?>">Add Your Product</a></li>
                                    <li><a href="<?= yii\helpers\Url::to(['site/cms', 'id' => 4, 'title' => 'how-to-advertise']); ?>">How To Advertise</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="footer-widget products-links">
                                <h3 class="mb-20">Products</h3>
                                <ul class="list-unstyled">
                                    <li><a href="<?= yii\helpers\Url::to(['site/coupons-deals']); ?>">Coupon & Deals</a></li>
                                    <li><a href="<?= yii\helpers\Url::to(['site/coupons-deals']); ?>">Monthly Best</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="footer-widget more-links">
                                <h3 class="mb-20">More</h3>
                                <ul class="list-unstyled">
                                    <li><a href="<?= yii\helpers\Url::to(['site/cms', 'id' => 5, 'title' => 'privacy-policy']); ?>">Privacy & Policy</a></li>
                                    <li><a href="<?= yii\helpers\Url::to(['site/contact']); ?>">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> 
                </div>
            </div> 
            <div class="footer-copyright text-center">
                <p>All Rights Reserved 2016 Affiliate - +88 01531 184 270 / 1207 Dhaka. BD</p>
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
