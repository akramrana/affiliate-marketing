<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="pull pull-left">
        <?= Html::a('Create Products', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <p class="pull pull-right">
        <?= Html::a('Import TTC Products', ['import-ttc'], ['class' => 'btn btn-info']) ?>
    </p>

    <span class="clearfix"></span>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'product_id',
            [
                'label' => 'Image',
                'value' => function($model) {
                    $img = $model->productImages[0]->image_url;
                    return $img;
                },
                'format' => ['image', ['width' => '128']],
            ],
            [
                'attribute' => 'network_id',
                'value' => function($model) {
                    return $model->network->network_name;
                },
                'filter' => Html::activeDropDownList($searchModel, 'network_id', app\helpers\AppHelper::getAllNetwork(), ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            'feed_id',
            'name',
            'price',
            //'retail_price',
            //'sale_price',
            'currency',
            'buy_url:ntext',
            //'description:ntext',
            'advertiser_name',
            //'is_stock',
            //'is_active',
            [
                'attribute' => 'is_featured',
                'format' => 'raw',
                'value' => function ($model, $url) {
                    return '<div class="onoffswitch">'
                            . Html::checkbox('onoffswitch', $model->is_featured, [
                                'class' => "onoffswitch-checkbox",
                                'id' => "fmyonoffswitch" . $model->product_id,
                                'onclick' => 'app.changeStatus("product/featured",this,' . $model->product_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="fmyonoffswitch' . $model->product_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_featured', [1 => 'Yes', 0 => 'No'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            [
                'label' => 'Status',
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model, $url) {
                    return '<div class="onoffswitch">'
                            . Html::checkbox('onoffswitch', $model->is_active, [
                                'class' => "onoffswitch-checkbox",
                                'id' => "myonoffswitch" . $model->product_id,
                                'onclick' => 'app.changeStatus("product/activate",this,' . $model->product_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="myonoffswitch' . $model->product_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            //'is_deleted',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
