<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "business".
 *
 * @property int $id
 * @property string $name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $url
 * @property string $note
 * @property string $member
 * @property string $created_dt
 *
 * @property BusinessCategory[] $businessCategories
 * @property ContactMethod[] $contactMethods
 */
class Business extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address1', 'city', 'state', 'zip', 'member'], 'required'],
            [['note', 'member'], 'string'],
            [['created_dt'], 'safe'],
            [['name', 'address1', 'address2', 'city'], 'string', 'max' => 100],
            [['state'], 'string', 'max' => 2],
            [['zip'], 'string', 'max' => 10],
            [['url'], 'string', 'max' => 255],
            [['url'], 'url', 'defaultScheme' => 'http'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'address1' => Yii::t('app', 'Address1'),
            'address2' => Yii::t('app', 'Address2'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'zip' => Yii::t('app', 'Zip'),
            'url' => Yii::t('app', 'Url'),
            'note' => Yii::t('app', 'Note'),
            'member' => Yii::t('app', 'Member'),
            'created_dt' => Yii::t('app', 'Created Dt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessCategories()
    {
        return $this->hasMany(BusinessCategory::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactMethods()
    {
        return $this->hasMany(ContactMethod::className(), ['business_id' => 'id']);
    }

    /**
     * Many-To-Many Relation
     * @return \yii\db\ActiveQuery
     */
    public function getCategories() {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
          ->viaTable('business_category', ['business_id' => 'id']);
    }
}
