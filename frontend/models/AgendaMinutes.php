<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "agenda_minutes".
 *
 * @property int $id
 * @property int $agenda_id
 * @property string $attend
 * @property string $absent
 * @property string $body
 * @property string $create_dt
 *
 * @property Agenda $agenda
 */
class AgendaMinutes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agenda_minutes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agenda_id', 'attend', 'body'], 'required'],
            [['agenda_id'], 'integer'],
            [['body'], 'string'],
            [['create_dt'], 'safe'],
            [['attend', 'absent','video'], 'string', 'max' => 255],
            [['agenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agenda::className(), 'targetAttribute' => ['agenda_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'agenda_id' => Yii::t('app', 'Agenda ID'),
            'attend' => Yii::t('app', 'Council Members Attending'),
            'absent' => Yii::t('app', 'Council Members Absent'),
            'body' => Yii::t('app', 'Minutes'),
            'video' => Yii::t('app', 'Video URL'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgenda()
    {
        return $this->hasOne(Agenda::className(), ['id' => 'agenda_id']);
    }
}
