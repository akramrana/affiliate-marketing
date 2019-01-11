<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cms".
 *
 * @property int $cms_id
 * @property string $title_en
 * @property string $content_en
 * @property int $is_deleted
 */
class Cms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_en', 'content_en'], 'required'],
            [['content_en'], 'string'],
            [['is_deleted'], 'integer'],
            [['title_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cms_id' => 'Cms ID',
            'title_en' => 'Title',
            'content_en' => 'Content',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
