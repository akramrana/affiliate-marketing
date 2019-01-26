<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deal_banners".
 *
 * @property int $deal_banner_id
 * @property string $title
 * @property string $content
 * @property string $type
 * @property string $created_at
 * @property int $is_deleted
 * @property int $is_active
 */
class DealBanners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deal_banners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content', 'type', 'created_at'], 'required'],
            [['content', 'type'], 'string'],
            [['created_at'], 'safe'],
            [['is_deleted', 'is_active'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'deal_banner_id' => 'Deal Banner ID',
            'title' => 'Title',
            'content' => 'Content',
            'type' => 'Type',
            'created_at' => 'Created At',
            'is_deleted' => 'Is Deleted',
            'is_active' => 'Is Active',
        ];
    }
}
