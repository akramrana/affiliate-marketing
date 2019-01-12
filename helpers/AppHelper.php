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
    static function getAllCategories($exclude_id="")
    {
        $query = \app\models\Categories::find()
                ->where(['is_deleted' => 0,'is_active' => 1])
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
    
    static function getAllNetwork()
    {
        $model = \app\models\Networks::find()->where(['is_active' => 1,'is_deleted' => 0])->all();
        $list = \yii\helpers\ArrayHelper::map($model, 'network_id', 'network_name');
        return $list;
    }
    
    static function getStoresAsProgram()
    {
        $model = \app\models\Stores::find()->where(['is_deleted' => 0])->all();
        $list = \yii\helpers\ArrayHelper::map($model, 'api_store_id', 'name');
        return $list;
    }
}
