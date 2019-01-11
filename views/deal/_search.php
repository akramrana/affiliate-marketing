<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DealSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deals-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'deal_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'is_active') ?>

    <?= $form->field($model, 'is_deleted') ?>

    <?php // echo $form->field($model, 'coupon_id') ?>

    <?php // echo $form->field($model, 'program_id') ?>

    <?php // echo $form->field($model, 'coupon_code') ?>

    <?php // echo $form->field($model, 'voucher_types') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'expire_date') ?>

    <?php // echo $form->field($model, 'last_change_date') ?>

    <?php // echo $form->field($model, 'partnership_status') ?>

    <?php // echo $form->field($model, 'integration_code') ?>

    <?php // echo $form->field($model, 'featured') ?>

    <?php // echo $form->field($model, 'minimum_order_value') ?>

    <?php // echo $form->field($model, 'customer_restriction') ?>

    <?php // echo $form->field($model, 'sys_user_ip') ?>

    <?php // echo $form->field($model, 'destination_url') ?>

    <?php // echo $form->field($model, 'discount_fixed') ?>

    <?php // echo $form->field($model, 'discount_variable') ?>

    <?php // echo $form->field($model, 'discount_code') ?>

    <?php // echo $form->field($model, 'network_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
