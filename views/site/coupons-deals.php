<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'Coupons&Deals';
?>
<section class="pdb-100 pdt-70 solitude-bg">
    <div class="container">
        <div class="row">
            <?php
            foreach ($models as $deal) {
                $store = \app\models\Stores::find()->where(['api_store_id' => $deal->program_id])->one();
                if ($deal->integration_code != "") {
                    $arr = explode('>', $deal->integration_code);
                    $arr1 = explode('"', $arr[0]);
                    $destination_url = $arr1[1];
                } else {
                    $destination_url = $deal->destination_url;
                }
                if($deal->coupon_code!=""){
                    $coupon = $deal->coupon_code;
                    $str = 'click & open site to redeem offer';
                }else{
                    $coupon = "Redeem";
                    $str = 'click & open site to redeem offer';
                }
                ?>
                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="product-wrapper text-center" itemscope itemtype="http://schema.org/Product">
                        <div class="product-image">
                            <a href="<?= yii\helpers\Url::to(['site/coupon-details','id' => $deal->deal_id,'name' => clean($deal->title)]); ?>">
                                <img itemprop="image" src="<?= $store->store_logo; ?>" class="img-responsive" alt="logo"/>
                            </a>
                        </div>
                        <div class="product-entry">
                            <div class="product-title" itemprop="name">
                                <h5><a href="<?= yii\helpers\Url::to(['site/coupon-details','id' => $deal->deal_id,'name' => clean($deal->title)]); ?>"><?= $deal->title; ?></a></h5>
                            </div>
                            <div class="cupon-num">
                                <a href="<?php echo $destination_url;?>" class="btn" data-clipboard-text="<?=$coupon;?>" target="_new"><?=$coupon;?></a>
                            </div>
                            <div class="cupon-info-text text-center">
                                <span><?=$str;?></span>
                            </div>
                            <div class="product-view-btn">
                                <a href="<?= yii\helpers\Url::to(['site/coupon-details','id' => $deal->deal_id,'name' => clean($deal->title)]); ?>">view details</a>
                            </div>
                        </div> 
                    </div> 
                </div> 
                <?php
            }
            ?>
            
            <div class="col-lg-12">
                <?php
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pages,
                ]);
                ?>
            </div>
        </div>
</section>