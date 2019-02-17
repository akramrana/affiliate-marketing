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
        <meta name="robots" content="index,follow"/>
        <meta name="googlebot" content="index,follow,snippet"/>
        <meta name="keywords" content="Offersndeal.codxplore.com! Free promotions,coupons and voucher!" />
        <meta name="391ecd4fc836d7f" content="98fd186c82a66386e4f8bef18c881a90" />
        <meta name="description" content="Offernsdeal.codxplore.com is a free website providing its user to free promotions,coupons and voucher to save money while shopping from online store or portals."/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?>-Offersndeal Free promotions,coupons and voucher!</title>
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
                    <h2 class="section-title section-title-width">Popular Deals</h1>
                </div> 
                <div class="row">
                    <?php
                    $creativeAds = app\helpers\AppHelper::getCreativeAds(12);
                    $creativeDeals = app\helpers\AppHelper::getDealsBenner(12);
                    ?>
                    <div class="owl-carousel owl-theme">
                        <?php
                        foreach ($creativeAds as $ca) {
                            ?>
                            <div class="item">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-30">
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
            </div>
            <div class="container">
                <div class="page-title text-center">
                    <h2 class="section-title">Explore More</h2>
                    <p class="section-sub">
                        Browse Our Various Deals.Buy Now And Save, 
                        Discover Brands At Great Prices.Shop Your Style.
                    </p>
                </div>
                <div class="job-menu">
                    <div class="row">
                        <?php
                        foreach ($creativeDeals as $db) {
                            ?>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mt-30">
                                <?php
                                echo $db->content;
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div> 
            </div>
        </section>
        <footer class="footer-bar">
            <div class="footer-social-section text-center">
                <div class="container">
                    <ul class="list-inline tt-animate ltr">
                        <li><a target="_new" href="https://www.facebook.com/Offersndeal-233757060846568/"><i class="fa fa-facebook"></i></a></li>
                        <li><a target="_new" href="https://twitter.com/Offersanddeals5"><i class="fa fa-twitter"></i></a></li>
                        <li><a target="_new" href="https://www.tumblr.com/blog/offerndeal"><i class="fa fa-tumblr"></i></a></li>
                        <li><a target="_new" href="https://www.reddit.com/user/akramoffersndeal"><i class="fa fa-reddit"></i></a></li>
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
                                <h3 class="mb-20">Advertise</h3>
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
                <p>All Rights Reserved <?=date('Y');?> OffernDeal - +88 01531 184 270 / 1207 Dhaka. BD</p>
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
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header header-top-bar">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title color-white">Coupon Code</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">
                            <strong  id="modal-coupon-txt"></strong>
                        </p>
                    </div>
                </div>

            </div>
        </div>
        <?php
        $js = '$(\'.owl-carousel\').owlCarousel({
                loop:true,
                margin:5,
                nav:true,
                autoplay:true,
                items:5,
            })';
        $this->registerJs($js, \yii\web\View::POS_END);
        ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
