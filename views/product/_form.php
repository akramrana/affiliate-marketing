<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\SelectAsset;

SelectAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'network_id')->dropDownList(app\helpers\AppHelper::getAllNetwork(), [
        'prompt' => 'Please Select',
    ])
    ?>

    <?= $form->field($model, 'advertiser_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'feed_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'retail_price')->textInput() ?>

    <?= $form->field($model, 'sale_price')->textInput() ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'buy_url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>


    <?php
    if (!$model->isNewRecord && !empty($model->productCategories)) {
        $selected = [];
        foreach ($model->productCategories as $dc) {
            $selected[] = $dc->category_id;
        }
        $model->categories_id = $selected;
    }
    if (!$model->isNewRecord && !empty($model->productImages)) {
        $images = [];
        foreach ($model->productImages as $dc) {
            $images[] = $dc->image_url;
        }
        $model->image_url = implode('~~', $images);
    }
    echo $form->field($model, 'categories_id')->dropDownList(app\helpers\AppHelper::getAllCategories(), [
        'multiple' => 'multiple',
        'class' => 'select2 form-control',
    ])
    ?>

    <?=
    $form->field($model, 'store_id')->dropDownList(app\helpers\AppHelper::getAllStores(), [
        'prompt' => 'Please Select',
        'class' => 'select2 form-control',
    ])
    ?>

    <?= $form->field($model, 'image_url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'additional_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_featured')->checkbox() ?>

    <?= $form->field($model, 'is_stock')->checkbox() ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs("$('.select2').select2({placeholder: \"Please Select\",});", \yii\web\View::POS_END, 'select-picker');
?>