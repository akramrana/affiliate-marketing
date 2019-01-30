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
    	<span class="pull pull-left">
    		<?= Html::a('Create New Deal', ['deal/create'], ['class' => 'btn btn-success']) ?>
    	</span>
        <span class="pull pull-right">
        <?= Html::a('Import Affilinet', ['affilinet/import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Import Awin', ['awin/import'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Import Trade Tracker', ['trade-tracker/index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Import CJ Affiliation', ['cj-affiliation/index'], ['class' => 'btn btn-primary']) ?>
        </span>
    </p>

    <br clear="all"/>
    
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'network_id',
                'value' => function($model) {
                    return $model->network->network_name;
                },
                'filter' => Html::activeDropDownList($searchModel, 'network_id', app\helpers\AppHelper::getAllNetwork(), ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            'title',
            //'content:ntext',
            //'coupon_id',
            [
                'attribute' => 'program_id',
                'value' => function($model) {
                    return $model->program->name;
                },
                'filter' => Html::activeDropDownList($searchModel, 'program_id', app\helpers\AppHelper::getStoresAsProgram(), ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            'coupon_code',
            //'voucher_types',
            [
                'attribute' => 'voucher_types',
                'value' => function($model) {
                    return ($model->voucher_types == 'P') ? "Promotion" : "Coupon";
                },
                'filter' => Html::activeDropDownList($searchModel, 'voucher_types', ['P' => 'Promotion', 'V' => 'Coupon'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            'start_date',
            //'end_date',
            'expire_date',
            //'last_change_date',
            'partnership_status',
            //'integration_code:ntext',
            //'featured',
            [
                'attribute' => 'featured',
                'format' => 'raw',
                'value' => function ($model, $url) {
                    return '<div class="onoffswitch">'
                            . Html::checkbox('onoffswitch', $model->featured, [
                                'class' => "onoffswitch-checkbox",
                                'id' => "fmyonoffswitch" . $model->deal_id,
                                'onclick' => 'app.changeStatus("deal/featured",this,' . $model->deal_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="fmyonoffswitch' . $model->deal_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'featured', [1 => 'Yes', 0 => 'No'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            //'minimum_order_value',
            //'customer_restriction',
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
    ]);
    ?>
</div>
