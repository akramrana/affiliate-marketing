<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->product_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->product_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'product_id',
            [
                'attribute' => 'network_id',
                'value' => $model->network->network_name,
            ],
            'feed_id',
            'name',
            'price',
            'retail_price',
            'sale_price',
            'currency',
            'buy_url:ntext',
            'description:ntext',
            'advertiser_name',
            [
                'attribute' => 'is_stock',
                'value' => $model->is_stock == 1 ? "Yes" : "No",
            ],
            [
                'attribute' => 'is_active',
                'value' => $model->is_active == 1 ? "Yes" : "No",
            ],
        //'is_deleted',
        ],
    ])
    ?>

    <?php
    $dataProvider = new yii\data\ActiveDataProvider([
        'query' => $model->getProductCategories(),
        'pagination' => [
            'pageSize' => 20,
        ],
        'sort' => ['defaultOrder' => ['product_categories_id' => SORT_ASC]],
    ]);

    echo yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'category_id',
                'value' => function($data) {
                    return $data->category->name;
                },
            ],
        ],
    ]);
    ?>

    <?php
    $dataProvider = new yii\data\ActiveDataProvider([
        'query' => $model->getProductImages(),
        'pagination' => [
            'pageSize' => 20,
        ],
        'sort' => ['defaultOrder' => ['product_image_id' => SORT_ASC]],
    ]);

    echo yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'image_url',
                'value' => function($data) {
                    return $data->image_url;
                },
                'format' => 'image'
            ],
        ],
    ]);
    ?>

</div>
