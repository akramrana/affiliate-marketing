<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banners".
 *
 * @property int $banner_id
 * @property string $name_en
 * @property string $image
 * @property string $type L = link, I = Image
 * @property int $is_active
 * @property int $is_deleted
 * @property string $created_at
 */
class Banners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image', 'type', 'created_at'], 'required'],
            [['type'], 'string'],
            [['is_active', 'is_deleted'], 'integer'],
            [['created_at'], 'safe'],
            [['name_en'], 'string', 'max' => 50],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'banner_id' => 'Banner ID',
            'name_en' => 'Name En',
            'image' => 'Image',
            'type' => 'Type',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }
}
