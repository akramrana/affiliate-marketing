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
    private $api_site_id = '324798';

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

    public function actionImport() {
        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id, 'network_passphrase' => $this->api_pass_phrase])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            Yii::$app->session->setFlash('error', 'Network does not exist');
            return $this->redirect(['deal/index']);
        }
        $client = new \SoapClient('http://ws.tradetracker.com/soap/affiliate?wsdl', array('compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP));
        $client->authenticate($this->api_customer_id, $this->api_pass_phrase);
        $materialOutputType = 'html';
        $options = array(
            'reference' => 'offerndeal-codxplore',
            'limit' => 100,
        );
        $vouchers = $client->getMaterialIncentiveVoucherItems($this->api_site_id, $materialOutputType, $options);
        $c = 0;
        if (!empty($vouchers)) {
            foreach ($vouchers as $coupon) {
                $addCoupon = $this->addDeal($coupon, $netWorkModel);
                if ($addCoupon) {
                    $c++;
                }
            }
        }
        $p = 0;
        $promotions = $client->getMaterialIncentiveOfferItems($this->api_site_id, $materialOutputType, $options);
        if (!empty($promotions)) {
            foreach ($promotions as $promo) {
                $addPromo = $this->addDeal($promo, $netWorkModel);
                if ($addPromo) {
                    $p++;
                }
            }
        }
        Yii::$app->session->setFlash('success', 'Tradetracker: ' . $c . ' coupons and ' . $p . ' promotions have been imported.');
        return $this->redirect(['deal/index']);
    }

    public function addDeal($coupon, $netWorkModel) {
        $coupon_id = $coupon->ID;
        $name = $coupon->name;
        $program_id = $coupon->campaign->ID;
        $description = $coupon->description;
        $created_at = !empty($coupon->creationDate) ? $coupon->creationDate : "";
        $valid_from_date = !empty($coupon->validFromDate) ? $coupon->validFromDate : "";
        $valid_to_date = !empty($coupon->validToDate) ? $coupon->validToDate : "";
        $discount_fixed = !empty($coupon->discountFixed) ? $coupon->discountFixed : "";
        $discount_variable = !empty($coupon->discountVariable) ? $coupon->discountVariable : "";
        $conditions = !empty($coupon->conditions) ? $coupon->conditions : "";
        $coupon_code = !empty($coupon->voucherCode) ? $coupon->voucherCode : "";
        $code = $coupon->code;
        //$codeArr = explode('<a href="', $code);
        $urlArr = explode('"', $code);
        $url = !empty($urlArr[1]) ? $urlArr[1] : "";
        $minOrderValue = '0.00';
        $expire_date = empty($valid_to_date) ? date('Y-m-d H:i:s', strtotime('+1 year')) : date('Y-m-d H:i:s', strtotime($valid_to_date));
        //
        $checkDeal = \app\models\Deals::find()
                ->where(['=', 'title', $name])
                ->andWhere(['network_id' => $netWorkModel->network_id])
                ->one();
        if (!empty($checkDeal)) {
            return false;
        }
        $checkDeal1 = \app\models\Deals::find()
                ->where(['coupon_id' => $coupon_id, 'network_id' => $netWorkModel->network_id])
                ->one();
        if (!empty($checkDeal1)) {
            return false;
        }
        /**
         * start store_id checking
         */
        $dealStore = \app\models\Stores::find()
                ->where(['api_store_id' => $program_id, 'network_id' => $netWorkModel->network_id])
                ->one();
        $store_id = !empty($dealStore) ? $dealStore->store_id : null;
        if (empty($store_id)) {
            return false;
        }
        if (!empty($expire_date)) {
            if (strtotime(date('Y-m-d H:i:s')) > strtotime($expire_date)) {
                return false;
            }
        }
        $categories = \app\models\Categories::find()
                ->where(['api_category_id' => $program_id, 'network_id' => $netWorkModel->network_id])
                ->all();
        //
        $deal = new \app\models\Deals();
        $deal->title = $name;
        $deal->content = $description . '<br/>' . $conditions;
        $deal->image_url = "";
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
        if (!empty($categories)) {
            foreach ($categories as $cat) {
                $dealCategory = new \app\models\DealCategories();
                $dealCategory->deal_id = $deal->deal_id;
                $dealCategory->category_id = $cat->category_id;
                $dealCategory->created_at = date('Y-m-d H:i:s');
                if (!$dealCategory->save()) {
                    die(json_encode($dealCategory->errors));
                }
            }
        }
        //
        $dealStoreModel = new \app\models\DealStores();
        $dealStoreModel->deal_id = $deal->deal_id;
        $dealStoreModel->store_id = $store_id;
        $dealStoreModel->created_at = date('Y-m-d H:i:s');
        if (!$dealStoreModel->save()) {
            die(json_encode($dealStoreModel->errors));
        }
        return true;
    }

    public function actionIndex() {
        return $this->render('index-v2', [
        ]);
    }

    public function actionImportStore() {
        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id, 'network_passphrase' => $this->api_pass_phrase])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            Yii::$app->session->setFlash('error', 'Network does not exist');
            return $this->redirect(['deal/index']);
        }
        $client = new \SoapClient('http://ws.tradetracker.com/soap/affiliate?wsdl', array('compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP));
        $client->authenticate($this->api_customer_id, $this->api_pass_phrase);
        $options = array(
            'assignmentStatus' => 'accepted',
        );
        $storeData = $client->getCampaigns($this->api_site_id, $options);
        $k = 0;
        $j = 0;
        $l = 0;
        if (!empty($storeData)) {
            foreach ($storeData as $store) {
                $storeModel = \app\models\Stores::find()
                        ->where(['api_store_id' => $store->ID, 'network_id' => $netWorkModel->network_id])
                        ->one();
                if (empty($storeModel)) {
                    $storeModel = new \app\models\Stores();
                    $storeModel->api_store_id = $store->ID;
                    $storeModel->name = $store->name;
                    $storeModel->store_url = $store->URL;
                    $storeModel->description = $store->info->campaignDescription;
                    $storeModel->store_logo = $store->info->imageURL;
                    $storeModel->network_id = $netWorkModel->network_id;
                    $storeModel->is_active = 1;
                    $storeModel->is_deleted = 0;
                    if ($storeModel->save()) {
                        $k++;
                        if (!empty($store->info->category)) {
                            $category = \app\models\Categories::find()
                                    ->where(['name' => $store->info->category->name])
                                    ->one();
                            if (empty($category)) {
                                $category = new \app\models\Categories();
                                $category->api_category_id = $storeModel->api_store_id;
                                $category->name = $store->info->category->name;
                                $category->created_at = date('Y-m-d H:i:s');
                                $category->updated_at = date('Y-m-d H:i:s');
                                $category->network_id = $netWorkModel->network_id;
                                $category->is_active = 1;
                                if ($category->save()) {
                                    $j++;
                                    if (!empty($store->info->subCategories)) {
                                        foreach ($store->info->subCategories as $subcat) {
                                            $subcategory = \app\models\Categories::find()
                                                    ->where(['name' => $subcat->name])
                                                    ->one();
                                            if (empty($subcategory)) {
                                                $subcategory = new \app\models\Categories();
                                                $subcategory->api_category_id = $subcat->ID;
                                                $subcategory->name = $subcat->name;
                                                $subcategory->created_at = date('Y-m-d H:i:s');
                                                $subcategory->updated_at = date('Y-m-d H:i:s');
                                                $subcategory->network_id = $netWorkModel->network_id;
                                                $subcategory->parent_id = $category->category_id;
                                                $subcategory->is_active = 1;
                                                if ($subcategory->save()) {
                                                    $l++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        Yii::$app->session->setFlash('success', 'Tradetracker: ' . $k . ' stores, ' . $j . ' categories and ' . $l . ' subcategories have been imported.');
        return $this->redirect(['trade-tracker/index']);
    }

    public function __actionIndex() {
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
                        if (empty($img)) {
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
