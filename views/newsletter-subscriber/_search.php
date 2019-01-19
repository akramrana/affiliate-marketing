<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NewsletterSubscriberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="newsletter-subscriber-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'newsletter_subscriber_id') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'is_active') ?>

    <?= $form->field($model, 'is_deleted') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
