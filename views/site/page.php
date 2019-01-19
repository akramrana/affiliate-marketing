<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = $model->title_en;
$this->registerCss("a{color:#333}");
?>
<section class="pdt-70 pdb-100 solitude-bg">
    <div class="container">
        <div class="privacy-content">
            <h2><?php echo $model->title_en; ?></h2>
            <?php echo html_entity_decode($model->content_en); ?>
            <span class="clearfix"></span>
        </div> 
    </div>
</section>
