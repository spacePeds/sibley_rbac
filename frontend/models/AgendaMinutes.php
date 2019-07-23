<?php

namespace frontend\models;

use Yii;
use yii\helpers\Url;

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
    public $pdfFile;
    public $pdfFileDetails = [];
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
            [['pdfFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
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
            'video' => Yii::t('app', 'YouTube Video ID (optional)'),
            'pdfFile'  => Yii::t('app', 'Attach a PDF (optional)'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgenda()
    {
        return $this->hasOne(Agenda::className(), ['id' => 'agenda_id']);
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
            ->select(['id', 'path', 'size','name'])
            ->from('document')
            ->where(['table_record' => 'minutes_' . $id])
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
            $baseName = time().rand(100,999);
            $tableRecord = 'minutes_' . $tableRecordId;
            $documentName = $baseName . $tableRecord . '.' . $this->pdfFile->extension;
            $documentPath = Url::to('@webroot/') . Yii::$app->params['media'] . $documentName;
            

            $document = new Document();
            $document->path = Yii::$app->params['media'];
            $document->name = $documentName;
            $document->table_record = $tableRecord;
            $document->type = $this->pdfFile->type;
            $document->size = $this->pdfFile->size;
            $document->label = '';
            $document->sort_order = 1;
            $document->save();
            $this->pdfFile->saveAs($documentPath);
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'Validation failed during image upload. <div class="small">' . Html::error($this,'imageFiles') .'</div>');
            return false;
        }
    }
}
