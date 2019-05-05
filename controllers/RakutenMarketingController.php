<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\components\UserIdentity;
use app\components\AccessRule;

class RakutenMarketingController extends \yii\web\Controller {

    private $api_customer_id = '3628251';

    /**
     * {@inheritdoc}
     */
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
        $k = 0;
        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            Yii::$app->session->setFlash('error', 'Network does not exist');
            return $this->redirect(['deal/index']);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rakutenmarketing.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=password&username=akram1991&password=Akram123%24&scope=3628251&client_id=RlotJdtHHAUaOloza3xBSC9W2e8a&client_secret=22_RPM0CzMpRCqX6kMqv9GBOIvAa&undefined=",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "Token: 69a27596-1458-4511-b791-8ca5b4bdcb18",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $result = json_decode($response);
            $curl1 = curl_init();
            curl_setopt_array($curl1, array(
                CURLOPT_URL => "https://api.rakutenmarketing.com/coupon/1.0",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $result->access_token",
                    "Token: 5a5b174e-2d5e-4294-a7eb-4b5622b74aa4",
                    "cache-control: no-cache"
                ),
            ));
            $response1 = curl_exec($curl1);
            $err1 = curl_error($curl1);
            curl_close($curl1);
            if ($err) {
                echo "cURL Error #:" . $err1;
            } else {
                libxml_use_internal_errors(true);
                $cXMLl = simplexml_load_string($response1);
                if ($cXMLl) {
                    $jsonl = json_encode($cXMLl);
                    $jDatal = json_decode($jsonl, true);
                    //debugPrint($jDatal);exit;
                    if (!empty($jDatal['link'])) {
                        foreach ($jDatal['link'] as $link) {
                            $ADVERTISER = !empty($link['advertisername']) ? $link['advertisername'] : "";
                            $TARGETED_COUNTRIES = "";
                            $LINK_ID = rand(11111111, 99999999);
                            $NAME = !empty($link['offerdescription']) ? $link['offerdescription'] : "";
                            $DESCRIPTION = !empty($link['offerdescription']) ? $link['offerdescription'] : "";
                            $KEYWORDS = "";
                            $LINK_TYPE = !empty($link['@attributes']['type']) ? $link['@attributes']['type'] : "";
                            $LAST_UDPATED = "";
                            $HTML_LINKS = "";
                            $CLICK_URL = !empty($link['clickurl']) ? $link['clickurl'] : "";
                            $PROMOTION_TYPE = !empty($link['promotiontypes']['promotiontype']) ? $link['promotiontypes']['promotiontype'] : "";
                            $COUPON_CODE = !empty($link['couponcode']) ? $link['couponcode'] : "";
                            $PROMOTIONAL_DATE = !empty($link['offerstartdate']) ? $link['offerstartdate'] : "";
                            $PROMOTIONAL_END_DATE = !empty($link['offerenddate']) ? $link['offerenddate'] : "";
                            //$CATEGORY = !empty($link['category']) ? $link['category'] : "";
                            $ADV_CID = !empty($link['advertiserid']) ? $link['advertiserid'] : "";
                            $RELATIONSHIP_STATUS = "Accepted";
                            //
                            if ($LINK_TYPE == "Banner") {
                                continue;
                            }
                            $checkDeal = \app\models\Deals::find()
                                    ->where(['LIKE', 'title', $NAME])
                                    ->one();
                            if (empty($checkDeal)) {
                                $checkDeal = \app\models\Deals::find()
                                        ->where(['coupon_id' => $LINK_ID, 'network_id' => $netWorkModel->network_id])
                                        ->one();
                                if (!empty($checkDeal)) {
                                    continue;
                                }
                                $expire_date = empty($PROMOTIONAL_END_DATE) ? date('Y-m-d H:i:s', strtotime('+1 year')) : date('Y-m-d H:i:s', strtotime($PROMOTIONAL_END_DATE));
                                /**
                                 * start store_id checking
                                 */
                                $dealStore = \app\models\Stores::find()
                                        ->where(['api_store_id' => $ADV_CID, 'network_id' => $netWorkModel->network_id])
                                        ->one();
                                $store_id = !empty($dealStore) ? $dealStore->store_id : null;
                                // create new store if not found
                                if ((empty($store_id))) {
                                    $dealStore = \app\models\Stores::find()
                                            ->where(['name' => $ADVERTISER])
                                            ->one();
                                    if (empty($dealStore) && $netWorkModel->create_store == 1) {
                                        $dealStore = new \app\models\Stores();
                                        $dealStore->api_store_id = $ADV_CID;
                                        $dealStore->name = $ADVERTISER;
                                        $dealStore->store_url = "";
                                        $dealStore->description = "";
                                        $dealStore->store_logo = "";
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
                                $categoriesArray = [];
                                if (!empty($link['categories']['category'])) {
                                    if (@sizeof($link['categories']['category']) > 1) {
                                        foreach ($link['categories']['category'] as $categoryName) {
                                            $category = \app\models\Categories::find()
                                                    ->where(['name' => $categoryName])
                                                    ->one();
                                            if (empty($category) && $netWorkModel->create_category == 1) {
                                                $category = new \app\models\Categories();
                                                $category->api_category_id = $ADV_CID;
                                                $category->name = $categoryName;
                                                $category->created_at = date('Y-m-d H:i:s');
                                                $category->updated_at = date('Y-m-d H:i:s');
                                                $category->network_id = $netWorkModel->network_id;
                                                if ($category->save()) {
                                                    $category_id = $category->category_id;
                                                }
                                            } else {
                                                $category_id = $category->category_id;
                                            }
                                            if (!empty($category_id)) {
                                                if (!in_array($category_id, $categoriesArray)) {
                                                    array_push($categoriesArray, $category_id);
                                                }
                                            }
                                        }
                                    } else {
                                        $categoryName = $link['categories']['category'];
                                        $category = \app\models\Categories::find()
                                                ->where(['name' => $categoryName])
                                                ->one();
                                        if (empty($category) && $netWorkModel->create_category == 1) {
                                            $category = new \app\models\Categories();
                                            $category->api_category_id = $ADV_CID;
                                            $category->name = $categoryName;
                                            $category->created_at = date('Y-m-d H:i:s');
                                            $category->updated_at = date('Y-m-d H:i:s');
                                            $category->network_id = $netWorkModel->network_id;
                                            if ($category->save()) {
                                                $category_id = $category->category_id;
                                            }
                                        } else {
                                            $category_id = $category->category_id;
                                        }
                                        if (!empty($category_id)) {
                                            if (!in_array($category_id, $categoriesArray)) {
                                                array_push($categoriesArray, $category_id);
                                            }
                                        }
                                    }
                                }
                                if (empty($categoriesArray)) {
                                    continue;
                                }
                                /**
                                 * end category_id checking
                                 */
                                // add coupon
                                $minOrderValue = '0.00';

                                $deal = new \app\models\Deals();
                                $deal->title = $NAME;
                                $deal->content = $DESCRIPTION;
                                //$deal->image_url = $imgUrl;
                                $deal->is_active = $netWorkModel->auto_publish;
                                $deal->is_deleted = 0;
                                $deal->coupon_id = $LINK_ID;
                                $deal->program_id = $ADV_CID;
                                $deal->coupon_code = $COUPON_CODE;
                                if (!empty($COUPON_CODE)) {
                                    $type = 'V';
                                } else {
                                    $type = 'P';
                                }
                                $deal->voucher_types = $type;
                                $deal->start_date = empty($PROMOTIONAL_DATE) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($PROMOTIONAL_DATE));
                                $deal->end_date = empty($PROMOTIONAL_END_DATE) ? date('Y-m-d H:i:s', strtotime('+1 year')) : date('Y-m-d H:i:s', strtotime($PROMOTIONAL_END_DATE));
                                $deal->expire_date = $expire_date;
                                $deal->last_change_date = date('Y-m-d H:i:s', strtotime($LAST_UDPATED));
                                $deal->partnership_status = $RELATIONSHIP_STATUS;
                                $deal->integration_code = $HTML_LINKS;
                                $deal->featured = 0;
                                $deal->minimum_order_value = $minOrderValue;
                                $deal->customer_restriction = !empty($TARGETED_COUNTRIES) ? $TARGETED_COUNTRIES . ' countries customers only' : "";
                                $deal->sys_user_ip = $_SERVER['REMOTE_ADDR'];
                                $deal->destination_url = $CLICK_URL;
                                $deal->network_id = $netWorkModel->network_id;
                                $extras = [
                                    'keywords' => $KEYWORDS,
                                    'link_type' => $LINK_TYPE,
                                    'promotion_type' => $PROMOTION_TYPE,
                                ];
                                $deal->extras = !empty($extras) ? json_encode($extras) : "";
                                //$deal->image_url = $imgUrl;
                                $deal->save(false);
                                //
                                foreach ($categoriesArray as $key => $val) {
                                    $dealCategory = new \app\models\DealCategories();
                                    $dealCategory->deal_id = $deal->deal_id;
                                    $dealCategory->category_id = $val;
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
                    }
                }
            }
        }
        Yii::$app->session->setFlash('success', 'Rakuten: ' . $k . ' coupons have been imported.');
        return $this->redirect(['deal/index']);
    }

}
