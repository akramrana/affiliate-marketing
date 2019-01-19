<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NewsletterSubscriber */

$this->title = 'Update Newsletter Subscriber: ' . $model->newsletter_subscriber_id;
$this->params['breadcrumbs'][] = ['label' => 'Newsletter Subscribers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->newsletter_subscriber_id, 'url' => ['view', 'id' => $model->newsletter_subscriber_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="newsletter-subscriber-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
