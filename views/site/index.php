<?php
/* @var $this yii\web\View */

$this->title = 'Home::Offer and deal';
?>
<div class="container">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php
            $i = 0;
            foreach ($banners as $banner) {
                ?>
                <li data-target="#myCarousel" data-slide-to="<?= $i ?>" class="<?= ($i == 0) ? 'active' : '' ?>"></li>
                <?php
                $i++;
            }
            ?>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <?php
            $i = 0;
            foreach ($banners as $banner) {
                $url = \yii\helpers\BaseUrl::home() . 'uploads/' . $banner->image;
                if ($banner->type == 'H') {
                    $url = $banner->html_code;
                }
                ?>
                <div class="item <?= ($i == 0) ? 'active' : '' ?>">
                    <?php
                    if ($banner->type == "H") {
                        ?>
                        <a href="<?= $banner->url; ?>" target="_new">
                            <img style="width: 970px;" src="<?php echo $url; ?>" alt="banner">
                        </a>
                        <?php
                    } else if ($banner->type == 'L') {
                        ?>
                        <a href="<?= $banner->url; ?>" target="_new">
                            <img style="width: 970px;" src="<?php echo $url; ?>" alt="banner">
                        </a>
                        <?php
                    } else {
                        ?>
                        <img src="<?php echo $url; ?>" alt="banner">
                        <?php
                    }
                    ?>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>
        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>


<section class="pdb-40 pdt-40 solitude-bg">
    <div class="container">
        <span class="clearfix"></span>
        <div class="page-title text-center mb-40">
            <h2 class="section-title section-title-width">Coupons & Promotion</h2>
        </div>
        <div class="row">
            <?php
            foreach ($top8 as $deal) {
                $store = \app\models\Stores::find()->where(['api_store_id' => $deal->program_id])->one();
                if ($deal->integration_code != "") {
                    $arr = explode('>', $deal->integration_code);
                    $arr1 = explode('"', $arr[0]);
                    $destination_url = $arr1[1];
                } else {
                    $destination_url = $deal->destination_url;
                }
                if ($deal->coupon_code != "") {
                    $coupon = "View Coupon";
                    $str = 'click & open site to redeem offer';
                } else {
                    $coupon = "Redeem";
                    $str = 'click & open site to redeem offer';
                }
                $dealImg = isset($store->store_logo) ? $store->store_logo : "";
                if ($deal->image_url != null) {
                    $dealImg = $deal->image_url;
                }
                ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-30">
                    <div class="product-wrapper" itemscope itemtype="http://schema.org/Article">
                        <meta itemprop="author" content="offerndeal.codxplore.com"/>
                        <meta itemprop="headline" content="<?= substr($deal->title, 0, 110); ?>"/>
                        <meta itemprop="image" content="<?= $dealImg; ?>"/>
                        <meta itemprop="datePublished" content="<?= date('Y-m-d', strtotime($deal->start_date)); ?>"/>
                        <meta itemprop="dateModified" content="<?= date('Y-m-d', strtotime($deal->last_change_date)); ?>"/>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="product-image text-center" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                                    <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">
                                        <span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject" >
                                            <img itemprop="url" src="<?= $dealImg; ?>" class="img-responsive img-thumbnail" alt="logo"/>
                                        </span>
                                    </a>
                                    <small>
                                        <meta itemprop="name" content="<?= $store->name; ?>"/>
                                        <a class="color-red" target="_new" href="<?php echo $destination_url; ?>">
                                            <?= $store->name; ?>
                                        </a>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 pad-left-10">
                                <div class="product-details">
                                    <div class="product-title">
                                        <h5><a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>"><?= $deal->title; ?></a></h5>
                                    </div>
                                    <div class="product-description">
                                        <p itemprop="description">
                                            <?= $deal->content; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="cupon-num">
                                    <small>End Date: <?= date('F j Y', strtotime($deal->end_date)); ?></small>
                                    <span class="clearfix"></span>
                                    <a id="d<?= $deal->deal_id; ?>" onclick="site.openRemoteUrl('<?php echo $destination_url; ?>', '<?= $deal->coupon_code; ?>',<?= $deal->deal_id; ?>)" href="javascript:;" class="btn" data-clipboard-text="<?= $deal->coupon_code; ?>">
                                        <?= $coupon; ?>
                                    </a>
                                    <a id="link<?= $deal->deal_id; ?>" href="<?php echo $destination_url; ?>" target="_blank"></a>
                                </div>

                                <div class="cupon-info-text">
                                    <span><?= $str; ?></span>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="product-view-btn">
                                    <a itemprop="mainEntityOfPage" href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">view details</a>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div> 
                <?php
            }
            ?>
            <div class="clearfix visible-lg visible-md"></div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-30 clearfix">
                <div class="widget-subscribe product-subscribe">
                    <div class="subscribe-header text-center mb-50">
                        <span>subscribe us</span>
                        <h2>Get Amazing Coupons Code & Offers Everyday</h2>
                    </div>
                    <div class="subscribe-input">
                        <?php
                        $model = new app\models\NewsletterSubscriber();
                        $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'subscription-form',
                                    'enableClientScript' => false,
                                    'action' => \yii\helpers\Url::to(['site/subscribe']),
                                    'options' => [
                                        'method' => 'post',
                                        'class' => 'subscribe-form'
                                    ]
                        ]);
                        ?>
                        <div class="clearfix">
                            <div class="input-field mb-20">
                                <label class="sr-only" for="email">Email</label>
                                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'YOUR EMAIL'), 'class' => 'form-control'])->label(false); ?>
                            </div>
                            <?= \yii\helpers\Html::submitButton(Yii::t('app', 'Yes! let’s so this'), ['class' => 'btn btn-block btn-primary submit-btn']) ?>
                        </div>
                        <?php yii\bootstrap\ActiveForm::end(); ?>
                        <p id="response" class="subscription-success"></p>
                    </div>
                </div> 
            </div> 
            <?php
            foreach ($top2 as $deal) {
                $store = \app\models\Stores::find()->where(['api_store_id' => $deal->program_id])->one();
                if ($deal->integration_code != "") {
                    $arr = explode('>', $deal->integration_code);
                    $arr1 = explode('"', $arr[0]);
                    $destination_url = $arr1[1];
                } else {
                    $destination_url = $deal->destination_url;
                }
                if ($deal->coupon_code != "") {
                    $coupon = "View Coupon";
                    $str = 'click & open site to redeem offer';
                } else {
                    $coupon = "Redeem";
                    $str = 'click & open site to redeem offer';
                }
                $dealImg = isset($store->store_logo) ? $store->store_logo : "";
                if ($deal->image_url != null) {
                    $dealImg = $deal->image_url;
                }
                ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-30">
                    <div class="product-wrapper" itemscope itemtype="http://schema.org/Article">
                        <meta itemprop="author" content="offerndeal.codxplore.com"/>
                        <meta itemprop="headline" content="<?= substr($deal->title, 0, 110); ?>"/>
                        <meta itemprop="image" content="<?= $dealImg; ?>"/>
                        <meta itemprop="datePublished" content="<?= date('Y-m-d', strtotime($deal->start_date)); ?>"/>
                        <meta itemprop="dateModified" content="<?= date('Y-m-d', strtotime($deal->last_change_date)); ?>"/>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="product-image text-center" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                                <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">
                                    <span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject" >
                                        <img itemprop="url" src="<?= $dealImg; ?>" class="img-responsive img-max" alt="logo"/>
                                    </span>
                                </a>
                                <small>
                                    <meta itemprop="name" content="<?= $store->name; ?>"/>
                                    <a class="color-red" target="_new" href="<?php echo $destination_url; ?>">
                                        <?= $store->name; ?>
                                    </a>
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <div class="product-details">
                                <div class="product-title">
                                    <h5><a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>"><?= $deal->title; ?></a></h5>
                                </div>
                                <div class="product-description">
                                    <p itemprop="description">
                                        <?= $deal->content; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="cupon-num">
                                <small>End Date: <?= date('F j Y', strtotime($deal->end_date)); ?></small>
                                <span class="clearfix"></span>
                                <a id="d<?= $deal->deal_id; ?>" onclick="site.openRemoteUrl('<?php echo $destination_url; ?>', '<?= $deal->coupon_code; ?>',<?= $deal->deal_id; ?>)" href="javascript:;" class="btn" data-clipboard-text="<?= $coupon; ?>"><?= $coupon; ?></a>
                                <a id="link<?= $deal->deal_id; ?>" href="<?php echo $destination_url; ?>" target="_blank"></a>
                            </div>
                            <div class="cupon-info-text">
                                <span><?= $str; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="product-view-btn">
                                    <a itemprop="mainEntityOfPage" href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">view details</a>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div> 
                <?php
            }
            ?>
        </div> 
    </div>
</section>

<?php
if (!empty($products)) {
    ?>
    <section class="pdb-40 pdt-40 solitude-bg">
        <div class="container">
            <span class="clearfix"></span>
            <div class="page-title text-center mb-40">
                <h2 class="section-title section-title-width">Discounted Products</h2>
            </div>
            <div class="row">
                <?php
                foreach ($products as $product) {
                    $img = !empty($product->productImages[0])?$product->productImages[0]->image_url:"";
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-6 mb-30">
                        <div class="product-wrapper text-center" itemscope itemtype="http://schema.org/Product">
                            <div class="product-image p-img">
                                <a href="<?= yii\helpers\Url::to(['site/product-details', 'id' => $product->product_id, 'name' => clean($product->name)]); ?>">
                                    <img itemprop="image" src="<?= $img; ?>" class="img-responsive" alt="<?= $product->name; ?>"/>
                                </a>
                            </div>
                            <div class="product-entry">
                                <div class="product-title p-title" itemprop="name">
                                    <h5><a href="<?= yii\helpers\Url::to(['site/product-details', 'id' => $product->product_id, 'name' => clean($product->name)]); ?>"><?= $product->name; ?></a></h5>
                                </div>
                                <div class="product-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <span itemprop="priceCurrency" content="<?= $product->currency; ?>"> <?= $product->currency; ?></span> <span itemprop="price" content="<?= number_format($product->price, 2); ?>"><?= number_format($product->price, 2); ?></span> 
        <!--                                    <del><?= $product->currency; ?> 1500.00</del>-->
                                    <link itemprop="availability" href="https://schema.org/InStock" />
                                </div>
                                <div class="product-view-btn">
                                    <a href="<?= yii\helpers\Url::to(['site/product-details', 'id' => $product->product_id, 'name' => clean($product->name)]); ?>">view details</a>
                                </div>
                            </div> 
                        </div> 
                    </div> 

                    <?php
                }
                ?>
            </div>
        </div>
    </section>
    <?php
}
?>


<section class="category-section pd-100">
    <div class="container">
        <div class="next-section-link solitude-bg text-center">
            <span data-target=".category-section" class="scroll-to-target"><i class="fa fa-arrow-down"></i></span>
        </div>
        <div class="page-title text-center mb-40">
            <h1 class="section-title section-title-width">We Have Hundreds of Stores To Choose. You Ready?</h1>
        </div>
        <div class="row">
            <?php
            foreach ($stores as $str) {
                ?>
                <div class="col-md-2 col-sm-6 mb-30">

                    <div class="category-wrapper">
                        <a href="<?= yii\helpers\Url::to(['site/coupons-deals', 'id' => $str->store_id, 'type' => 's', 'name' => clean($str->name)]); ?>" class="category-name">

                            <img itemprop="image" src="<?= $str->store_logo; ?>" class="img-responsive" alt="logo"/>
                        </a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div> 
    </div>
</section>



<?php
$this->registerJs("$('body').on('submit', 'form#subscription-form', function (e) {
             var form = $(this);
             var userEmail = $('#newslettersubscriber-email').val();
             var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
             if($.trim(pattern) && pattern.test(userEmail)){
                $('#response').html('<div class=\"row\"><div class=\"col-md-12\"><div class=\"alert alert-info\">" . Yii::t('app', 'Sending....') . "</div></div></div>');
                $.ajax({
                    url: form.attr('action'),
                    type: 'post',
                    data: form.serialize(),
                    //async: false,
                    success: function (response) {
                       if(response.success==1){
                          $('#response').html('<div class=\"row\"><div class=\"col-md-12\"><div class=\"alert alert-success\">'+response.msg+'</div></div></div>');
                          $(\"#subscription-form\")[0].reset();
                          setTimeout(function(){
                             $('#response').html('');
                          },4000)
                       }
                       else if(response.success==2){
                          $('#response').html('<div class=\"row\"><div class=\"col-md-12\"><div class=\"alert alert-warning\">'+response.msg+'</div></div></div>');
                          $(\"#subscription-form\")[0].reset();
                          setTimeout(function(){
                             $('#response').html('');
                          },4000)
                       }
                       else{
                          $('#response').html('<div class=\"row\"><div class=\"col-md-12\"><div class=\"alert alert-warning\">'+response.msg+'</div></div></div>');
                          $(\"#subscription-form\")[0].reset();
                          setTimeout(function(){
                             $('#response').html('');
                          },4000)
                       }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        console.log(jqXHR.responseText);
                    }
                });
             }
             return false;   
        });", \yii\web\View::POS_END);
