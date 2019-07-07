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
 * Description of TtController
 *
 * @author akram
 */
class TtController extends Controller{
    
    private $api_customer_id = '182121';
    private $api_pass_phrase = '29cbe2fa70636a22e98cba80ae58f33aa971ea57';
    private $api_site_id = '324798';
    
    public function actionIndex() {
        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id, 'network_passphrase' => $this->api_pass_phrase])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            return ExitCode::NOUSER;
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
        return ExitCode::OK;
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
        //$deal->sys_user_ip = $_SERVER['REMOTE_ADDR'];
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
}
