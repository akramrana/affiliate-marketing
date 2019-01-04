<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NetworkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="networks-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'network_id') ?>

    <?= $form->field($model, 'network_name') ?>

    <?= $form->field($model, 'network_customer_id') ?>

    <?= $form->field($model, 'network_passphrase') ?>

    <?= $form->field($model, 'network_site_id') ?>

    <?php // echo $form->field($model, 'network_site_locale') ?>

    <?php // echo $form->field($model, 'cron_daily') ?>

    <?php // echo $form->field($model, 'create_category') ?>

    <?php // echo $form->field($model, 'create_store') ?>

    <?php // echo $form->field($model, 'notify_stores') ?>

    <?php // echo $form->field($model, 'notify_categories') ?>

    <?php // echo $form->field($model, 'auto_publish') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
