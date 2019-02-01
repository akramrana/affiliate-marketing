<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\UserIdentity;
use app\components\AccessRule;

class EffiliationController extends \yii\web\Controller {

    private $api_customer_id = '1395090664';
    private $api_passphrase = 'yofUp0hyBjdFid85AJUxXdgocgy7FpuU';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'import', 'import-store'],
                'rules' => [
                    [
                        'actions' => ['index', 'import', 'import-store'],
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
        return $this->render('index');
    }

    public function actionImportStore() {
        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id, 'network_passphrase' => $this->api_passphrase])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            Yii::$app->session->setFlash('error', 'Network does not exist');
            return $this->redirect(['deal/index']);
        } else {
            $ch = curl_init();
            $url = "https://apiv2.effiliation.com/apiv2/programs.json?key=" . $this->api_passphrase . "&filter=mines&lg=en";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($result);
            $k = 0;
            $j = 0;
            if (!empty($json->programs)) {
                foreach ($json->programs as $store) {
                    $storeModel = \app\models\Stores::find()
                            ->where(['api_store_id' => $store->id_programme, 'network_id' => $netWorkModel->network_id])
                            ->one();
                    if (empty($storeModel)) {
                        $storeModel = new \app\models\Stores();
                    }
                    $storeModel->api_store_id = $store->id_programme;
                    $storeModel->name = $store->nom;
                    $storeModel->store_url = $store->url;
                    $storeModel->description = $store->description;
                    $storeModel->store_logo = $store->urllo;
                    $storeModel->network_id = $netWorkModel->network_id;
                    $storeModel->is_active = 0;
                    $storeModel->is_deleted = 0;
                    if ($storeModel->save()) {
                        $k++;
                    }
                    //
                    $categories = explode(",", $store->categories);
                    if (!empty($categories)) {
                        foreach ($categories as $k => $val) {
                            $category = \app\models\Categories::find()
                                    ->where(['name' => $val])
                                    ->one();
                            if (empty($category)) {
                                $category = new \app\models\Categories();
                                $category->api_category_id = $store->id_programme;
                                $category->name = $val;
                                $category->created_at = date('Y-m-d H:i:s');
                                $category->updated_at = date('Y-m-d H:i:s');
                                $category->network_id = $netWorkModel->network_id;
                                if ($category->save()) {
                                    $j++;
                                }
                            }
                        }
                    }
                }
                Yii::$app->session->setFlash('success', 'Effiliation: ' . $k . ' stores and ' . $j . ' categories have been imported.');
                return $this->redirect(['effiliation/index']);
            }
        }
    }

    public function actionImport() {
        $username = $this->api_customer_id;
        $password = $this->api_passphrase;

        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id, 'network_passphrase' => $this->api_passphrase])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            Yii::$app->session->setFlash('error', 'Network does not exist');
            return $this->redirect(['deal/index']);
        } else {
            $ch = curl_init();
            $url = "https://apiv2.effiliation.com/apiv2/commercialtrades.json?key=" . $this->api_passphrase . "&filter=mines";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($result);
            $k = 0;
            if (!empty($json->supports)) {
                foreach ($json->supports as $coupon) {
                    $store_name = $coupon->nomprogramme;
                    $coupon_id = $coupon->id_lien;
                    $name = $coupon->nom;
                    $url_redir = $coupon->url_redir;
                    $program_id = $coupon->id_programme;
                    $description = $coupon->description;
                    $imageUrl = $coupon->url_logo;
                    $category_name = "";
                    $created_at = !empty($coupon->date_debut) ? $coupon->date_debut : "";
                    $valid_from_date = !empty($coupon->date_debut) ? $coupon->date_debut : "";
                    $valid_to_date = !empty($coupon->date_fin) ? $coupon->date_fin : "";
                    $coupon_code = "";
                    $exclusivite = ($coupon->exclusivite != "Non") ? "1" : "0";
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
                        $categories = \app\models\Categories::find()
                                ->where(['api_category_id' => $program_id])
                                ->all();
                        if (empty($categories)) {
                            continue;
                        }
                        // add coupon
                        $minOrderValue = '0.00';

                        $deal = new \app\models\Deals();
                        $deal->title = $name;
                        $deal->content = $description;
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
                        $deal->featured = $exclusivite;
                        $deal->minimum_order_value = $minOrderValue;
                        $deal->customer_restriction = "";
                        $deal->sys_user_ip = $_SERVER['REMOTE_ADDR'];
                        $deal->destination_url = $url_redir;
                        $deal->network_id = $netWorkModel->network_id;
                        $deal->extras = "";
                        $deal->save(false);
                        //
                        foreach ($categories as $cat) {
                            $dealCategory = new \app\models\DealCategories();
                            $dealCategory->deal_id = $deal->deal_id;
                            $dealCategory->category_id = $cat->category_id;
                            $dealCategory->created_at = date('Y-m-d H:i:s');
                            if (!$dealCategory->save()) {
                                die(json_encode($dealCategory->errors));
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
                        $k++;
                    }
                }
                Yii::$app->session->setFlash('success', 'Effiliation: ' . $k . ' coupons have been imported.');
                return $this->redirect(['deal/index']);
            }
        }
    }

}
