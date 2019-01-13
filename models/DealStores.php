<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deal_stores".
 *
 * @property int $deal_store_id
 * @property int $deal_id
 * @property int $store_id
 * @property string $created_at
 *
 * @property Deals $deal
 * @property Stores $store
 */
class DealStores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deal_stores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deal_id', 'store_id', 'created_at'], 'required'],
            [['deal_id', 'store_id'], 'integer'],
            [['created_at'], 'safe'],
            [['deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deals::className(), 'targetAttribute' => ['deal_id' => 'deal_id']],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stores::className(), 'targetAttribute' => ['store_id' => 'store_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'deal_store_id' => 'Deal Store ID',
            'deal_id' => 'Deal ID',
            'store_id' => 'Store',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeal()
    {
        return $this->hasOne(Deals::className(), ['deal_id' => 'deal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Stores::className(), ['store_id' => 'store_id']);
    }
}
