<?php

namespace frontend\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "header_image".
 *
 * @property int $id
 * @property string $image_path
 * @property string $image_idx
 * @property string $display
 * @property int $brightness
 * @property int $offset
 * @property int $height
 * @property string $position
 * @property int $sequence
 * @property string $last_edit
 * @property int $created_by
 */
class HeaderImage extends \yii\db\ActiveRecord
{
    //image to upload
    public $uploadedImage;
    public $ajaxResult;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'header_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['display', 'height', 'position'], 'required'],
            [['brightness', 'offset', 'height', 'created_by','sequence'], 'integer'],
            [['last_edit','created_by'], 'safe'],
            ['brightness', 'double'],
            [['brightness', 'offset'], 'default', 'value'=> 0],
            [['image_path', 'image_idx','display','position'], 'string', 'max' => 255],
            [['uploadedImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg,jpeg,gif,png'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'image_path' => Yii::t('app', 'Image Path'),
            'display' => Yii::t('app', 'Display Type'),
            'brightness' => Yii::t('app', 'Brightness'),
            'offset' => Yii::t('app', 'Vertical Offset'),
            'height' => Yii::t('app', 'Height'),
            'position' => Yii::t('app', 'Allignment'),
            'sequence' => Yii::t('app', 'Display Order'),
            'last_edit' => Yii::t('app', 'Last Edit'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @param mixed $tableRecordId
     * @param string $newNamePath
     * @return \yii\db\ActiveQuery
     */
    public function upload($newNamePath)
    {
        if ($this->validate(['uploadedImage'])) {
            $this->uploadedImage->saveAs($newNamePath);
            $this->ajaxResult = ['status' => 'success','message' => 'Upload Successful'];
            return true;
        } else {
            $this->ajaxResult = ['status' => 'error','message' =>  Html::error($this,'uploadedImage')];
            return false;
        }
    }
    // public function upload($path)
    // {
    //     if ($this->validate(['uploadedImage'])) {
    //         //$baseName = $string = preg_replace('/\s+/', '', $this->pdfFile->baseName);
    //         $baseName = time().rand(100,999);
    //         $documentName = 'headerImg' . $this->image_idx . '_' . $baseName . '.' . $this->uploadedImage->extension;
    //         $documentPath = Url::to('@webroot/') . $path . $documentName;
    //         $this->image_path = $documentPath;
    //         // $document = new Document();
    //         // $document->path = $path;
    //         // $document->name = $documentName;
    //         // $document->label = $this->ajax_file_label;
    //         // $document->table_record = $tableRecord;
    //         // $document->type = $this->ajax_file->type;
    //         // $document->size = $this->ajax_file->size;
    //         // $document->sort_order = 1;
    //         // $document->save();
    //         $this->uploadedImage->saveAs($documentPath);
    //         $this->ajaxResult = ['status' => 'success','message' => 'Upload Successful'];
    //         return true;
    //     } else {
    //         //Yii::$app->session->setFlash('error', 'Validation failed during file upload. <div class="small">' . Html::error($this,'ajax_file') .'</div>');
    //         $this->ajaxResult = ['status' => 'error','message' =>  Html::error($this,'uploadedImage')];
    //         return false;
    //     }
    // }
}
