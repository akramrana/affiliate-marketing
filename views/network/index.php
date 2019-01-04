<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NetworkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Networks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="networks-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Networks', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'network_name',
            'network_customer_id',
            'network_passphrase',
            'network_site_id',
            'network_site_locale',
            //'cron_daily',
            //'create_category',
            //'create_store',
            //'notify_stores',
            //'notify_categories',
            //'auto_publish',
            //'is_active',
            //'is_deleted',
            [
                'label' => 'Status',
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model, $url) {
                    return '<div class="onoffswitch">'
                            . Html::checkbox('onoffswitch', $model->is_active, [
                                'class' => "onoffswitch-checkbox",
                                'id' => "myonoffswitch" . $model->network_id,
                                'onclick' => 'app.changeStatus("network/activate",this,' . $model->network_id . ')',
                            ])
                            . '<label class="onoffswitch-label" for="myonoffswitch' . $model->network_id . '"></label></div>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-control select2', 'prompt' => 'Filter']),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
