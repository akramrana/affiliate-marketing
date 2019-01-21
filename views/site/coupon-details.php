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
                            <h2>Get Amazing Cupons Code & Offers Everyday</h2>
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
                        ?>
                        <div class="col-md-4 col-sm-6 mb-30">
                            <div class="product-wrapper text-center" itemscope itemtype="http://schema.org/Product">
                                <div class="product-image">
                                    <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">
                                        <img itemprop="image" src="<?= $rstore->store_logo; ?>" class="img-responsive" alt="logo" style="width: 270px;height: 200px;"/>
                                    </a>
                                </div>
                                <div class="product-entry">
                                    <div class="product-title" itemprop="name" style="height: 80px;">
                                        <h5><a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>"><?= $deal->title; ?></a></h5>
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
                            }else{
                                $destination_url = $model->destination_url;
                            }
                            ?>
                            <a target="_new" href="<?= $destination_url; ?>" class="btn btn-primary btn-lg buy-from-amazon"><i class="fa fa-asterisk"></i>Redeem Offer</a>
                        </div>
                    </div> 
                    <div class="widget product-overview mt-30">
                        <h5>Overview</h5>
                        <div class="product-over-view-details">
                            <p><span>Store</span><img src="<?= $store->store_logo ?>" alt="<?= $store->name ?>"/></p>
                            <p><span>Categories</span><?php echo implode(',', $categoriesName); ?></p>
                            <p><span>Compatibility</span><?= $model->customer_restriction; ?></p>
                            <p><span>End Date</span><?= date('F j Y', strtotime($model->end_date)); ?></p>
                        </div>
                    </div>
                    <div class="widget populer-product-widget mt-30">
                        <h5>Popular Shops</h5>
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
