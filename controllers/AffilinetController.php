<?php

namespace app\controllers;

define("WSDL_WS", "https://api.affili.net/V2.0/PublisherInbox.svc?wsdl");
define("WSDL_LOGON", "https://api.affili.net/V2.0/Logon.svc?wsdl");
define("WSDL_PROGRAM", "https://api.affili.net/V2.0/PublisherProgram.svc?wsdl");

class AffilinetController extends \yii\web\Controller {

    private $api_customer_id = '854690';
    private $api_passphrase = '1J31vp0ZPo3ZplTfdAvg';

    public function actionImport() {
        $username = $this->api_customer_id;
        $password = $this->api_passphrase;
        $page = 0;
        $maxPage = 0;
        $limit = 100;
        $i = 0;
        do {
            $page++;
            $coupons = $this->importAffiliVoucherCodes($page, $limit);
            $maxPage = ceil($coupons->TotalResults / $limit);
            //$maxPage = 2;
            $coupon_status = 0;
            if (!empty($coupons)) {
                $programIds = [];
                $loadedCategories = new \stdClass();
                $loadedStores = new \stdClass();
                if (!empty($coupons->VoucherCodeCollection)) {
                    foreach ($coupons->VoucherCodeCollection->VoucherCodeItem as $coupon) {
                        $checkDeal = \app\models\Deals::find()
                                ->where(['LIKE', 'title', $coupon->Title])
                                ->one();
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
                                'PartnershipStatus' => array('Active', 'Paused', 'Waiting', 'Refused', 'NoPartnership'),
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
                                    ->where(['coupon_id' => $coupon->Id, 'network_id' => 1])
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
                                    ->where(['api_store_id' => $coupon->ProgramId, 'network_id' => 1])
                                    ->one();
                            $store_id = !empty($dealStore) ? $dealStore->api_store_id : null;
                            //debugPrint($response->ProgramCollection);
                            // create new store if not found
                            if ((empty($store_id))) {
                                if (!empty($response) && !empty($response->ProgramCollection->Program)) {
                                    $programTitle = $response->ProgramCollection->Program->ProgramTitle;
                                    $programID = $response->ProgramCollection->Program->ProgramId;
                                    $programURL = $response->ProgramCollection->Program->ProgramURL;
                                    $programDescription = $response->ProgramCollection->Program->ProgramDescription;
                                    $logoURL = $response->ProgramCollection->Program->LogoURL;
                                    $dealStore = new \app\models\Stores();
                                    $dealStore->api_store_id = $programID;
                                    $dealStore->name = $programTitle;
                                    $dealStore->store_url = $programURL;
                                    $dealStore->description = $programDescription;
                                    $dealStore->store_logo = $logoURL;
                                    $dealStore->network_id = 1;
                                    $dealStore->is_active = 0;
                                    $dealStore->is_deleted = 0;
                                    if ($dealStore->save()) {
                                        $store_id = $dealStore->api_store_id;
                                    }
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
                            //if imported total is as defined in setting stop execution
                            if ($i >= 5) {
                                break 2;
                            }
                            //get coupon categories
                            $couponCategories = $response->ProgramCollection->Program->ProgramCategoryIds->int;
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
                                            ->where(['api_category_id' => $key, 'network_id' => 1])
                                            ->one();
                                    if (!empty($category)) {
                                        $category_id = $category->api_category_id;
                                    } else {
                                        $category_id = null;
                                    }
                                } else {
                                    $category_id = null;
                                }
                                if (empty($category_id)) {
                                    if (in_array($key, $couponCategories)) {
                                        $category = new \app\models\Categories();
                                        $category->api_category_id = $key;
                                        $category->name = $val;
                                        $category->created_at = date('Y-m-d H:i:s');
                                        $category->updated_at = date('Y-m-d H:i:s');
                                        $category->network_id = 1;
                                        if ($category->save()) {
                                            $category_id = $category->api_category_id;
                                            $termsArray[] = $val;
                                        }
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

                            foreach ($categoriesArray as $key => $val) {
                                
                            }
                            array_push($programIds, $coupon->ProgramId);
                            $i++;
                        }
                    }
                }
            }
        } while ($page < $maxPage);
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
                //'ProgramPartnershipStatus' => 'Accepted'
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
