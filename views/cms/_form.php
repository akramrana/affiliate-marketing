<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

\app\assets\CmsEditorAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Cms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cms-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content_en')->textarea(['rows' => 6,'class' => 'summernote']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs("$(function () {
        $('.summernote').summernote({
            height: 200
        });
    });", \yii\web\View::POS_END);
?>