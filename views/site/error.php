<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$name = $exception->getName().'(#'.Yii::$app->errorHandler->exception->statusCode.')';
$this->title = $name;
?>
<section class="pd-100 solitude-bg">
    <div class="container">
        <div class="error-wrapper text-center">
            <h1 class="mb-30"><?php echo Yii::$app->errorHandler->exception->statusCode ?></h1>
            <span class="error-sub"><?= $name ?></span>
            <p><?= nl2br(Html::encode($message)) ?></p>
            <a href="<?php echo yii\helpers\BaseUrl::home(); ?>" class="btn btn-primary bgMid">Home</a>
        </div>
    </div>
</section>