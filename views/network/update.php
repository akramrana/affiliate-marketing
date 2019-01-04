<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Networks */

$this->title = 'Update Networks: ' . $model->network_name;
$this->params['breadcrumbs'][] = ['label' => 'Networks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->network_name, 'url' => ['view', 'id' => $model->network_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="networks-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
