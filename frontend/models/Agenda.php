<?php

namespace frontend\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "agenda".
 *
 * @property int $id
 * @property string $type
 * @property string $date
 * @property string $body
 * @property string $create_dt
 *
 * @property AgendaMinutes[] $agendaMinutes
 */
class Agenda extends \yii\db\ActiveRecord
{
    public $yearToggle = '';
    public $yearList = [];
    public $dfltAgenda = 0;
    public $pdfFile;
    public $pdfFileDetails = [];
  
    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agenda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //default scenario
            [['type', 'date', 'body'], 'required'],
            [['date', 'create_dt','created_by'], 'safe'],            
            [['body'], 'string'],
            [['type'], 'string', 'max' => 100],
            [['date'], 'compareMeetingDates', 'on' => 'create']
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {   
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['type', 'date', 'body'];
        return $scenarios;

        
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'body',
                // 'slugAttribute' => 'slug',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Meeting Type'),
            'date' => Yii::t('app', 'Meeting Date'),
            'body' => Yii::t('app', 'Agenda'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgendaMinutes()
    {
        return $this->hasMany(AgendaMinutes::className(), ['agenda_id' => 'id']);
    }

    

    /**
     * Custom Validator
     * Check to see if any meetings are already set on this date
     */
    public function compareMeetingDates()
    {
        if ($this->id < 1) {
            $specifiedDate = date('Y-m-d', strtotime($this->date));
            $agendaCount = Agenda::find()->where(['date' => $specifiedDate])->count();

            if ( $agendaCount > 0) {
                $this->addError('date', "There appears to already be a meeting scheduled on this date.");
            }
        }
        
    }
}
