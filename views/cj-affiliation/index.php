<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'CJ Affiliation Import';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cj-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $form = \yii\widgets\ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
            ])
    ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <br/>
        <?php
        echo Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-sm btn-primary']);
        ?>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>