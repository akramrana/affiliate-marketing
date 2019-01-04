<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Networks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="networks-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'network_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'network_customer_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'network_passphrase')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'network_site_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'network_site_locale')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cron_daily')->textInput() ?>

    <?= $form->field($model, 'create_category')->checkbox() ?>

    <?= $form->field($model, 'create_store')->checkbox() ?>

    <?= $form->field($model, 'notify_stores')->checkbox() ?>

    <?= $form->field($model, 'notify_categories')->checkbox() ?>

    <?= $form->field($model, 'auto_publish')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
