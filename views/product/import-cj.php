<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\SelectAsset;

SelectAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = 'Import Cj';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="products-form">

        <?php $form = ActiveForm::begin(); ?>
        
        <?=
        $form->field($model, 'store_id')->dropDownList(app\helpers\AppHelper::getStoreByNetwork(2), [
            'prompt' => 'Please Select',
            'class' => 'select2 form-control'
        ])
        ?>
        
        <?=
        $form->field($model, 'category_id')->dropDownList(app\helpers\AppHelper::getCategoryByNetworkV2(2), [
            'prompt' => 'Please Select',
            'class' => 'select2 form-control'
        ])
        ?>
        
        <?=
        $form->field($model, 'import_limit')->textInput();
        ?>

        <div class="form-group">
            <?= Html::submitButton('Import', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<?php
$this->registerJs("$('.select2').select2();", \yii\web\View::POS_END, 'select-picker');
?>