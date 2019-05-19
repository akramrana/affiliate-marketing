<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $product_id
 * @property int $network_id
 * @property string $feed_id
 * @property string $name
 * @property double $price
 * @property double $retail_price
 * @property double $sale_price
 * @property string $currency
 * @property string $buy_url
 * @property string $description
 * @property string $advertiser_name
 * @property int $is_stock
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property ProductCategories[] $productCategories
 * @property ProductImages[] $productImages
 * @property Networks $network
 */
class Products extends \yii\db\ActiveRecord {

    public $categories_id, $image_url;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['network_id', 'name', 'price', 'retail_price', 'sale_price', 'currency', 'buy_url', 'advertiser_name', 'categories_id', 'image_url'], 'required'],
            [['network_id', 'is_stock', 'is_active', 'is_deleted'], 'integer'],
            [['price', 'retail_price', 'sale_price'], 'number'],
            [['buy_url', 'description'], 'string'],
            [['feed_id', 'currency', 'advertiser_name'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['additional_info', 'image_url', 'categories_id', 'is_featured', 'store_id'], 'safe'],
            [['network_id'], 'exist', 'skipOnError' => true, 'targetClass' => Networks::className(), 'targetAttribute' => ['network_id' => 'network_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'product_id' => 'Product ID',
            'network_id' => 'Network',
            'feed_id' => 'Feed ID',
            'name' => 'Name',
            'price' => 'Price',
            'retail_price' => 'Retail Price',
            'sale_price' => 'Sale Price',
            'currency' => 'Currency',
            'buy_url' => 'Buy Url',
            'description' => 'Description',
            'advertiser_name' => 'Advertiser Name',
            'is_stock' => 'Is Stock',
            'store_id' => 'Store',
            'is_featured' => 'Featured?',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'categories_id' => 'Categories',
            'image_url' => 'Image Url',
            'additional_info' => 'Additional Info',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories() {
        return $this->hasMany(ProductCategories::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages() {
        return $this->hasMany(ProductImages::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetwork() {
        return $this->hasOne(Networks::className(), ['network_id' => 'network_id']);
    }

}
