<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Faqs */

$this->title = 'Update Faqs: ' . $model->faq_id;
$this->params['breadcrumbs'][] = ['label' => 'Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->faq_id, 'url' => ['view', 'id' => $model->faq_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="faqs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
