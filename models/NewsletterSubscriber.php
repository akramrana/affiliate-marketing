<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newsletter_subscriber".
 *
 * @property int $newsletter_subscriber_id
 * @property string $email
 * @property string $created_at
 * @property int $is_active
 * @property int $is_deleted
 */
class NewsletterSubscriber extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newsletter_subscriber';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'created_at'], 'required'],
            [['created_at'], 'safe'],
            [['is_active', 'is_deleted'], 'integer'],
            [['email'], 'string', 'max' => 50],
            ['email','email']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'newsletter_subscriber_id' => 'Newsletter Subscriber ID',
            'email' => 'Email',
            'created_at' => 'Created At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
