<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'Coupons&Deals';
$get = Yii::$app->request->queryParams;
//debugPrint($get);
?>
<section class="pdb-100 pdt-70 solitude-bg">
    <div class="container">
        <div class="page-title text-center mb-40">
            <h1><?= $this->title; ?></h1>
        </div>
        <div class="row">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <b>Sort by</b>
                        </div>
                        <hr/>
                        <div class="panel-body">
                            <select onchange="sortDeals(this.value)" name="sort_by" class="form-control">
                                <option value="">Default</option>
                                <option <?php
                                if (!empty($get['sort_by']) && $get['sort_by'] == "latest") {
                                    echo 'selected="selected"';
                                }
                                ?> value="latest">Latest</option>
                                <option <?php
                                if (!empty($get['sort_by']) && $get['sort_by'] == "oldest") {
                                    echo 'selected="selected"';
                                }
                                ?> value="oldest">Oldest</option>
                                <option <?php
                                if (!empty($get['sort_by']) && $get['sort_by'] == "end_date_asc") {
                                    echo 'selected="selected"';
                                }
                                ?> value="end_date_asc">End Date(ASC)</option>
                                <option <?php
                                if (!empty($get['sort_by']) && $get['sort_by'] == "end_date_desc") {
                                    echo 'selected="selected"';
                                }
                                ?> value="end_date_desc">End Date(DESC)</option>
                            </select>
                        </div>
                    </div>
                    <div class="panel">
                        <form method="get" action="<?= \yii\helpers\Url::to(['site/coupons-deals'], true); ?>">
                            <div class="panel-heading">
                                <b>Filter by store</b>
                            </div>
                            <hr/>
                            <div class="panel-body">
                                <?php
                                $stores = \app\helpers\AppHelper::getStoreWithCouponCount();
                                //debugPrint($get['store']);
                                if (!empty($stores)) {
                                    ?>
                                    <ul class="filter-store">
                                        <?php
                                        foreach ($stores as $str) {
                                            ?>
                                            <li><input 
                                                <?php
                                                if (!empty($get['store'])) {
                                                    if ($get['store'] == $str['store_id']) {
                                                        echo 'checked = "checked"';
                                                    }
                                                }
                                                if (!empty($get['id'])) {
                                                    if ($get['id'] == $str['store_id']) {
                                                        echo 'checked = "checked"';
                                                    }
                                                }
                                                ?> type="radio" name="store" value="<?php echo $str['store_id']; ?>"/> <?php echo $str['name']; ?>(<?php echo $str['no_of_deal']; ?>)</li>
                                                <?php
                                            }
                                            ?>
                                    </ul>
                                    <?php
                                }
                                ?>


                            </div>
                            <hr/>
                            <input class="btn btn-sm btn-primary btn-search" type="submit" name="search" value="Search"/>
                        </form>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <?php
                    if (!empty($models)) {
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
                                <div class="product-wrapper" itemscope itemtype="http://schema.org/Article">
                                    <meta itemprop="author" content="offerndeal.codxplore.com"/>
                                    <meta itemprop="headline" content="<?= substr($deal->title, 0, 110); ?>"/>
                                    <meta itemprop="image" content="<?= $dealImg; ?>"/>
                                    <meta itemprop="datePublished" content="<?= date('Y-m-d', strtotime($deal->start_date)); ?>"/>
                                    <meta itemprop="dateModified" content="<?= date('Y-m-d', strtotime($deal->last_change_date)); ?>"/>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="product-image text-center" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                                                <a href="<?php echo $destination_url; ?>">
                                                    <span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject" >
                                                        <img itemprop="url" src="<?= $dealImg; ?>" class="img-responsive img-thumbnail" alt="logo"/>
                                                    </span>
                                                </a>
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
                                                <a itemprop="mainEntityOfPage" href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">view details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <?php
                        }
                    } else {
                        echo '<h3 align="center">Sorry not result found!<h3>';
                    }
                    ?>
                </div>

            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
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
<?php
$params = '?';
if (!empty($get)) {
    $params = '?';
    unset($get['sort_by']);
    foreach ($get as $k => $g) {
        $params .= $k . '=' . $g;
    }
}
if ($params != "?") {
    $params .= '&';
}
$this->registerJs("
function sortDeals(val){
   window.location.href = baseUrl+'site/coupons-deals" . $params . "sort_by='+val;
}", yii\web\View::POS_END);
