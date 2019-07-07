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
 * Description of EffiliationController
 *
 * @author akram
 */
class EffiliationController extends Controller{
    //put your code here
    private $api_customer_id = '1395090664';
    private $api_passphrase = 'yofUp0hyBjdFid85AJUxXdgocgy7FpuU';
    
    public function actionIndex()
    {
        $netWorkModel = \app\models\Networks::find()
                ->where(['network_customer_id' => $this->api_customer_id, 'network_passphrase' => $this->api_passphrase])
                ->andWhere(['is_deleted' => 0, 'is_active' => 1])
                ->one();
        if (empty($netWorkModel)) {
            return ExitCode::NOUSER;
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
                            ->where(['api_store_id' => $store->id_affilieur, 'network_id' => $netWorkModel->network_id])
                            ->one();
                    if (empty($storeModel)) {
                        $storeModel = new \app\models\Stores();
                    }
                    $storeModel->api_store_id = $store->id_affilieur;
                    $storeModel->name = $store->nom;
                    $storeModel->store_url = $store->url;
                    $storeModel->description = $store->description;
                    $storeModel->store_logo = $store->urllo;
                    $storeModel->network_id = $netWorkModel->network_id;
                    $storeModel->is_active = 1;
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
                                $category->api_category_id = $store->id_affilieur;
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
                return ExitCode::OK;
            }
        }
        
    }
}
