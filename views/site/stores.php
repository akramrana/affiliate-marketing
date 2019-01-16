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
        <div class="row">
            <?php
            foreach ($stores as $str) {
                ?>
                <div class="col-md-3 col-sm-6 mb-30">
                    <div class="wrapper">
                        <a href="<?= yii\helpers\Url::to(['site/coupons-deals','id' => $str->store_id,'type' => 's','name' => clean($str->name)]); ?>" class="category-name">
                            <img src="<?= $str->store_logo ?>" alt="img"/>
                        </a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>