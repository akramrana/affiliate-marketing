<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "votes".
 *
 * @property int $vote_id
 * @property int $deal_id
 * @property int $user_id
 * @property int $vote
 * @property string $ip_address
 * @property string $created_at
 *
 * @property Deals $deal
 */
class Votes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'votes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deal_id', 'vote', 'ip_address', 'created_at'], 'required'],
            [['deal_id', 'user_id', 'vote'], 'integer'],
            [['created_at'], 'safe'],
            [['ip_address'], 'string', 'max' => 15],
            [['deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deals::className(), 'targetAttribute' => ['deal_id' => 'deal_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vote_id' => 'Vote ID',
            'deal_id' => 'Deal ID',
            'user_id' => 'User ID',
            'vote' => 'Vote',
            'ip_address' => 'Ip Address',
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
}
