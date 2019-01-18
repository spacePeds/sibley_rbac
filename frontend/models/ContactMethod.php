<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "contact_method".
 *
 * @property int $id
 * @property int $business_id
 * @property string $method
 * @property string $contact
 * @property string $description
 * @property string $created_dt
 *
 * @property Business $business
 */
class ContactMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['method', 'contact'], 'required'],    //remove 'business_id' requirment as it may not exist yet for one-to-many relation
            [['business_id'], 'integer'],
            [['method'], 'string'],
            [['created_dt'], 'safe'],
            [['contact'], 'string', 'max' => 100],
            //['contact','email', 'when' => function($model){
            //    return ($model->method == "email" ? true : false);
            //}],
            //['contact','number', 'when' => function($model){
            //    return ($model->method == "phone" ? true : false);
            //}],
            [['description'], 'string', 'max' => 255],
            [['business_id'], 'exist', 'skipOnError' => true, 'targetClass' => Business::className(), 'targetAttribute' => ['business_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'business_id' => Yii::t('app', 'Business ID'),
            'method' => Yii::t('app', 'Method'),
            'contact' => Yii::t('app', 'Phone or Email Address'),
            'description' => Yii::t('app', 'Description'),
            'created_dt' => Yii::t('app', 'Created Dt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }
}
