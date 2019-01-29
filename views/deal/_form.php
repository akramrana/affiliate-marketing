<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\DatePickerAsset;
use app\assets\SelectAsset;

SelectAsset::register($this);
DatePickerAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Deals */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deals-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'coupon_id')->textInput() ?>

    <?=
    $form->field($model, 'program_id')->dropDownList(app\helpers\AppHelper::getStoresAsProgram(), [
        'prompt' => 'Please Select',
    ])
    ?>

    <?= $form->field($model, 'coupon_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'voucher_types')->dropDownList(['V' => 'Voucher', 'P' => 'Promotion',], ['prompt' => 'Please Select']) ?>

    <?= $form->field($model, 'start_date')->textInput(['class' => 'datepicker form-control']) ?>

    <?= $form->field($model, 'end_date')->textInput(['class' => 'datepicker form-control']) ?>

    <?= $form->field($model, 'expire_date')->textInput(['class' => 'datepicker form-control']) ?>

    <?= $form->field($model, 'last_change_date')->textInput(['class' => 'datepicker form-control']) ?>

    <?= $form->field($model, 'partnership_status')->dropDownList(\app\helpers\AppHelper::getPartnershipStatus(), ['prompt' => 'Please Select']) ?>

    <?= $form->field($model, 'integration_code')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'featured')->checkbox() ?>

    <?= $form->field($model, 'minimum_order_value')->textInput() ?>

    <?= $form->field($model, 'customer_restriction')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sys_user_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'destination_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount_fixed')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount_variable')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount_code')->textInput(['maxlength' => true]) ?>


    <?=
    $form->field($model, 'network_id')->dropDownList(app\helpers\AppHelper::getAllNetwork(), [
        'prompt' => 'Please Select',
    ])
    ?>

    <?php
    if (!$model->isNewRecord && !empty($model->dealCategories)) {
        $selected = [];
        foreach ($model->dealCategories as $dc) {
            $selected[] = $dc->category_id;
        }
        $model->categories_id = $selected;
    }
    echo $form->field($model, 'categories_id')->dropDownList(app\helpers\AppHelper::getAllCategories(), [
        'multiple' => 'multiple',
        'class' => 'select2 form-control',
    ])
    ?>
    
    <?php
    if (!$model->isNewRecord && !empty($model->dealStores)) {
        $selected1 = [];
        foreach ($model->dealStores as $ds) {
            $selected1[] = $ds->store_id;
        }
        $model->stores_id = $selected1;
    }
    echo $form->field($model, 'stores_id')->dropDownList(app\helpers\AppHelper::getAllStores(), [
        'multiple' => 'multiple',
        'class' => 'select2 form-control',
    ])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = '$(\'.datepicker\').datepicker({
    format: \'yyyy-mm-dd\',
    autoclose:true,
    todayHighlight: true
});';
$this->registerJs($js, \yii\web\View::POS_END);

$this->registerJs("$('.select2').select2({placeholder: \"Please Select\",});", \yii\web\View::POS_END, 'select-picker');
?>