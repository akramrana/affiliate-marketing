<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "networks".
 *
 * @property int $network_id
 * @property string $network_name
 * @property string $network_customer_id
 * @property string $network_passphrase
 * @property string $network_site_id
 * @property string $network_site_locale
 * @property int $cron_daily
 * @property int $create_category
 * @property int $create_store
 * @property int $notify_stores
 * @property int $notify_categories
 * @property int $auto_publish
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property Categories[] $categories
 * @property Deals[] $deals
 * @property Stores[] $stores
 */
class Networks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'networks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['network_name', 'network_customer_id'], 'required'],
            [['cron_daily', 'create_category', 'create_store', 'notify_stores', 'notify_categories', 'auto_publish', 'is_active', 'is_deleted'], 'integer'],
            [['network_name', 'network_customer_id', 'network_passphrase', 'network_site_id', 'network_site_locale'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'network_id' => 'Network ID',
            'network_name' => 'Network Name',
            'network_customer_id' => 'Network Customer ID',
            'network_passphrase' => 'Network Passphrase',
            'network_site_id' => 'Network Site ID',
            'network_site_locale' => 'Network Site Locale',
            'cron_daily' => 'Cron Daily',
            'create_category' => 'Create Category',
            'create_store' => 'Create Store',
            'notify_stores' => 'Notify Stores',
            'notify_categories' => 'Notify Categories',
            'auto_publish' => 'Auto Publish',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['network_id' => 'network_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeals()
    {
        return $this->hasMany(Deals::className(), ['network_id' => 'network_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStores()
    {
        return $this->hasMany(Stores::className(), ['network_id' => 'network_id']);
    }
}
