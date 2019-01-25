<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stores-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Stores', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'api_store_id',
            'name',
            'store_url:url',
            [
                'attribute' => 'store_logo',
                'value' => function($model) {
                    return $model->store_logo;
                },
                'format' => ['image',['width' => '150']],
                'filter' => false,
            ],
            [
                'attribute' => 'network_id',
                'value' => function($model) {
                    return $model->network->network_name;
                },
                'filter' => Html::activeDropDownList($searchModel, 'network_id', app\helpers\AppHelper::getAllNetwork(), ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            [
                'label' => 'Status',
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model, $url) {
                    return '<div class="onoffswitch">'
                            . Html::checkbox('onoffswitch', $model->is_active, [
                                'class' => "onoffswitch-checkbox",
                                'id' => "myonoffswitch" . $model->store_id,
                                'onclick' => 'app.changeStatus("store/activate",this,' . $model->store_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="myonoffswitch' . $model->store_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
