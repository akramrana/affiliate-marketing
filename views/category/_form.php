<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'api_category_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?=
    $form->field($model, 'parent_id')->dropDownList(app\helpers\AppHelper::getAllCategories($model->category_id), [
        'prompt' => 'Please Select',
    ])
    ?>

    <?=
    $form->field($model, 'network_id')->dropDownList(app\helpers\AppHelper::getAllNetwork(), [
        'prompt' => 'Please Select',
    ])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
