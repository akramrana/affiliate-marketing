<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Networks */

$this->title = $model->network_name;
$this->params['breadcrumbs'][] = ['label' => 'Networks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="networks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->network_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->network_id], [
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
            'network_name',
            'network_customer_id',
            'network_passphrase',
            'network_site_id',
            'network_site_locale',
            'cron_daily',
            [
                'attribute' => 'create_category',
                'value' => $model->create_category==1?"Yes":"No",
            ],
            [
                'attribute' => 'create_store',
                'value' => $model->create_store==1?"Yes":"No",
            ],
            [
                'attribute' => 'notify_stores',
                'value' => $model->notify_stores==1?"Yes":"No",
            ],
            [
                'attribute' => 'notify_categories',
                'value' => $model->notify_categories==1?"Yes":"No",
            ],
            [
                'attribute' => 'auto_publish',
                'value' => $model->auto_publish==1?"Yes":"No",
            ],
            [
                'attribute' => 'is_active',
                'value' => $model->is_active==1?"Yes":"No",
            ],
        ],
    ]) ?>

</div>
