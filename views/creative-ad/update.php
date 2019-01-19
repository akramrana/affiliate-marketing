<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CreativeAds */

$this->title = 'Update Creative Ads: #' . $model->creative_ad_id;
$this->params['breadcrumbs'][] = ['label' => 'Creative Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->creative_ad_id, 'url' => ['view', 'id' => $model->creative_ad_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="creative-ads-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
