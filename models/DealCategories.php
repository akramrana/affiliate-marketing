<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deal_categories".
 *
 * @property int $deal_category_id
 * @property int $deal_id
 * @property int $category_id
 * @property string $created_at
 *
 * @property Categories $category
 * @property Deals $deal
 */
class DealCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deal_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deal_id', 'category_id', 'created_at'], 'required'],
            [['deal_id', 'category_id'], 'integer'],
            [['created_at'], 'safe'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'category_id']],
            [['deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deals::className(), 'targetAttribute' => ['deal_id' => 'deal_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'deal_category_id' => 'Deal Category ID',
            'deal_id' => 'Deal ID',
            'category_id' => 'Category',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeal()
    {
        return $this->hasOne(Deals::className(), ['deal_id' => 'deal_id']);
    }
}
