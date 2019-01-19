<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CreativeAdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Creative Ads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="creative-ads-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Creative Ads', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'content',
                'format' => 'raw'
            ],
            ['class' => 'yii\grid\ActionColumn','template' => "{update} {delete}"],
        ],
    ]); ?>
</div>
