<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\base\Model;
/**
 * Description of ImportProductForm
 *
 * @author akram
 */
class ImportProductForm extends Model{
    //put your code here
    public $network_id;
    public $store_id;
    public $category_id;
    public $import_limit;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['store_id', 'import_limit'], 'required'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'network_id' => 'Network',
            'store_id' => 'Store',
            'category_id' => 'Category',
            'import_limit' => 'Import limit',
        ];
    }
}
