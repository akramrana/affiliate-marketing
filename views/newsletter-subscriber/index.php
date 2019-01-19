<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsletterSubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Newsletter Subscribers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="newsletter-subscriber-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'email:email',
            'created_at',
            [
                'label' => 'Status',
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model, $url) {
                    return '<div class="onoffswitch">'
                            . Html::checkbox('onoffswitch', $model->is_active, [
                                'class' => "onoffswitch-checkbox",
                                'id' => "myonoffswitch" . $model->newsletter_subscriber_id,
                                'onclick' => 'app.changeStatus("newsletter-subscriber/activate",this,' . $model->newsletter_subscriber_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="myonoffswitch' . $model->newsletter_subscriber_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{delete}'],
        ],
    ]); ?>
</div>
