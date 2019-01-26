<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model app\models\Banners */
/* @var $form yii\widgets\ActiveForm */
$linkStyle = 'display:none;';
$htmlStyle = 'display:none;';
if (!$model->isNewRecord) {
    if ($model->type == 'L') {
        $linkStyle = '';
    }
    else if($model->type == 'H'){
        $htmlStyle = '';
        $linkStyle = '';
    }
}
?>

<div class="banners-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>

    <label>
        Image
    </label><br/>
    <?php
    echo FileUpload::widget([
        'name' => 'Banners[image]',
        'url' => [
            'upload/common?attribute=Banners[image]'
        ],
        'options' => [
            'accept' => 'image/*',
            'tabindex' => 2
        ],
        'clientOptions' => [
            'dataType' => 'json',
            'maxFileSize' => 2000000,
        ],
        'clientEvents' => [
            'fileuploadprogressall' => "function (e, data) {
                                        var progress = parseInt(data.loaded / data.total * 100, 10);
                                        $('#progress').show();
                                        $('#progress .progress-bar').css(
                                            'width',
                                            progress + '%'
                                        );
                                     }",
            'fileuploaddone' => 'function (e, data) {
                                        if(data.result.files.error==""){
                                            var img = \'<br/><img id="clientImg" class="img-responsive" src="' . yii\helpers\BaseUrl::home() . 'uploads/\'+data.result.files.name+\'" alt="img" style="max-width:512px;"/>\';
                                            $("#image_preview").html(img);
                                            $(".field-banners-image input[type=hidden]").val(data.result.files.name);
                                            $("#progress .progress-bar").attr("style","width: 0%;");
                                            $("#progress").hide();
                                            $("#progress .progress-bar").attr("style","width: 0%;");
                                        }
                                        else{
                                           $("#progress .progress-bar").attr("style","width: 0%;");
                                           $("#progress").hide();
                                           var errorHtm = \'<span style="color:#dd4b39">\'+data.result.files.error+\'</span>\';
                                           $("#image_preview").html(errorHtm);
                                           setTimeout(function(){
                                               $("#image_preview span").remove();
                                           },3000)
                                        }
                                    }',
        ],
    ]);
    ?>

    <div id="progress" class="progress m-t-xs full progress-small" style="display: none;">
        <div class="progress-bar progress-bar-success"></div>
    </div>
    <div id="image_preview">
        <?php
        if (!$model->isNewRecord) {
            if ($model->image != "") {
                ?>
                <br/><img src="<?php echo yii\helpers\BaseUrl::home() ?>uploads/<?php echo $model->image ?>" alt="img" style="max-width:512px;"/>
                <?php
            }
        }
        ?>
    </div>

    <?php echo $form->field($model, 'image')->hiddenInput()->label(false); ?>

    <?=
    $form->field($model, 'type')->dropDownList(['L' => 'Link', 'I' => 'Image Only','H' => 'HTML'], [
        'prompt' => 'Please Select',
        'onclick' => 'app.showHideLink(this.value)',
    ])
    ?>

    <div id="link-section" style="<?= $linkStyle ?>">
        <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    </div>
    
    <div id="html-section" style="<?= $htmlStyle ?>">
        <?= $form->field($model, 'html_code')->textarea() ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
