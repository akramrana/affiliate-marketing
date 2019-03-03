<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\UserIdentity;
use app\components\AccessRule;

define("WSDL_WS", "https://api.affili.net/V2.0/PublisherInbox.svc?wsdl");
define("WSDL_LOGON", "https://api.affili.net/V2.0/Logon.svc?wsdl");
define("WSDL_PROGRAM", "https://api.affili.net/V2.0/PublisherProgram.svc?wsdl");

class AffilinetController extends \yii\web\Controller {

    private $api_customer_id = '857276';
    private $api_passphrase = 'XDy9LnwOls9bH6AqlkZR';

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
                'only' => ['import'],
                'rules' => [
                    [
                        'actions' => ['import'],
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
        $username = $this->api_customer_id;
        $password = $this->api_passphrase;
        $page = 0;
        $maxPage = 0;
        $limit = 100;
        $i = 0;

        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id, 'network_passphrase' => $this->api_passphrase])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            Yii::$app->session->setFlash('error', 'Network does not exist');
            return $this->redirect(['deal/index']);
        }
        do {
            $page++;
            $coupons = $this->importAffiliVoucherCodes($page, $limit);
            $maxPage = ceil($coupons->TotalResults / $limit);
            //$maxPage = 2;
            $coupon_status = 0;
            if (!empty($coupons)) {
                //debugPrint($coupons);
                $programIds = [];
                $loadedCategories = new \stdClass();
                $loadedStores = new \stdClass();
                if (!empty($coupons->VoucherCodeCollection)) {
                    foreach ($coupons->VoucherCodeCollection->VoucherCodeItem as $coupon) {
                        if ($coupon->Title != "") {
                            $checkDeal = \app\models\Deals::find()
                                    ->where(['LIKE', 'title', $coupon->Title])
                                    ->one();
                        }
                        if (!empty($checkDeal)) {
                            
                        } else {
                            $soapLogon = new \SoapClient(WSDL_LOGON);
                            $token = $soapLogon->Logon(array(
                                'Username' => $username,
                                'Password' => $password,
                                'WebServiceType' => 'Publisher'
                            ));

                            // Set parameters
                            $displaySettings = array(
                                'PageSize' => 50,
                                'CurrentPage' => 1
                            );

                            $getProgramsQuery = array(
                                'PartnershipStatus' => array('Active'),
                                'ProgramIds' => [$coupon->ProgramId]
                            );

                            $soapRequest = new \SoapClient(WSDL_PROGRAM);
                            $categories = $soapRequest->GetProgramCategories($token);

                            $response = $soapRequest->GetPrograms(array(
                                'CredentialToken' => $token,
                                'DisplaySettings' => $displaySettings,
                                'GetProgramsQuery' => $getProgramsQuery
                            ));

                            $checkDeal = \app\models\Deals::find()
                                    ->where(['coupon_id' => $coupon->Id, 'network_id' => $netWorkModel->network_id])
                                    ->one();
                            if (!empty($checkDeal)) {
                                continue;
                            }

                            $expire_date_format = 'Y-m-d';
                            $expire_date = ( empty($coupon->EndDate) ) ? strtotime('+1 year') : str_replace("T", " ", $coupon->EndDate);
                            $expire_date = date($expire_date_format, strtotime($expire_date));

                            $arr = explode('>', $coupon->IntegrationCode);
                            $arr1 = explode('"', $arr[0]);
                            /**
                             * start store_id checking
                             */
                            $dealStore = \app\models\Stores::find()
                                    ->where(['api_store_id' => $coupon->ProgramId, 'network_id' => $netWorkModel->network_id])
                                    ->one();
                            $store_id = !empty($dealStore) ? $dealStore->store_id : null;
                            //debugPrint($response->ProgramCollection);
                            // create new store if not found
                            if ((empty($store_id))) {
                                if (!empty($response) && !empty($response->ProgramCollection->Program)) {
                                    $programTitle = $response->ProgramCollection->Program->ProgramTitle;
                                    $programID = $response->ProgramCollection->Program->ProgramId;
                                    $programURL = $response->ProgramCollection->Program->ProgramURL;
                                    $programDescription = $response->ProgramCollection->Program->ProgramDescription;
                                    $logoURL = $response->ProgramCollection->Program->LogoURL;
                                    $dealStore = \app\models\Stores::find()
                                            ->where(['name' => $programTitle])
                                            ->one();
                                    if (empty($dealStore) && $netWorkModel->create_store == 1) {
                                        $dealStore = new \app\models\Stores();
                                        $dealStore->api_store_id = $programID;
                                        $dealStore->name = $programTitle;
                                        $dealStore->store_url = $programURL;
                                        $dealStore->description = $programDescription;
                                        $dealStore->store_logo = $logoURL;
                                        $dealStore->network_id = $netWorkModel->network_id;
                                        $dealStore->is_active = 0;
                                        $dealStore->is_deleted = 0;
                                        if ($dealStore->save()) {
                                            $store_id = $dealStore->store_id;
                                        }
                                    } else {
                                        $store_id = $dealStore->store_id;
                                    }

                                    $destination_url = str_replace('_0_', '_' . $coupon->Id . '_', $programURL);
                                    $destination_url = str_replace('&m=0', '&m=' . $coupon->Id, $destination_url);
                                    $destination_url = str_replace('_&r=', '_' . $coupon->Id . '&r=', $destination_url);
                                    $destination_url = str_replace('&r=&', '&r=' . $coupon->Id . '&', $destination_url);
                                } else {
                                    $store_id = null;
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
                            //get coupon categories
                            $couponCategories = !empty($response->ProgramCollection->Program->ProgramCategoryIds->int) ? $response->ProgramCollection->Program->ProgramCategoryIds->int : [];
                            //load all store categories recursively
                            $allCategories = [];
                            if (!empty($categories->RootCategories->ProgramCategory)) {
                                foreach ($categories->RootCategories->ProgramCategory as $pc) {
                                    if ($pc->CategoryId != "") {
                                        $allCategories[$pc->CategoryId] = $pc->Name;
                                        if (!empty($pc->SubCategories->ProgramCategory)) {
                                            if (count($pc->SubCategories->ProgramCategory) < 2) {
                                                $allCategories[$pc->SubCategories->ProgramCategory->CategoryId] = $pc->SubCategories->ProgramCategory->Name;
                                            } else {
                                                foreach ($pc->SubCategories->ProgramCategory as $spc) {
                                                    if ($spc->CategoryId != "") {
                                                        $allCategories[$spc->CategoryId] = $spc->Name;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $categoriesArray = [];
                            $termsArray = [];
                            /**
                             * start category_id checking
                             */
                            foreach ($allCategories as $key => $val) {
                                if (in_array($key, $couponCategories)) {
                                    $category = \app\models\Categories::find()
                                            ->where(['api_category_id' => $key, 'network_id' => $netWorkModel->network_id])
                                            ->one();
                                    if (!empty($category)) {
                                        $category_id = $category->category_id;
                                    } else {
                                        $category_id = null;
                                    }
                                }
                                if (empty($category_id)) {
                                    if (in_array($key, $couponCategories)) {
                                        $category = \app\models\Categories::find()
                                                ->where(['name' => $val])
                                                ->one();
                                        if (empty($category) && $netWorkModel->create_category == 1) {
                                            $category = new \app\models\Categories();
                                            $category->api_category_id = $key;
                                            $category->name = $val;
                                            $category->created_at = date('Y-m-d H:i:s');
                                            $category->updated_at = date('Y-m-d H:i:s');
                                            $category->network_id = $netWorkModel->network_id;
                                            if ($category->save()) {
                                                $category_id = $category->category_id;
                                                $termsArray[] = $val;
                                            }
                                        } else {
                                            $category_id = $category->category_id;
                                            $termsArray[] = $val;
                                        }
                                    } else {
                                        $category_id = null;
                                    }
                                }
                                if (!empty($category_id)) {
                                    if (!in_array($category_id, $categoriesArray)) {
                                        array_push($categoriesArray, $category_id);
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
                            $minOrderValue = ($coupon->MinimumOrderValue != "") ? $coupon->MinimumOrderValue : '0.00';
                            $couponDescription = $coupon->Description . '<br/><br/><br/>'
                                    . 'Condition:<br/>'
                                    . 'Min Order Value: €' . $minOrderValue . '<br/>'
                                    . 'Customer Restriction: ' . $coupon->CustomerRestriction . '<br/>';

                            $deal = new \app\models\Deals();
                            $deal->title = $coupon->Title;
                            $deal->content = $couponDescription;
                            $deal->is_active = $netWorkModel->auto_publish;
                            $deal->is_deleted = 0;
                            $deal->coupon_id = $coupon->Id;
                            $deal->program_id = $coupon->ProgramId;
                            $deal->coupon_code = $coupon->Code;
                            if (!empty($coupon->Code)) {
                                $type = 'V';
                            } else {
                                $type = 'P';
                            }
                            $deal->voucher_types = $type;
                            $deal->start_date = $coupon->StartDate;
                            $deal->end_date = $coupon->EndDate;
                            $deal->expire_date = $expire_date;
                            $deal->last_change_date = $coupon->LastChangeDate;
                            $deal->partnership_status = $coupon->PartnershipStatus;
                            $deal->integration_code = $coupon->IntegrationCode;
                            $deal->featured = $coupon->IsExclusive;
                            $deal->minimum_order_value = $minOrderValue;
                            $deal->customer_restriction = $coupon->CustomerRestriction;
                            $deal->sys_user_ip = $_SERVER['REMOTE_ADDR'];
                            $deal->destination_url = !empty($destination_url) ? $destination_url : "";
                            $deal->network_id = $netWorkModel->network_id;
                            $deal->extras = !empty($coupon->VoucherTypes->VoucherType) ? json_encode($coupon->VoucherTypes->VoucherType) : "";
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
                            //
                            array_push($programIds, $coupon->ProgramId);
                            $i++;
                            //if imported total is as defined in setting stop execution
                            if ($i >= 5) {
                                break 2;
                            }
                        }
                    }
                }
            }
        } while ($page < $maxPage);

        Yii::$app->session->setFlash('success', 'Affili: ' . $i . ' coupons have been imported.');
        return $this->redirect(['deal/index']);
    }

    private function importAffiliVoucherCodes($page, $limit) {

        $username = $this->api_customer_id;
        $password = $this->api_passphrase;

        $soapLogon = new \SoapClient(WSDL_LOGON);
        $token = $soapLogon->Logon(array(
            'Username' => $username,
            'Password' => $password,
            'WebServiceType' => 'Publisher'
        ));

        $displaySettings = array(
            'CurrentPage' => $page,
            'PageSize' => $limit,
            'SortBy' => 'LastChangeDate',
            'SortOrder' => 'Descending'
        );

        $params = array(
            'StartDate' => strtotime("now"),
            'EndDate' => strtotime("now"),
            'VoucherCodeContent' => 'Any',
            'ProgramPartnershipStatus' => 'Accepted'
        );
        $soapRequest = new \SoapClient(WSDL_WS);
        $response = $soapRequest->SearchVoucherCodes(array(
            'CredentialToken' => $token,
            'DisplaySettings' => $displaySettings,
            'SearchVoucherCodesRequestMessage' => $params
        ));

        return $response;
    }

}
