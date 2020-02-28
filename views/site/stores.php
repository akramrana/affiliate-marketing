<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'Stores';
?>
<section class="pdb-100 pdt-70 white-bg">
    <div class="container">
        <div class="page-title text-center mb-40">
            <h1><?= $this->title; ?></h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $i = 0;
                foreach ($stores as $str) {
                    $i++;
                    ?>
                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 mb-30">
                        <div class="wrapper" style="background: <?= $str->store_logo ?>">
                            <a href="<?= yii\helpers\Url::to(['site/coupons-deals', 'id' => $str->store_id, 'type' => 's', 'name' => clean($str->name)]); ?>" class="category-name">
                                <img src="<?= $str->store_logo ?>" alt="img" class="img-thumbnail store-img"/>
                            </a>
                        </div>
                    </div>
                    <?php
                    if ($i % 6 == 0) {
                        ?>
                        <span class="clearfix"></span>
                        <hr style="margin-bottom: 35px;"/>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>