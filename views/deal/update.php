<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Deals */

$this->title = 'Update Deals: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Deals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->deal_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="deals-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
