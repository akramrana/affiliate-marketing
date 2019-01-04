<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stores".
 *
 * @property int $store_id
 * @property int $api_store_id
 * @property int $name
 * @property string $store_url
 * @property string $description
 * @property string $store_logo
 * @property int $network_id
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property DealStores[] $dealStores
 * @property Networks $network
 */
class Stores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['api_store_id', 'name', 'network_id', 'is_active', 'is_deleted'], 'integer'],
            [['description'], 'string'],
            [['network_id', 'is_active', 'is_deleted'], 'required'],
            [['store_url', 'store_logo'], 'string', 'max' => 255],
            [['network_id'], 'exist', 'skipOnError' => true, 'targetClass' => Networks::className(), 'targetAttribute' => ['network_id' => 'network_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'store_id' => 'Store ID',
            'api_store_id' => 'Api Store ID',
            'name' => 'Name',
            'store_url' => 'Store Url',
            'description' => 'Description',
            'store_logo' => 'Store Logo',
            'network_id' => 'Network ID',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDealStores()
    {
        return $this->hasMany(DealStores::className(), ['store_id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetwork()
    {
        return $this->hasOne(Networks::className(), ['network_id' => 'network_id']);
    }
}
