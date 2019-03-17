<?php

namespace frontend\models;


use Yii;
use frontend\models\Document;
use yii\helpers\Url;

/**
 * This is the model class for table "link".
 *
 * @property int $id
 * @property string $type
 * @property string $group
 * @property string $src_table
 * @property int $src_id
 * @property string $name
 * @property string $description
 * @property string $last_edit
 * @property string $created_by
 */
class Link extends \yii\db\ActiveRecord
{
    //file to upload
    public $pdfFile;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['group', 'setNewGroup','skipOnEmpty' => false, 'skipOnError' => false],
            [['type', 'name','group'], 'required'],
            [['src_id','created_by'], 'integer'],
            [['description'], 'string'],
            [['last_edit'], 'safe'],
            [['pdfFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['type'], 'string', 'max' => 10],
            ['name','url', 'defaultScheme' => 'http', 'when' => function($model){
                return ($model->type == "xlink" ? true : false);
            }, 'whenClient' => "function (attribute, value) {
                    return $('#link-type').val() == 'xlink';
            }"],
            [['src_table', 'name', 'group', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Link Type'),
            'group' => Yii::t('app', 'Group'),
            'name' => Yii::t('app', 'Link'),
            'label' => Yii::t('app', 'Label'),
            'pdfFile' => Yii::t('app', 'Choose PDF file'),
            'description' => Yii::t('app', 'Description (optional)'),
        ];
    }
    /**
     * Upload a PDF
     */
    public function upload($tableRecordId)
    {
        if ($this->validate()) {
            //$baseName = $string = preg_replace('/\s+/', '', $this->pdfFile->baseName);
            $baseName = time().rand(100,999);
            $documentName = date('YmdHms') . '_' . $baseName . '.' . $this->pdfFile->extension;
            $documentPath = Url::to('@webroot/') . 'media/' . $documentName;
            $tableRecord = 'link_' . $tableRecordId;

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
     * Make sure when new group is selected that a value is entered
     */
    public function setNewGroup($attribute)
    {
        //$this->addError($attribute, "Please type a new group label: post:: " . $_POST['newGroup'] . 'grp:' . $this->group);
        //return false;
        
        if ($this->group == 'new' && empty($_POST['newGroup'])) {
            $this->addError('group', "Please type a new group label.");
            return false;
        }
        return true;
    }
    /**
     * @param integer $id
     * @return string
     */
    public function getAttachment($id)
    {
        if (empty($id)) {
            return '';
        }
        $rows = (new \yii\db\Query())
            ->select(['path', 'size','name'])
            ->from('document')
            ->where(['table_record' => 'link_' . $id])
            ->all();
        foreach($rows as $row) {
            //expecting just record so return
            return $row;
        }
    }
    /**
     * @param integer $id
     * @return boolean
     */
    public function deleteAttachment($id)
    {
        if (empty($id)) {
            return false;
        }
        $attachment = $this->getAttachment($id);
        $path = $attachment['path'] . $attachment['name'];
        $sysPath = Url::to('@webroot') . '/'. $path;
        if(file_exists($sysPath)) {
            unlink($sysPath);
        } else {
            Yii::$app->session->setFlash('error', "Unable to locate attachment at: $path"); // in:" .Yii::getAlias('@webroot')
        }
       
        $affected_rows = (new \yii\db\Query())
            ->createCommand()
            ->delete('document', ['table_record' => 'link_' . $id])
            ->execute();
        if ($affected_rows > 0) {
            return true;
        }
        return false;
    }
    /**
     * Custom Validator
     * Make sure external link is a valid url
     */
    public function urlCustomValidator($attribute)
    {
        if ($this->type == 'xlink') {
            $this->addError($attribute, "this is a test");
        }
    }
}
