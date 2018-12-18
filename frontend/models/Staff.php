<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "staff".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $position
 * @property string $elected
 * @property string $email
 * @property string $phone
 * @property string $image_asset
 *
 * @property StaffElected[] $staffElected
 */
class Staff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'position'], 'required'],
            [['elected'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['position'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 10],
            [['email'], 'email'],
            [['image_asset'], 'safe'],
            [['phone'], 'number', 'message' => 'Please enter a phone number, with area code, without dashes.'],
            [['phone'], 'string', 'min'=>7,'max'=>11],
            //['elected', 'requiredWhenElected', 'params' => [
            //    'term_start' => 'term_start'  //whatever value happens to be
            //]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'position' => Yii::t('app', 'Position'),
            'elected' => Yii::t('app', 'Elected'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'image_asset' => Yii::t('app', 'Attach a uploaded photo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffElected()
    {
        return $this->hasMany(StaffElected::className(), ['staff_id' => 'id']);
    }

    /**
     * Custom Validator
     */
    //public function requiredWhenElected($attribute, $params) {
        //$myDate = $this->attribute;
        //$elected = $this->$params['elected'];
        //if ($elected) {
    //        $this->addError('staffElected[term_start]', "Term start is required when staff memeber is elected: , <pre>" . print_r($this,true) . '</pre>');
        //}  
    //}
    
}
