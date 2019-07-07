<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
/**
 * Description of CjController
 *
 * @author akram
 */
class CjController extends Controller{
    //put your code here
    private $api_customer_id = '5209964';
    
    public function actionIndex() {
        $model = new \app\models\ExcelUpload();
        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            return ExitCode::NOUSER;
        }
        
        $promoTypes = [
            'coupon' => 'coupon',
            'sweepstakes' => 'sweepstakes',
            'product' => 'product',
            'sale/discount' => 'sale/discount',
            'free shipping' => 'free shipping',
            'seasonal link' => 'seasonal link',
            'site to store' => 'site to store'
        ];
        $k = 0;
        foreach ($promoTypes as $promptype) {
            $api_url = 'https://link-search.api.cj.com/v2/link-search?website-id=8982498&advertiser-ids=joined&promotion-type=' . $promptype . '&records-per-page=100';
            $headers = array(
                "Authorization: Bearer 6tnkb4da5hpy4hjz0ktdjxd8q6",
            );
            $chl = curl_init();
            curl_setopt($chl, CURLOPT_URL, $api_url);
            curl_setopt($chl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($chl, CURLOPT_HTTPHEADER, $headers);
            $resultl = curl_exec($chl);
            curl_close($chl);
            libxml_use_internal_errors(true);
            $cXMLl = simplexml_load_string($resultl);
            if ($cXMLl) {
                $jsonl = json_encode($cXMLl);
                $jDatal = json_decode($jsonl, true);
                if (!empty($jDatal['links']['link'])) {
                    foreach ($jDatal['links']['link'] as $link) {
                        $ADVERTISER = !empty($link['advertiser-name'])?$link['advertiser-name']:"";
                        $TARGETED_COUNTRIES = "";
                        $LINK_ID = !empty($link['link-id'])?$link['link-id']:"";
                        $NAME = !empty($link['link-name'])?$link['link-name']:"";
                        $DESCRIPTION = !empty($link['description'])?$link['description']:"";
                        $KEYWORDS = "";
                        $LINK_TYPE = !empty($link['link-type'])?$link['link-type']:"";
                        $LAST_UDPATED = "";
                        $HTML_LINKS = !empty($link['link-code-html'])?$link['link-code-html']:"";
                        $CLICK_URL = !empty($link['clickUrl'])?$link['clickUrl']:"";
                        $PROMOTION_TYPE = !empty($link['promotion-type'])?$link['promotion-type']:"";
                        $COUPON_CODE = !empty($link['coupon-code'])?$link['coupon-code']:"";
                        $PROMOTIONAL_DATE = !empty($link['promotion-start-date'])?$link['promotion-start-date']:"";
                        $PROMOTIONAL_END_DATE = !empty($link['promotion-end-date'])?$link['promotion-end-date']:"";
                        $CATEGORY = !empty($link['category'])?$link['category']:"";
                        $ADV_CID = !empty($link['advertiser-id'])?$link['advertiser-id']:"";
                        $RELATIONSHIP_STATUS = !empty($link['relationship-status'])?$link['relationship-status']:"";
                        $imgUrl = '';
                        $srcArr = explode('<a href="', $HTML_LINKS);
                        $urlArr = !empty($srcArr[1]) ? explode('"', $srcArr[1]) : "";
                        $imgUrl = !empty($urlArr) ? $urlArr[0] : "";
                        if ($LINK_TYPE == "Banner") {
                            continue;
                        }
                        //
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
                            $category = \app\models\Categories::find()
                                    ->where(['name' => $CATEGORY])
                                    ->one();
                            if (empty($category) && $netWorkModel->create_category == 1) {
                                $category = new \app\models\Categories();
                                $category->api_category_id = $ADV_CID;
                                $category->name = $CATEGORY;
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
                            //$deal->sys_user_ip = $_SERVER['REMOTE_ADDR'];
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
                }
            }
        }
        return ExitCode::OK;
    }
}
