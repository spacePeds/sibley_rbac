<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property string $path
 * @property string $type
 * @property int $size
 * @property string $name
 * @property int $sort_order
 */
class Document extends \yii\db\ActiveRecord
{
    //file to upload
    public $asset;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['table_record'], 'required'],
            [['size', 'sort_order'], 'integer'],
            [['path'], 'string', 'max' => 1024],
            [['asset'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg,jpeg,gif,png'],
            [['type', 'name','table_record'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'path' => Yii::t('app', 'Path'),
            'type' => Yii::t('app', 'Type'),
            'size' => Yii::t('app', 'Size'),
            'name' => Yii::t('app', 'Name'),
            'table_record' => Yii::t('app', 'Table Record'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'asset'  => Yii::t('app', 'Attach an image'),
        ];
    }
    /**
     * @param tableRecord $tableRecord
     * @return string
     */
    public function getAssets($tableRecord)
    {
        if (empty($tableRecord)) {
            return '';
        }
        $rows = (new \yii\db\Query())
            ->select(['path', 'size','name'])
            ->from('document')
            ->where(['like', 'table_record', $tableRecord])
            ->all();
        $images = [];
        $idx = 0;
        foreach($rows as $row) {
            $images[$idx] = $row;
            $idx++;
            
        }
        return $images;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function upload()
    {
        if ($this->validate()) {
            $baseName = $string = preg_replace('/\s+/', '', $this->asset->baseName);
            $documentName = date('YmdHms') . '_' . $baseName . '.' . $this->asset->extension;
            $documentPath = 'uploads/' . $documentName;
            $tableRecord = 'page_asset';

            $document = new Document();
            $document->path = $documentPath;
            $document->name = $documentName;
            $document->table_record = $tableRecord;
            $document->type = $this->asset->type;
            $document->size = $this->asset->size;
            $document->sort_order = 1;
            $document->save();
            $this->asset->saveAs($documentPath);
            return true;
        } else {
            return false;
        }
    }
}
