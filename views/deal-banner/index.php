<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DealBannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deal Banners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-banners-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Deal Banners', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            [
                'attribute' => 'content',
                'format' => 'raw'
            ],
            [
                'attribute' => 'type',
                'value' => function($model) {
                    return ($model->type == 'V') ? "Vertical" : "Horizontal";
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', ['V' => 'Vertical', 'H' => 'Horizontal'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            'created_at',
            //'is_deleted',
            [
                'label' => 'Status',
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model, $url) {
                    return '<div class="onoffswitch">'
                            . Html::checkbox('onoffswitch', $model->is_active, [
                                'class' => "onoffswitch-checkbox",
                                'id' => "myonoffswitch" . $model->deal_banner_id,
                                'onclick' => 'app.changeStatus("deal-banner/activate",this,' . $model->deal_banner_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="myonoffswitch' . $model->deal_banner_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
