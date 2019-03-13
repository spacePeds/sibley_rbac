<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "audit".
 *
 * @property int $id
 * @property int $record_id
 * @property string $field
 * @property string $old_value
 * @property string $new_value
 * @property string $date
 * @property int $update_user
 */
class Audit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['record_id', 'field', 'update_user'], 'required'],
            [['record_id', 'update_user'], 'integer'],
            [['table','old_value', 'new_value'], 'string'],
            [['date'], 'safe'],
            [['field'], 'string', 'max' => 255],
            [['table'], 'string', 'max' => 145],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'table' => Yii::t('app', 'Table'),
            'record_id' => Yii::t('app', 'Record ID'),
            'field' => Yii::t('app', 'Field'),
            'old_value' => Yii::t('app', 'Old Value'),
            'new_value' => Yii::t('app', 'New Value'),
            'date' => Yii::t('app', 'Date'),
            'update_user' => Yii::t('app', 'Update User'),
        ];
    }
}
