<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "creative_ads".
 *
 * @property int $creative_ad_id
 * @property string $content
 */
class CreativeAds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'creative_ads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'creative_ad_id' => 'Creative Ad ID',
            'content' => 'Content',
        ];
    }
}
