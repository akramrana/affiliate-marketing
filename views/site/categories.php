<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'Categories';
?>
<section class="pdb-100 pdt-70 white-bg">
    <div class="container">
        <div class="page-title text-center mb-40">
            <h1><?= $this->title; ?></h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                foreach ($categories as $str) {
                    ?>
                    <div class="col-md-3 col-sm-6 mb-30">
                        <div class="category-wrapper">
                            <a href="<?= yii\helpers\Url::to(['site/coupons-deals', 'id' => $str->category_id, 'type' => 'c', 'name' => clean($str->name)]); ?>" class="category-name"><?= $str->name ?></a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>