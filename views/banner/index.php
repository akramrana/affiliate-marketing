<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banners';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Banners', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name_en',
            [
                'label' => 'Image',
                'value' => function($model) {
                    return \yii\helpers\BaseUrl::home() . 'uploads/' . $model->image;
                },
                'format' => ['image', ['width' => '96']],
                'filter' => false,
            ],
            [
                'attribute' => 'type',
                'value' => function($model) {
                    return ($model->type == 'L') ? "Link" : "Image Only";
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', ['L' => 'Link', 'I' => 'Image Only', 'H' => 'Html'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            'url:url',
            [
                'label' => 'html_code',
                'value' => function($model) {
                    return $model->html_code;
                },
                'format' => ['image', ['width' => '240']],
                'filter' => false,
            ],
            [
                'label' => 'Status',
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model, $url) {
                    return '<div class="onoffswitch">'
                            . Html::checkbox('onoffswitch', $model->is_active, [
                                'class' => "onoffswitch-checkbox",
                                'id' => "myonoffswitch" . $model->banner_id,
                                'onclick' => 'app.changeStatus("banner/activate",this,' . $model->banner_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="myonoffswitch' . $model->banner_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            //'is_deleted',
            //'created_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
