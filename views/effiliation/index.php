<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Effiliation Import';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cj-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Import Coupon/Voucher', ['effiliation/import'], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Import Store', ['effiliation/import-store'], ['class' => 'btn btn-info']) ?>
    
</div>