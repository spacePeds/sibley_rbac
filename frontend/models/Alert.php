<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "alert".
 *
 * @property int $id
 * @property string $group
 * @property string $type
 * @property string $message
 * @property string $title
 * @property string $start_dt
 * @property string $end_dt
 * @property string $created_dt
 */
class Alert extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group', 'type', 'title', 'start_dt', 'end_dt', 'created_dt'], 'required'],
            [['start_dt', 'end_dt', 'created_dt'], 'safe'],
            [['group', 'type'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 255],
            [['message'], 'string'],
            [['start_dt'], 'date', 'format' => 'php:m/d/Y']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'group' => Yii::t('app', 'Group'),
            'type' => Yii::t('app', 'Display Type'),
            'title' => Yii::t('app', 'Title / Short Message'),
            'message' => Yii::t('app', 'Extended Message (optional)'),
            'start_dt' => Yii::t('app', 'Start Date'),
            'end_dt' => Yii::t('app', 'End Date'),
        ];
    }
}
