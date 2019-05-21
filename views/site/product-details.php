<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = $model->name;
?>
<section class="pdb-100 pdt-70 solitude-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-push-4">
                <div class="single-product-wrapper">
                    <div class="single-product-gallery">
                        <div id="project-slider" class="carousel slide gallery-thumb" data-ride="carousel">
                            <div class="carousel-inner" role="listbox">
                                <?php
                                $i = 0;
                                foreach ($model->productImages as $img) {
                                    $i++;
                                    ?>
                                    <div class="item <?= ($i == 1) ? "active" : "" ?>">
                                        <img src="<?= $img->image_url; ?>" alt="<?= $model->name; ?>"/>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <a class="left carousel-control" href="#project-slider" role="button" data-slide="prev">
                                <span class="fa fa-arrow-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#project-slider" role="button" data-slide="next">
                                <span class="fa fa-arrow-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div> 
                    <div class="single-product-content">
                        <div class="header-entry">
                            <div class="product-title" itemprop="name">
                                <h3><?= $model->name; ?></h3>
                            </div>
                        </div> 
                        <div class="single-product-desc">
                            <?= $model->description; ?>
                        </div> 
                    </div> 
                </div> 
                <div class="row mt-30">
                    <?php
                    if (!empty($related)) {
                        foreach ($related as $product) {
                            $img = $product->productImages[0]->image_url;
                            ?>
                            <div class="col-lg-6 col-sm-6 mb-30">
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
<!--                                            <del><?= $product->currency; ?> 1500.00</del>-->
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
            <div class="col-md-4 col-md-pull-8">
                <div class="tt-sidebar">
                    <div class="widget item-price-widget text-center">
                        <div class="price-ammount" itemprop="offers" itemtype="http://schema.org/Offer">
                            <h3><?= number_format($model->price, 2); ?> <?= $model->currency; ?></h3>
                        </div>
                        <div class="item-from-buy">
                            <a target="_new" href="<?= $model->buy_url; ?>" class="btn btn-primary btn-lg buy-from-amazon"><i class="fa fa-amazon"></i>Buy via <?= $model->advertiser_name; ?></a>
                        </div>
                    </div> 
                    <div class="widget product-overview mt-30">
                        <h5>Overview & Specs</h5>
                        <div class="product-over-view-details">
                            <?= $model->additional_info; ?>
                        </div>
                    </div> 
                    <div class="widget populer-product-widget mt-30">
                        <?php
                        $creativeAds = app\helpers\AppHelper::getRandomCreativeAds(1);
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
