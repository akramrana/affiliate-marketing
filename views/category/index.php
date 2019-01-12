<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorieSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Categories', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'api_category_id',
            'name',
            //'created_at',
            //'updated_at',
            [
                'attribute' => 'parent_id',
                'value' => function($model) {
                    $parent = app\models\Categories::findOne($model->parent_id);
                    return !empty($parent) ? $parent->name : "";
                },
                'filter' => Html::activeDropDownList($searchModel, 'parent_id', app\helpers\AppHelper ::getAllCategories(), ['class' => 'form-control', 'prompt' => 'Filter']),
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
                                'id' => "myonoffswitch" . $model->category_id,
                                'onclick' => 'app.changeStatus("category/activate",this,' . $model->category_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="myonoffswitch' . $model->category_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            //'is_deleted',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
