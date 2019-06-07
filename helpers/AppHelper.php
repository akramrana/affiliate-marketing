<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppHelper
 *
 * @author akram
 */

namespace app\helpers;

use Yii;

class AppHelper {

    //put your code here
    static function getAllCategories($exclude_id = "") {
        $query = \app\models\Categories::find()
                ->where(['is_deleted' => 0, 'is_active' => 1])
                ->andWhere(['IS', 'parent_id', new \yii\db\Expression('NULL')]);
        if ($exclude_id != "") {
            $query->andWhere(['!=', 'category_id', $exclude_id]);
        }
        $roots = $query->all();
        $result = [];
        foreach ($roots as $row) {
            $d['category_id'] = $row->category_id;
            $d['name'] = $row->name;
            $d['children'] = self::getChildCategoryList($row, $exclude_id);
            array_push($result, $d);
        }
        //$it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($result));
        $nonested = self::makeNonNestedCategoryArray($result);
        $list = \yii\helpers\ArrayHelper::map($nonested, 'id', 'name');
        return $list;
    }

    static function makeNonNestedCategoryArray($data, $pass = 0) {
        $result = [];
        foreach ($data as $key => $value) {
            $d = [
                'id' => $value['category_id'],
                'name' => $value['name'],
            ];
            array_push($result, $d);
            if (array_key_exists('children', $value)) {
                $nested = self::makeNonNestedCategoryArray($value['children'], $pass);
                if (!empty($nested)) {
                    $count = $pass + 1;
                    foreach ($nested as $n) {
                        $n = [
                            'id' => $n['id'],
                            'name' => str_repeat("--", $count) . $n['name']
                        ];
                        array_push($result, $n);
                    }
                }
            }
        }
        return $result;
    }

    static function getChildCategoryList($parent, $exclude_id = "") {
        $query = \app\models\Categories::find()
                ->where(['is_deleted' => 0, 'is_active' => 1, 'parent_id' => $parent->category_id]);
        if ($exclude_id != "") {
            $query->andWhere(['!=', 'category_id', $exclude_id]);
        }
        $children = $query->all();
        $result = [];
        foreach ($children as $row) {
            $d['category_id'] = $row->category_id;
            $d['name'] = $row->name;
            $countQuery = \app\models\Categories::find()
                    ->where(['is_deleted' => 0, 'is_active' => 1, 'parent_id' => $row->category_id]);
            if ($exclude_id != "") {
                $countQuery->andWhere(['!=', 'category_id', $exclude_id]);
            }
            $count = $countQuery->count();
            if ($count > 0) {
                $d['children'] = self::getChildCategoryList($row);
            }
            array_push($result, $d);
        }
        return $result;
    }

    static function getAllNetwork() {
        $model = \app\models\Networks::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();
        $list = \yii\helpers\ArrayHelper::map($model, 'network_id', 'network_name');
        return $list;
    }

    static function getStoresAsProgram() {
        $model = \app\models\Stores::find()->where(['is_deleted' => 0])->all();
        $list = \yii\helpers\ArrayHelper::map($model, 'api_store_id', 'name');
        return $list;
    }

    static function getStores($limit) {
        $model = \app\models\Stores::find()
                ->select(['stores.*', '(
                    SELECT count(deal_stores.deal_store_id) 
                    FROM deal_stores 
                    LEFT JOIN deals ON deal_stores.deal_id = deals.deal_id
                    WHERE deal_stores.store_id = stores.store_id AND deals.is_active = 1 AND deals.is_deleted = 0 AND DATE(deals.end_date) >= "' . date('Y-m-d') . '"
                ) as no_of_deal'])
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->having(['>', 'no_of_deal', 0])
                ->limit($limit)
                ->orderBy(['no_of_deal' => SORT_DESC])
                ->all();
        return $model;
    }

    static function getCategories($limit) {
        $model = \app\models\Categories::find()
                ->select(['categories.*', '(
                    SELECT count(deal_categories.deal_category_id)
                    FROM  deal_categories 
                    LEFT JOIN deals ON deal_categories.deal_id = deals.deal_id
                    WHERE deal_categories.category_id = categories.category_id AND deals.is_active = 1 AND deals.is_deleted = 0 AND DATE(deals.end_date) >= "' . date('Y-m-d') . '"
                 ) as no_of_deal'])
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->having(['>', 'no_of_deal', 0])
                ->limit($limit)
                ->orderBy(['no_of_deal' => SORT_DESC])
                ->all();
        return $model;
    }

    static function getCreativeAds($limit) {
        $model = \app\models\CreativeAds::find()
                ->where(['is_deleted' => 0])
                ->limit($limit)
                ->orderBy(['creative_ad_id' => SORT_DESC])
                ->all();
        return $model;
    }

    static function getRandomCreativeAds($limit) {
        $model = \app\models\CreativeAds::find()
                ->where(['is_deleted' => 0])
                ->limit($limit)
                ->orderBy("RAND()")
                ->all();
        return $model;
    }

    static function getDealsBenner($limit) {
        $model = \app\models\DealBanners::find()
                ->where(['is_deleted' => 0, 'is_active' => 1])
                ->limit($limit)
                ->orderBy("RAND()")
                ->all();
        return $model;
    }

    static function getPartnershipStatus() {
        $model = \app\models\Deals::find()
                ->select(['DISTINCT(partnership_status) as partnership_status'])
                ->orderBy(['partnership_status' => SORT_ASC])
                ->all();
        $list = \yii\helpers\ArrayHelper::map($model, 'partnership_status', 'partnership_status');
        return $list;
    }

    static function getAllStores() {
        $model = \app\models\Stores::find()->where(['is_deleted' => 0])->all();
        $list = \yii\helpers\ArrayHelper::map($model, 'store_id', 'name');
        return $list;
    }

    static function getStoreByNetwork($network_id) {
        $model = \app\models\Stores::find()
                ->where(['is_deleted' => 0, 'network_id' => $network_id])
                ->all();
        $list = \yii\helpers\ArrayHelper::map($model, 'api_store_id', 'name');
        return $list;
    }
    
    static function getCategoryByNetworkV2($network_id) {
        $model = \app\models\Categories::find()
                ->where(['is_deleted' => 0, 'network_id' => $network_id])
                ->all();
        $list = \yii\helpers\ArrayHelper::map($model, 'api_category_id', 'name');
        return $list;
    }

    static function getCategoryByNetwork($network_id) {
        $categories = array(
            '34' => 'Adult',
            '7' => 'Animals',
            '20' => 'Art and living',
            '2' => 'Baby & kids',
            '4' => 'Boats and watercraft',
            '24' => 'Books, papers and magazines',
            '1' => 'Cars, motorcycles and bikes',
            '16' => 'Charity',
            '227' => 'Daily offers and group deals',
            '6' => 'Dating',
            '36' => 'Department stores',
            '8' => 'Domain names and hosting',
            '9' => 'Email marketing',
            '30' => 'Employment, education and career',
            '35' => 'Entertainment and leisure',
            '120' => 'Family',
            '22' => 'Fashion and jewelry',
            '13' => 'Financial products',
            '3' => 'Flowers',
            '11' => 'Food and beverages',
            '17' => 'Free services and prize contests',
            '14' => 'Games and fun',
            '5' => 'Gifts and gadgets',
            '18' => 'Hard and software',
            '15' => 'Health and beauty',
            '38' => 'Hobby and leisure time',
            '31' => 'Home and garden',
            '10' => 'Household appliances',
            '37' => 'Legal',
            '21' => 'Lottery and gaming',
            '23' => 'Music, video and DVD',
            '19' => 'Office',
            '39' => 'Other',
            '12' => 'Party supplies',
            '25' => 'Personal internet services',
            '32' => 'Professional services',
            '28' => 'Sport and recreation',
            '29' => 'Telecommunication',
            '27' => 'Toys',
            '26' => 'Travel and holidays',
        );
        return $categories;
    }

    static function getStoreWithProductCount()
    {
        $model = \app\models\Products::find()
                ->select([
                    'advertiser_name',
                    'COUNT(products.product_id) AS num_product',
                    'store_id'
                ])
                ->where('advertiser_name!=""')
                ->having(['>','num_product',0])
                ->groupBy('advertiser_name')
                ->orderBy(['advertiser_name' => SORT_ASC])
                ->asArray()
                ->all();
        return $model;
    }
}
