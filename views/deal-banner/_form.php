<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DealBanners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deal-banners-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?=
    $form->field($model, 'type')->dropDownList(['H' => 'Horizontal', 'V' => 'Vertical',], [
        'prompt' => 'Please Select'
    ])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
