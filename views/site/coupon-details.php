<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = $model->title;
$categoriesName = [];
if (!empty($model->dealCategories)) {
    foreach ($model->dealCategories as $dc) {
        $categoriesName[] = $dc->category->name;
    }
}
?>
<section class="pdb-100 pdt-70 solitude-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-push-4">
                <div class="single-product-wrapper">
                    <div class="single-product-content" style="margin-top: 0px;">
                        <div class="header-entry">
                            <div class="product-title" itemprop="name">
                                <h3><?= $model->title ?></h3>
                            </div>
                        </div> 
                        <div class="single-product-desc">
                            <p><?= $model->content; ?></p>
                        </div> 
                    </div> 
                </div> 

                <div class="subscribe-section">
                    <div class="subscribe-section-bg">
                        <div class="subscribe-header text-center">
                            <span>SUBSCRIBE US</span>
                            <h2>Get Amazing Coupons Code & Offers Everyday</h2>
                        </div> 
                        <?php
                        $nsSodel = new app\models\NewsletterSubscriber();
                        $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'subscription-form',
                                    'enableClientScript' => false,
                                    'action' => \yii\helpers\Url::to(['site/subscribe']),
                                    'options' => [
                                        'method' => 'post',
                                        'class' => 'subscribe-form  subscribe-input'
                                    ]
                        ]);
                        ?>
                        <div class="clearfix">
                            <div class="input-field mb-20">
                                <label class="sr-only" for="email">Email</label>
                                <?= $form->field($nsSodel, 'email')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'YOUR EMAIL'), 'class' => 'form-control'])->label(false); ?>
                            </div>
                            <?= \yii\helpers\Html::submitButton(Yii::t('app', 'Yes! let’s so this'), ['class' => 'btn btn-block btn-primary submit-btn']) ?>
                            <span class="clearfix"></span>
                            <div class="input-field mb-20">
                                <br class="clearfix"/>
                                <p id="response" class="subscription-success"></p>
                            </div>
                        </div>
                        <?php yii\bootstrap\ActiveForm::end(); ?>

                    </div>
                </div>
                <div class="row mt-30">
                    <?php
                    foreach ($related as $deal) {
                        $rstore = \app\models\Stores::find()->where(['api_store_id' => $deal->program_id])->one();
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
                        $dealImg = isset($rstore->store_logo) ? $rstore->store_logo : "";
                        if ($deal->image_url != null) {
                            $dealImg = $deal->image_url;
                        }
                        ?>
                        <div class="col-md-4 col-sm-6 mb-30">
                            <div class="product-wrapper text-center" itemscope itemtype="http://schema.org/Product">
                                <div class="product-image">
                                    <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">
                                        <img itemprop="image" src="<?= $dealImg; ?>" class="img-responsive img-max" alt="logo"/>
                                    </a>
                                </div>
                                <div class="product-entry">
                                    <div class="product-title" itemprop="name">
                                        <h5><a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>"><?= $deal->title; ?></a></h5>
                                    </div>
                                    <div class="cupon-num">
                                        <a id="d<?= $deal->deal_id; ?>" onclick="site.openRemoteUrl('<?php echo $destination_url; ?>', '<?= $deal->coupon_code; ?>',<?= $deal->deal_id; ?>)"  href="javascript:;" class="btn" data-clipboard-text="<?= $coupon; ?>"><?= $coupon; ?></a>
                                        <a id="link<?= $deal->deal_id; ?>" href="<?php echo $destination_url; ?>" target="_blank"></a>
                                    </div>
                                    <div class="cupon-info-text text-center">
                                        <span><?= $str; ?></span>
                                    </div>
                                    <div class="product-view-btn">
                                        <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">view details</a>
                                    </div>
                                </div> 
                            </div> 
                        </div> 
                        <?php
                    }
                    ?>
                </div>
            </div> 
            <div class="col-md-4 col-md-pull-8">
                <div class="tt-sidebar">
                    <div class="widget item-price-widget text-center">
                        <div class="item-from-buy">
                            <?php
                            if ($model->integration_code != "") {
                                $arr = explode('>', $model->integration_code);
                                $arr1 = explode('"', $arr[0]);
                                $destination_url = $arr1[1];
                            } else {
                                $destination_url = $model->destination_url;
                            }
                            if ($model->coupon_code != "") {
                                $str = 'Redeem Offer';
                            } else {
                                $str = 'Redeem Offer';
                            }
                            ?>
                            <a onclick="site.openRemoteUrlSingle('<?php echo $destination_url; ?>', '<?= $model->coupon_code; ?>',<?= $model->deal_id; ?>)"  href="javascript:;" class="btn btn-primary btn-lg buy-from-amazon"><i class="fa fa-asterisk"></i><?= $str; ?></a>

                        </div>
                    </div> 
                    <div class="widget product-overview mt-30">
                        <h5>Overview</h5>
                        <div class="product-over-view-details">
                            <p><span>Store</span><img src="<?= isset($store->store_logo) ? $store->store_logo : "" ?>" alt="<?= isset($store) ? $store->name : "" ?>" class="max-img-width"/></p><span class="clearfix">&nbsp;</span>
                            <p><span>Categories</span><?php echo implode(',', $categoriesName); ?></p><span class="clearfix">&nbsp;</span>
                            <?php
                            if ($model->customer_restriction != "") {
                                ?>
                                <p><span>Compatibility</span><?= $model->customer_restriction; ?></p><span class="clearfix">&nbsp;</span>
                                <?php
                            }
                            ?>
                            <p><span>End Date</span><?= date('F j Y', strtotime($model->end_date)); ?></p><span class="clearfix">&nbsp;</span>
                            <p>
                                <span>Coupon Code</span>
                                <a id="s<?= $model->deal_id; ?>" onclick="site.openRemoteUrlSingle('<?php echo $destination_url; ?>', '<?= $model->coupon_code; ?>',<?= $model->deal_id; ?>)"  href="javascript:;" class="color-red">
                                    <?= ($model->coupon_code != "") ? "View Coupon" : "Redeem"; ?>
                                </a>
                                <a id="slink<?= $model->deal_id; ?>" href="<?php echo $destination_url; ?>" target="_blank"></a>
                            </p>
                            <span class="clearfix">&nbsp;</span>
                        </div>
                    </div>
                    <div class="widget populer-product-widget mt-30">
                        <h5>Popular Deals</h5>
                        <?php
                        $creativeAds = app\helpers\AppHelper::getRandomCreativeAds(3);
                        foreach ($creativeAds as $ca) {
                            ?>
                            <div class="populer-product-details" itemscope="" itemtype="http://schema.org/Product">
                                <div class="product-image">
                                    <?php
                                    echo $ca->content;
                                    ?>
                                </div>
                            </div> 
                            <span class="clearfix">&nbsp;</span>
                            <?php
                        }
                        ?>
                    </div>
                </div> 
            </div> 
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
