<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $category_id
 * @property int $api_category_id
 * @property string $name
 * @property string $description
 * @property int $no_of_programs
 * @property string $created_at
 * @property string $updated_at
 * @property int $parent_id
 * @property int $network_id
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property Networks $network
 * @property DealCategories[] $dealCategories
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','network_id'], 'required'],
            [['api_category_id', 'no_of_programs', 'parent_id', 'network_id', 'is_active', 'is_deleted'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['network_id'], 'exist', 'skipOnError' => true, 'targetClass' => Networks::className(), 'targetAttribute' => ['network_id' => 'network_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'api_category_id' => 'Api Category ID',
            'name' => 'Name',
            'description' => 'Description',
            'no_of_programs' => 'No Of Programs',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'parent_id' => 'Parent',
            'network_id' => 'Network',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
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
    public function getDealCategories()
    {
        return $this->hasMany(DealCategories::className(), ['category_id' => 'category_id']);
    }
}
