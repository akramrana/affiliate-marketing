<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Trade Tracker Import';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cj-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Import Coupon/Voucher', ['trade-tracker/import'], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Import Store', ['trade-tracker/import-store'], ['class' => 'btn btn-info']) ?>
    
</div>