<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deals".
 *
 * @property int $deal_id
 * @property string $title
 * @property string $content
 * @property int $is_active
 * @property int $is_deleted
 * @property int $coupon_id
 * @property int $program_id
 * @property string $coupon_code
 * @property string $voucher_types
 * @property string $start_date
 * @property string $end_date
 * @property string $expire_date
 * @property string $last_change_date
 * @property string $partnership_status
 * @property string $integration_code
 * @property int $featured
 * @property double $minimum_order_value
 * @property string $customer_restriction
 * @property string $sys_user_ip
 * @property string $destination_url
 * @property string $discount_fixed
 * @property string $discount_variable
 * @property string $discount_code
 * @property int $network_id
 *
 * @property DealCategories[] $dealCategories
 * @property DealStores[] $dealStores
 * @property Networks $network
 * @property Votes[] $votes
 * @property VotesTotal[] $votesTotals
 */
class Deals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'is_active', 'is_deleted', 'coupon_id', 'program_id', 'voucher_types', 'network_id'], 'required'],
            [['content', 'voucher_types', 'integration_code'], 'string'],
            [['is_active', 'is_deleted', 'coupon_id', 'program_id', 'featured', 'network_id'], 'integer'],
            [['start_date', 'end_date', 'expire_date', 'last_change_date', 'extras'], 'safe'],
            [['minimum_order_value'], 'number'],
            [['title', 'partnership_status'], 'string', 'max' => 255],
            [['coupon_code', 'customer_restriction', 'sys_user_ip', 'discount_fixed', 'discount_variable', 'discount_code'], 'string', 'max' => 50],
            [['network_id'], 'exist', 'skipOnError' => true, 'targetClass' => Networks::className(), 'targetAttribute' => ['network_id' => 'network_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'deal_id' => 'Deal ID',
            'title' => 'Title',
            'content' => 'Content',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'coupon_id' => 'Coupon',
            'program_id' => 'Program',
            'coupon_code' => 'Coupon Code',
            'voucher_types' => 'Voucher Types',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'expire_date' => 'Expire Date',
            'last_change_date' => 'Last Change Date',
            'partnership_status' => 'Partnership Status',
            'integration_code' => 'Integration Code',
            'featured' => 'Featured',
            'minimum_order_value' => 'Minimum Order Value',
            'customer_restriction' => 'Customer Restriction',
            'sys_user_ip' => 'Sys User Ip',
            'destination_url' => 'Destination Url',
            'discount_fixed' => 'Discount Fixed',
            'discount_variable' => 'Discount Variable',
            'discount_code' => 'Discount Code',
            'extras' => 'Extras',
            'network_id' => 'Network',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDealCategories()
    {
        return $this->hasMany(DealCategories::className(), ['deal_id' => 'deal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDealStores()
    {
        return $this->hasMany(DealStores::className(), ['deal_id' => 'deal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetwork()
    {
        return $this->hasOne(Networks::className(), ['network_id' => 'network_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Votes::className(), ['deal_id' => 'deal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotesTotals()
    {
        return $this->hasMany(VotesTotal::className(), ['deal_id' => 'deal_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Stores::className(), ['api_store_id' => 'program_id']);
    }
}
