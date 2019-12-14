<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'Products';
$params = Yii::$app->request->queryParams;
//debugPrint($params);
?>
<section class="pdt-100 pdb-70 solitude-bg">
    <div class="container">
        <div class="page-title text-center mb-40">
            <h2>Discounted Products</h2>
        </div> 
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-30">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="panel">
                        <form method="get" action="<?= \yii\helpers\Url::to(['site/products'], true); ?>">
                            <div class="panel-heading">
                                <b>Filter</b>
                            </div>
                            <hr/>
                            <div class="panel-body">
                                <?php
                                $stores = \app\helpers\AppHelper::getStoreWithProductCount();
                                //debugPrint($stores);
                                if (!empty($stores)) {
                                    ?>
                                    <select name="store" class="form-control">
                                        <option value="">Filter by store</option>
                                        <?php
                                        foreach ($stores as $str) {
                                            ?>
                                            <option <?php
                                            if (!empty($params['store'])) {
                                                if ($params['store'] == $str['advertiser_name']) {
                                                    echo 'selected = "selected"';
                                                }
                                            }
                                            ?> value="<?php echo $str['advertiser_name']; ?>"><?php echo $str['advertiser_name']; ?>(<?php echo $str['num_product']; ?>)</option>
                                                <?php
                                            }
                                            ?>
                                    </select>
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
                        foreach ($models as $product) {
                            $img = $product->productImages[0]->image_url;
                            ?>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="product-wrapper text-center" itemscope itemtype="http://schema.org/Product">
                                    <meta itemprop="brand" content="<?= $product->advertiser_name; ?>"/>
                                    <meta itemprop="description" content="<?= $product->description; ?>" />
                                    <meta itemprop="productID" content="<?= $product->product_id; ?>" />
                                    <meta itemprop="sku" content="<?= $product->feed_id; ?>" />
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
    </div>
</section>
