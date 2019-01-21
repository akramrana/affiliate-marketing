<?php
/* @var $this yii\web\View */

$this->title = 'Home';
?>
<section class="rev_slider_wrapper fullwidthbanner-container">
    <div class="gadgetfloor_slider rev_slider fullwidthabanner" style="display:none">
        <ul>
            <?php
            foreach ($banners as $banner) {
                ?>
                <li data-transition="fade">
                    <img src="<?php echo \yii\helpers\BaseUrl::home(); ?>uploads/<?php echo $banner->image ?>" alt="banner" class="rev-slidebg">

                    <div class="tp-caption Creative-Title tp-resizeme" data-x="['left','left','center','left']" data-hoffset="['30','110','0','55']" data-y="['top','top','top','top']" data-voffset="['160','160','160','160']" data-fontsize="['45','45','45',35']" data-lineheight="['45','45','45','40']" data-width="['none','none','none','400']" data-height="['none','none','none','100']" data-whitespace="['nowrap','nowrap','nowrap','normal']" data-transform_idle="o:1;" data-transform_in="y:50px;opacity:0;s:1500;e:Power3.easeOut;" data-transform_out="opacity:0;s:1000;" data-start="700" data-splitin="none" data-splitout="none" data-responsive_offset="on" style="z-index: 6; text-align: left; white-space: nowrap; font-weight: 400; font-family: 'Vidaloka', serif; visibility: hidden; transition: none; border-width: 0px; margin: 0px; padding: 0px;"><?php echo $banner->name_en ?>
                    </div>

                    <?php
                    if ($banner->type == 'L') {
                        ?>
                        <div class="tp-caption Creative-Button rev-btn" data-x="['left','left','center','left']" data-hoffset="['30','110','0','55']" data-y="['top','top','top','top']" data-voffset="['250','250','240','280']" data-fontsize="['12','12','12','12']" data-fontweight="['400','400','400','400']" data-width="['188','none','none','none']" data-height="none" data-whitespace="['normal','nowrap','nowrap','nowrap']" data-transform_idle="o:1;" data-transform_hover="o:1;rX:0;rY:0;rZ:0;z:0;s:300;e:Power1.easeInOut;" data-style_hover="c:rgba(35, 35, 35, 1.00);bg:rgba(255, 255, 255, 1.00);" data-transform_in="y:50px;opacity:0;s:1500;e:Power3.easeOut;" data-transform_out="opacity:0;s:1000;" data-start="1000" data-splitin="none" data-splitout="none" data-responsive_offset="on" data-responsive="off" style="z-index: 7;  font-size: 12px; color: #fff; border: 1px solid #fff; cursor: pointer; transition: none; border-radius: 4px; margin: 0px; padding: 15px 30px; letter-spacing: 3px; text-transform: uppercase; opacity: 1;">
                            <a href="<?= $banner->url ?>">Let's explore</a>
                        </div>
                        <?php
                    }
                    ?>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</section>


<section class="pdb-40 solitude-bg">
    <div class="container product-section-content">
        <div class="row">
            <?php
            foreach ($top8 as $deal) {
                $store = \app\models\Stores::find()->where(['api_store_id' => $deal->program_id])->one();
                ?>
                <div class="col-md-3 col-sm-6 mb-30">
                    <div class="product-wrapper text-center" itemscope itemtype="http://schema.org/Product">
                        <div class="product-image">
                            <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">
                                <img itemprop="image" src="<?= $store->store_logo; ?>" class="img-responsive" alt="logo" style="width: 270px;height: 200px;"/>
                            </a>
                        </div>
                        <div class="product-entry">
                            <div class="product-title" itemprop="name" style="height: 80px;">
                                <h5><a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>"><?= $deal->title; ?></a></h5>
                            </div>
                            <div class="product-view-btn">
                                <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">view details</a>
                            </div>
                        </div> 
                    </div> 
                </div> 
                <?php
            }
            ?>
            <div class="clearfix visible-lg visible-md"></div>
            <div class="col-md-6 mb-30 clearfix">
                <div class="widget-subscribe product-subscribe">
                    <div class="subscribe-header text-center mb-50">
                        <span>subscribe us</span>
                        <h2>Get Amazing Cupons Code & Offers Everyday</h2>
                    </div>
                    <div class="subscribe-input">
                        <?php
                        $model = new app\models\NewsletterSubscriber();
                        $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'subscription-form',
                                    'enableClientScript' => false,
                                    'action' => \yii\helpers\Url::to(['site/subscribe']),
                                    'options' => [
                                        'method' => 'post',
                                        'class' => 'subscribe-form'
                                    ]
                        ]);
                        ?>
                        <div class="clearfix">
                            <div class="input-field mb-20">
                                <label class="sr-only" for="email">Email</label>
                                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'YOUR EMAIL'), 'class' => 'form-control'])->label(false); ?>
                            </div>
                            <?= \yii\helpers\Html::submitButton(Yii::t('app', 'Yes! let’s so this'), ['class' => 'btn btn-block btn-primary submit-btn']) ?>
                        </div>
                        <?php yii\bootstrap\ActiveForm::end(); ?>
                        <p id="response" class="subscription-success"></p>
                    </div>
                </div> 
            </div> 
            <?php
            foreach ($top2 as $deal) {
                $store = \app\models\Stores::find()->where(['api_store_id' => $deal->program_id])->one();
                ?>
                <div class="col-md-3 col-sm-6 mb-30">
                    <div class="product-wrapper text-center" itemscope itemtype="http://schema.org/Product">
                        <div class="product-image">
                            <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">
                                <img itemprop="image" src="<?= $store->store_logo; ?>" class="img-responsive" alt="logo" style="width: 270px;height: 200px;"/>
                            </a>
                        </div>
                        <div class="product-entry">
                            <div class="product-title" itemprop="name" style="height: 80px;">
                                <h5><a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>"><?= $deal->title; ?></a></h5>
                            </div>
                            <div class="product-view-btn">
                                <a href="<?= yii\helpers\Url::to(['site/coupon-details', 'id' => $deal->deal_id, 'name' => clean($deal->title)]); ?>">view details</a>
                            </div>
                        </div> 
                    </div> 
                </div> 
                <?php
            }
            ?>
        </div> 
    </div>
</section>


<section class="category-section pd-100">
    <div class="container">
        <div class="next-section-link solitude-bg text-center">
            <span data-target=".category-section" class="scroll-to-target"><i class="fa fa-arrow-down"></i></span>
        </div>
        <div class="page-title text-center mb-40">
            <h1 class="section-title section-title-width">We Have Hundreds of Stores To Choose. You Ready?</h1>
        </div>
        <div class="row">
            <?php
            foreach ($stores as $str) {
                ?>
                <div class="col-md-3 col-sm-6 mb-30">
                    <div class="category-wrapper">
                        <a href="<?= yii\helpers\Url::to(['site/coupons-deals', 'id' => $str->store_id, 'type' => 's', 'name' => clean($str->name)]); ?>" class="category-name"><?= $str->name ?></a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div> 
    </div>
</section>



<?php
$js = "$(document).on('ready',function() {
	 
	       		/* initialize the slider based on the Slider's ID or class attribute */
	        	jQuery('.gadgetfloor_slider').show().revolution({

	        		/* sets the Slider's default timeline */
    				delay: 9000,
	 
	            	/* options are 'auto', 'fullwidth' or 'fullscreen' */
	            	sliderLayout: 'auto',
    				disableProgressBar: 'on',
	 
	           		/* basic navigation arrows and bullets */
	            	navigation: {
	            		touch: {
								touchenabled: \"on\",
								swipe_threshold: 75,
								swipe_min_touches: 50,
								swipe_direction: \"horizontal\",
								drag_block_vertical: false
							},
	               		arrows: {
								style:\"hesperiden\",
								enable:true,
								hide_onmobile:false,
								hide_onleave:true,
								left: {
									h_align:\"left\",
									v_align:\"center\",
									h_offset:20,
									v_offset:0
								},
								right: {
									h_align:\"right\",
									v_align:\"center\",
									h_offset:20,
									v_offset:0
								}
							}
	            	},
	            	responsiveLevels: [1240, 1024, 778, 480],
					visibilityLevels: [1240, 1024, 778, 480],
					gridwidth: [1200, 1024, 778, 480],
					gridheight: [550, 550, 450, 450],
					lazyType: \"single\"
	        	});
	    	});";
$this->registerJs($js, yii\web\View::POS_END);

$this->registerJs("$('body').on('submit', 'form#subscription-form', function (e) {
             var form = $(this);
             var userEmail = $('#newslettersubscriber-email').val();
             var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
             if($.trim(pattern) && pattern.test(userEmail)){
                $('#response').html('<div class=\"row\"><div class=\"col-md-12\"><div class=\"alert alert-info\">" . Yii::t('app', 'Sending....') . "</div></div></div>');
                $.ajax({
                    url: form.attr('action'),
                    type: 'post',
                    data: form.serialize(),
                    //async: false,
                    success: function (response) {
                       if(response.success==1){
                          $('#response').html('<div class=\"row\"><div class=\"col-md-12\"><div class=\"alert alert-success\">'+response.msg+'</div></div></div>');
                          $(\"#subscription-form\")[0].reset();
                          setTimeout(function(){
                             $('#response').html('');
                          },4000)
                       }
                       else if(response.success==2){
                          $('#response').html('<div class=\"row\"><div class=\"col-md-12\"><div class=\"alert alert-warning\">'+response.msg+'</div></div></div>');
                          $(\"#subscription-form\")[0].reset();
                          setTimeout(function(){
                             $('#response').html('');
                          },4000)
                       }
                       else{
                          $('#response').html('<div class=\"row\"><div class=\"col-md-12\"><div class=\"alert alert-warning\">'+response.msg+'</div></div></div>');
                          $(\"#subscription-form\")[0].reset();
                          setTimeout(function(){
                             $('#response').html('');
                          },4000)
                       }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        console.log(jqXHR.responseText);
                    }
                });
             }
             return false;   
        });", \yii\web\View::POS_END);
