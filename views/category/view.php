<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="categories-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->category_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->category_id], [
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
            'api_category_id',
            'name',
            'description:ntext',
            'no_of_programs',
            'created_at',
            'updated_at',
            [
                'attribute' => 'parent_id',
                'value' => call_user_func(function($model) {
                    $parent = app\models\Categories::findOne($model->parent_id);
                    return !empty($parent)?$parent->name:"";
                },$model),
            ],
            [
                'attribute' => 'network_id',
                'value' => $model->network->network_name,
            ],
            [
                'attribute' => 'is_active',
                'value' => $model->is_active==1?"Yes":"No",
            ],
        ],
    ]) ?>

</div>
