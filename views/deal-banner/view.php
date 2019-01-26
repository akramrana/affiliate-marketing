<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DealBanners */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Deal Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="deal-banners-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->deal_banner_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->deal_banner_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
                'attribute' => 'content',
                'format' => 'raw'
            ],
            [
                'attribute' => 'type',
                'value' => ($model->type == 'L') ?"Vertical" : "Horizontal"
            ],
            'created_at',
            [
                'attribute' => 'is_active',
                'value' => ($model->is_active == '1') ? "Yes" : "No"
            ],
        ],
    ]) ?>

</div>
