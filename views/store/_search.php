<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StoreSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stores-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'store_id') ?>

    <?= $form->field($model, 'api_store_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'store_url') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'store_logo') ?>

    <?php // echo $form->field($model, 'network_id') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
