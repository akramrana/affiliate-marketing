<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admins".
 *
 * @property int $admin_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $created_date
 * @property int $is_active
 * @property int $is_deleted
 */
class Admins extends \yii\db\ActiveRecord
{
    public $password_hash, $confirm_password, $permissions;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admins';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', 'created_date'], 'required'],
            [['created_date'], 'safe'],
            [['password_hash', 'confirm_password'], 'required', 'on' => 'create'],
            [['password_hash'], 'string', 'min' => 6],
            [['is_active', 'is_deleted'], 'integer'],
            [['name', 'email', 'password'], 'string', 'max' => 100],
            ['confirm_password', 'compare', 'compareAttribute' => 'password_hash', 'message' => Yii::t('yii', 'Confirm Password must be equal to "Password"')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'created_date' => 'Created Date',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'password_hash' => Yii::t('app', 'Password'),
            'confirm_password' => Yii::t('app', 'Confirm Password'),
        ];
    }
}
