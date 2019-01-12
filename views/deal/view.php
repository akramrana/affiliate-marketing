<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Deals */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Deals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="deals-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->deal_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->deal_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'content:ntext',
            [
                'attribute' => 'is_active',
                'value' => $model->is_active==1?"Yes":"No",
            ],
            //'coupon_id',
            [
                'attribute' => 'program_id',
                'value' => $model->program->name,
            ],
            'coupon_code',
            [
                'attribute' => 'voucher_types',
                'value' => ($model->voucher_types == 'P') ? "Promotion" : "Coupon",
            ],
            'start_date',
            'end_date',
            'expire_date',
            'last_change_date',
            'partnership_status',
            'integration_code:ntext',
            [
                'attribute' => 'featured',
                'value' => $model->featured==1?"Yes":"No",
            ],
            'minimum_order_value',
            'customer_restriction',
            'sys_user_ip',
            'destination_url:url',
            'discount_fixed',
            'discount_variable',
            'discount_code',
            [
                'attribute' => 'network_id',
                'value' => $model->network->network_name,
            ],
        ],
    ]) ?>

</div>
