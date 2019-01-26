<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DealBanners */

$this->title = 'Update Deal Banners: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Deal Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->deal_banner_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="deal-banners-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
