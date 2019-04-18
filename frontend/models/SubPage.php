<?php

namespace frontend\models;
use frontend\models\Document;
use yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "sub_page".
 *
 * @property int $id
 * @property int $page_id
 * @property string $title
 * @property string $body
 * @property string $type
 * @property string $path
 * @property string $last_edit
 * @property int $created_by
 *
 * @property Page $page
 * @property SubPageDocument[] $subPageDocuments
 */
class SubPage extends \yii\db\ActiveRecord
{
    public $pageLabel;
    public $ajax_file;
    public $ajax_file_label;
    public $ajaxResult;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'title', 'type'], 'required'],
            [['page_id', 'created_by'], 'integer'],
            [['body','ajax_file_label'], 'string'],
            [['last_edit', 'created_by'], 'safe'],
            [['title', 'path'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 25],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
            [['ajax_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            
            'title' => Yii::t('app', 'Section Title'),
            'body' => Yii::t('app', 'Section Content'),
            'type' => Yii::t('app', 'Type'),
            'path' => Yii::t('app', 'Path'),
            'ajax_file_label' => Yii::t('app', 'Attachment Label'),
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubPageDocuments()
    {
        return $this->hasMany(SubPageDocument::className(), ['sub_page_id' => 'id']);
    }

    // public function upload()
    // {
    //     if ($this->validate(['ajax_file'])) {
    //         $this->ajax_file->saveAs('img/ajax/' . $this->ajax_file->baseName . '.' . $this->ajax_file->extension);
    //         return true;
    //     } else {
    //         Yii::$app->session->setFlash('error', 'VAlidation Failed.');
    //         return false;
    //     }
    // }

    /**
     * @param mixed $tableRecordId
     * @param string $path
     * @return \yii\db\ActiveQuery
     */
    public function upload($tableRecordId, $path)
    {
        if ($this->validate(['ajax_file'])) {
            //$baseName = $string = preg_replace('/\s+/', '', $this->pdfFile->baseName);
            $baseName = time().rand(100,999);
            $documentName = date('YmdHms') . '_' . $baseName . '.' . $this->ajax_file->extension;
            $documentPath = Url::to('@webroot/') . $path . $documentName;
            $tableRecord = 'subPage_' . $tableRecordId;

            $document = new Document();
            $document->path = $path;
            $document->name = $documentName;
            $document->label = $this->ajax_file_label;
            $document->table_record = $tableRecord;
            $document->type = $this->ajax_file->type;
            $document->size = $this->ajax_file->size;
            $document->sort_order = 1;
            $document->save();
            $this->ajax_file->saveAs($documentPath);
            $this->ajaxResult = ['status' => 'success','message' => 'Upload Successful', 'label' => $this->ajax_file_label];
            return true;
        } else {
            //Yii::$app->session->setFlash('error', 'Validation failed during file upload. <div class="small">' . Html::error($this,'ajax_file') .'</div>');
            $this->ajaxResult = ['status' => 'error','message' =>  Html::error($this,'ajax_file')];
            return false;
        }
    }
}
