<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deals-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Import Affilinet', ['affilinet/import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Import Awin', ['awin/import'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'network_id',
            'title',
            //'content:ntext',
            'coupon_id',
            'program_id',
            'coupon_code',
            'voucher_types',
            'start_date',
            //'end_date',
            'expire_date',
            //'last_change_date',
            'partnership_status',
            //'integration_code:ntext',
            //'featured',
            //'minimum_order_value',
            'customer_restriction',
            [
                'label' => 'Status',
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model, $url) {
                    return '<div class="onoffswitch">'
                            . Html::checkbox('onoffswitch', $model->is_active, [
                                'class' => "onoffswitch-checkbox",
                                'id' => "myonoffswitch" . $model->deal_id,
                                'onclick' => 'app.changeStatus("deal/activate",this,' . $model->deal_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="myonoffswitch' . $model->deal_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            //'sys_user_ip',
            //'destination_url:url',
            //'discount_fixed',
            //'discount_variable',
            //'discount_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
