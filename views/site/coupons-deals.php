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
        <div class="page-title text-center mb-40">
            <h1><?= $this->title; ?></h1>
        </div>
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
                    <div class="product-wrapper" itemscope itemtype="http://schema.org/Product">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="product-image">
                                    <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">
                                        <img itemprop="image" src="<?= $dealImg; ?>" class="img-responsive" alt="logo"/>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 pad-left-10">
                                <div class="product-details">
                                    <div class="product-title" itemprop="name">
                                        <h5><a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>"><?= $deal->title; ?></a></h5>
                                    </div>
                                    <div class="product-description" itemprop="description">
                                        <p>
                                            <?= $deal->content; ?>
                                        </p>
                                    </div>
                                </div> 
                                <div class="cupon-num">
                                    <small>End Date: <?= date('F j Y', strtotime($deal->end_date)); ?></small>
                                    <span class="clearfix"></span>
                                    <a id="d<?= $deal->deal_id; ?>" onclick="site.openRemoteUrl('<?php echo $destination_url; ?>', '<?= $deal->coupon_code; ?>',<?= $deal->deal_id; ?>)"  href="javascript:;" class="btn" data-clipboard-text="<?= $coupon; ?>"><?= $coupon; ?></a>
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
                                    <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">view details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <?php
            }
            ?>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pages,
                    'firstPageLabel' => 'First',
                    'lastPageLabel' => 'Last',
                ]);
                ?>
            </div>
        </div>
</section>