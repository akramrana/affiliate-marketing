<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cms */

$this->title = 'Update Cms: ' . $model->cms_id;
$this->params['breadcrumbs'][] = ['label' => 'Cms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cms_id, 'url' => ['view', 'id' => $model->cms_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cms-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
