<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\components\UserIdentity;
use app\components\AccessRule;

class TradeTrackerController extends \yii\web\Controller {

    private $api_customer_id = '182121';
    private $api_pass_phrase = '29cbe2fa70636a22e98cba80ae58f33aa971ea57';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [
                            UserIdentity::ROLE_ADMIN
                        ]
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new \app\models\JsonUpload();
        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id, 'network_passphrase' => $this->api_pass_phrase])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            Yii::$app->session->setFlash('error', 'Network does not exist');
            return $this->redirect(['deal/index']);
        }
        if ($model->load(Yii::$app->request->post())) {
            $json = UploadedFile::getInstance($model, 'file');
            if ($json) {
                $model->file = 'tradetracker-import-' . time() . '.' . $json->extension;
                $upload_path = Yii::$app->basePath . '/web/uploads/';
                $path = $upload_path . $model->file;
                $json->saveAs($path);
                //
                $jsonData = file_get_contents($path);
                $jsonObject = json_decode($jsonData);
                $k = 0;
                if (!empty($jsonObject->products)) {
                    $data = $jsonObject->products;
                    foreach ($data as $coupon) {
                        $store_name = $coupon->properties->campaignName[0];
                        $coupon_id = $coupon->ID;
                        $name = $coupon->name;
                        $url = $coupon->URL;
                        $explodeUrl = explode("&", $url);
                        $storeArr = explode("=", $explodeUrl[0]);
                        $program_id = $storeArr[1];
                        $description = $coupon->description;
                        $imageUrl = $coupon->images[0];
                        $category_name = key((array) $coupon->categories);
                        $created_at = !empty($coupon->properties->creationDate) ? $coupon->properties->creationDate[0] : "";
                        $valid_from_date = !empty($coupon->properties->validFromDate) ? $coupon->properties->validFromDate[0] : "";
                        $valid_to_date = !empty($coupon->properties->validToDate) ? $coupon->properties->validToDate[0] : "";
                        $discount_fixed = !empty($coupon->properties->discountFixed) ? $coupon->properties->discountFixed[0] : "";
                        $discount_variable = !empty($coupon->properties->discountVariable) ? $coupon->properties->discountVariable[0] : "";
                        $conditions = !empty($coupon->properties->conditions) ? $coupon->properties->conditions[0] : "";
                        $coupon_code = !empty($coupon->properties->voucherCode) ? $coupon->properties->voucherCode[0] : "";
                        //debugPrint($category);
                        $img = @getimagesize($imageUrl);
                        if(empty($img)){
                            continue;
                        }
                        $checkDeal = \app\models\Deals::find()
                                ->where(['LIKE', 'title', $name])
                                ->one();
                        if (empty($checkDeal)) {
                            $checkDeal = \app\models\Deals::find()
                                    ->where(['coupon_id' => $coupon_id, 'network_id' => $netWorkModel->network_id])
                                    ->one();
                            if (!empty($checkDeal)) {
                                continue;
                            }
                            $expire_date = empty($valid_to_date) ? date('Y-m-d H:i:s', strtotime('+1 year')) : date('Y-m-d H:i:s', strtotime($valid_to_date));
                            /**
                             * start store_id checking
                             */
                            $dealStore = \app\models\Stores::find()
                                    ->where(['api_store_id' => $program_id, 'network_id' => $netWorkModel->network_id])
                                    ->one();
                            $store_id = !empty($dealStore) ? $dealStore->store_id : null;
                            if ((empty($store_id))) {
                                $dealStore = \app\models\Stores::find()
                                        ->where(['name' => $store_name])
                                        ->one();
                                if ((empty($dealStore)) && $netWorkModel->create_store == 1) {
                                    $dealStore = new \app\models\Stores();
                                    $dealStore->api_store_id = $program_id;
                                    $dealStore->name = $store_name;
                                    $dealStore->store_url = strtolower($store_name);
                                    $dealStore->description = "";
                                    $dealStore->store_logo = $imageUrl;
                                    $dealStore->network_id = $netWorkModel->network_id;
                                    $dealStore->is_active = 0;
                                    $dealStore->is_deleted = 0;
                                    if ($dealStore->save()) {
                                        $store_id = $dealStore->store_id;
                                    }
                                } else {
                                    $store_id = $dealStore->store_id;
                                }
                            }
                            if (empty($store_id)) {
                                continue;
                            }
                            /*
                             * end store_id checking
                             */
                            //skip coupon if its expired
                            if (!empty($expire_date)) {
                                if (strtotime(date('Y-m-d H:i:s')) > strtotime($expire_date)) {
                                    continue;
                                }
                            }
                            $category = \app\models\Categories::find()
                                    ->where(['name' => $category_name])
                                    ->one();
                            if (empty($category) && $netWorkModel->create_category == 1) {
                                $category = new \app\models\Categories();
                                $category->api_category_id = $program_id;
                                $category->name = $category_name;
                                $category->created_at = date('Y-m-d H:i:s');
                                $category->updated_at = date('Y-m-d H:i:s');
                                $category->network_id = $netWorkModel->network_id;
                                if ($category->save()) {
                                    $category_id = $category->category_id;
                                }
                            } else {
                                $category_id = $category->category_id;
                            }
                            if (empty($category_id)) {
                                continue;
                            }
                            /**
                             * end category_id checking
                             */
                            // add coupon
                            $minOrderValue = '0.00';

                            $destination_url = str_replace('_0_', '_' . $coupon_id . '_', $url);
                            $destination_url = str_replace('&m=0', '&m=' . $coupon_id, $destination_url);
                            $destination_url = str_replace('_&r=', '_' . $coupon_id . '&r=', $destination_url);
                            $destination_url = str_replace('&r=&', '&r=' . $coupon_id . '&', $destination_url);

                            $deal = new \app\models\Deals();
                            $deal->title = $name;
                            $deal->content = $description . '<br/>' . $conditions;
                            $deal->image_url = $imageUrl;
                            $deal->is_active = $netWorkModel->auto_publish;
                            $deal->is_deleted = 0;
                            $deal->coupon_id = $coupon_id;
                            $deal->program_id = $program_id;
                            $deal->coupon_code = $coupon_code;
                            if (!empty($coupon_code)) {
                                $type = 'V';
                            } else {
                                $type = 'P';
                            }
                            $deal->voucher_types = $type;
                            $deal->start_date = empty($valid_from_date) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($valid_from_date));
                            $deal->end_date = empty($valid_to_date) ? date('Y-m-d H:i:s', strtotime('+1 year')) : date('Y-m-d H:i:s', strtotime($valid_to_date));
                            $deal->expire_date = $expire_date;
                            $deal->last_change_date = date('Y-m-d H:i:s', strtotime($created_at));
                            $deal->partnership_status = "Accepted";
                            $deal->integration_code = "";
                            $deal->featured = 0;
                            $deal->minimum_order_value = $minOrderValue;
                            $deal->customer_restriction = "";
                            $deal->sys_user_ip = $_SERVER['REMOTE_ADDR'];
                            $deal->destination_url = $url;
                            $deal->network_id = $netWorkModel->network_id;
                            $extras = [
                                'discount_fixed' => $discount_fixed,
                                'discount_variable' => $discount_variable,
                            ];
                            $deal->extras = !empty($extras) ? json_encode($extras) : "";
                            $deal->save(false);
                            //
                            $dealCategory = new \app\models\DealCategories();
                            $dealCategory->deal_id = $deal->deal_id;
                            $dealCategory->category_id = $category_id;
                            $dealCategory->created_at = date('Y-m-d H:i:s');
                            if (!$dealCategory->save()) {
                                die(json_encode($dealCategory->errors));
                            }
                            //
                            $dealStoreModel = new \app\models\DealStores();
                            $dealStoreModel->deal_id = $deal->deal_id;
                            $dealStoreModel->store_id = $store_id;
                            $dealStoreModel->created_at = date('Y-m-d H:i:s');
                            if (!$dealStoreModel->save()) {
                                die(json_encode($dealStoreModel->errors));
                            }
                            $k++;
                        }
                    }
                    Yii::$app->session->setFlash('success', 'Trade Tracker: ' . $k . ' coupons have been imported.');
                    return $this->redirect(['deal/index']);
                }
            }
        }
        return $this->render('index', [
                    'model' => $model
        ]);
    }

}
