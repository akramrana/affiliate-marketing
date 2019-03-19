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
            <?php
            $i = 0;
            foreach ($stores as $str) {
                $i++;
                ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 mb-30">
                    <div class="wrapper">
                        <a href="<?= yii\helpers\Url::to(['site/coupons-deals', 'id' => $str->store_id, 'type' => 's', 'name' => clean($str->name)]); ?>" class="category-name">
                            <img src="<?= $str->store_logo ?>" alt="img" class="img-responsive img-thumbnail"/>
                        </a>
                    </div>
                </div>
                <?php
                if ($i % 4 == 0) {
                    ?>
                    <span class="clearfix"></span>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</section>