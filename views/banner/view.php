<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Banners */

$this->title = '#'.$model->banner_id;
$this->params['breadcrumbs'][] = ['label' => 'Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="banners-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->banner_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->banner_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name_en',
            [
                'label' => 'Image',
                'value' => \yii\helpers\BaseUrl::home() . 'uploads/' . $model->image,
                'format' => ['image', ['width' => '96']],
            ],
            [
                'attribute' => 'type',
                'value' => ($model->type == 'L') ? "Link" : "Image Only"
            ],
            'url:url',
            [
                'attribute' => 'is_active',
                'value' => ($model->is_active == '1') ? "Yes" : "No"
            ],
            'created_at',
        ],
    ])
    ?>

</div>
