<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DealBanners */

$this->title = 'Create Deal Banners';
$this->params['breadcrumbs'][] = ['label' => 'Deal Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-banners-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
