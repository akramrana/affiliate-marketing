<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "votes_total".
 *
 * @property int $vote_total_id
 * @property int $deal_id
 * @property int $votes_up
 * @property int $votes_down
 * @property int $votes_total
 * @property string $last_update
 *
 * @property Deals $deal
 */
class VotesTotal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'votes_total';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deal_id', 'last_update'], 'required'],
            [['deal_id', 'votes_up', 'votes_down', 'votes_total'], 'integer'],
            [['last_update'], 'safe'],
            [['deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deals::className(), 'targetAttribute' => ['deal_id' => 'deal_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vote_total_id' => 'Vote Total ID',
            'deal_id' => 'Deal ID',
            'votes_up' => 'Votes Up',
            'votes_down' => 'Votes Down',
            'votes_total' => 'Votes Total',
            'last_update' => 'Last Update',
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
