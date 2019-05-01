<?php

namespace frontend\models;

use Yii;
use frontend\models\Document;
use common\models\User;
use yii\helpers\Url;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $subject
 * @property string $description
 * @property string $group
 * @property string $start_dt
 * @property string $end_dt
 * @property int $all_day
 * @property int $repeat_interval
 * @property string $last_edit_dt
 * @property int $user_id
 *
 * @property User $user
 */
class Event extends \yii\db\ActiveRecord
{
    //file to upload
    public $pdfFile;
    public $pdfFileName;
    public $notes;
    public $groupDesc;
      //              $event['color'] = Yii::$app->params['eventGroupColor'][$event['group']];
       //             $event['icon']
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject', 'group', 'start_dt'], 'required'],
            [['description', 'group','location'], 'string'],
            [['start_dt', 'end_dt', 'last_edit_dt'], 'safe'],
            [['user_id', 'all_day','repeat_interval'], 'integer'],
            [['subject'], 'string', 'max' => 200],
            [['location'], 'string', 'max' => 255],
            [['pdfFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['end_dt'], 'compareDates'],
            [['repeat_interval'], 'intervalTest'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject' => Yii::t('app', 'Subject'),
            'description' => Yii::t('app', 'Description (optional)'),
            'location' => Yii::t('app', 'Location (optional)'),
            'group' => Yii::t('app', 'Group'),
            'start_dt' => Yii::t('app', 'Start Date'),
            'end_dt' => Yii::t('app', 'End Date (optional)'),
            'all_day' => Yii::t('app', 'All Day (optional)'),
            'repeat_interval' => Yii::t('app', 'Repeat Interval (optional)'),
            'pdfFile'  => Yii::t('app', 'Attach a PDF (optional)'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @param integer $id
     * @return string
     */
    public static function getAttachment($id)
    {
        if (empty($id)) {
            return '';
        }
        $rows = (new \yii\db\Query())
            ->select(['path', 'size','name'])
            ->from('document')
            ->where(['table_record' => 'event_' . $id])
            ->all();
        foreach($rows as $row) {
            //expecting just record so return
            return $row;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function upload($tableRecordId)
    {
        if ($this->validate()) {
            //$baseName = $string = preg_replace('/\s+/', '', $this->pdfFile->baseName);
            $baseName = time().rand(100,999);
            $documentName = date('YmdHms') . '_' . $baseName . '.' . $this->pdfFile->extension;
            $documentPath = Url::to('@webroot/') . Yii::$app->params['media'] . $documentName;
            $tableRecord = 'event_' . $tableRecordId;

            $document = new Document();
            $document->path = Yii::$app->params['media'];
            $document->name = $documentName;
            $document->table_record = $tableRecord;
            $document->type = $this->pdfFile->type;
            $document->size = $this->pdfFile->size;
            $document->sort_order = 1;
            $document->save();
            $this->pdfFile->saveAs($documentPath);
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Validation failed during image upload. <div class="small">' . Html::error($this,'imageFiles') .'</div>');
            return false;
        }
    }
    /**
     * Custom Validator
     * make sure End date comes after Start Date
     */
    public function compareDates()
    {
        $end_date = strtotime($this->end_dt);
        $start_date = strtotime($this->start_dt);

        if (!$this->hasErrors() && $end_date < $start_date) {
            $this->addError('end_dt', "End date must occur after start date.");
        }
    }
    /**
     * Custom VAlidator
     * Make sure selected custom interval has a future date far enough in the future
     */
    public function intervalTest() {
        $end_date = strtotime($this->end_dt);
        $start_date = strtotime($this->start_dt);
        
        if ($this->repeat_interval > 0) {
            if (!$this->hasErrors() && $end_date == $start_date) {
                $this->addError('repeat_interval', "End date must be further in the future to use this interval.");
            }
        }

    }
}
