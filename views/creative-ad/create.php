<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CreativeAds */

$this->title = 'Create Creative Ads';
$this->params['breadcrumbs'][] = ['label' => 'Creative Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="creative-ads-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
