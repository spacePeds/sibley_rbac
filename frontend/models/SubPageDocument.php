<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "sub_page_document".
 *
 * @property int $id
 * @property int $sub_page_id
 * @property int $document_id
 * @property string $allignment
 *
 * @property Document $document
 * @property SubPage $subPage
 */
class SubPageDocument extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_page_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sub_page_id', 'document_id', 'allignment'], 'required'],
            [['sub_page_id', 'document_id'], 'integer'],
            [['allignment'], 'string', 'max' => 25],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['sub_page_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubPage::className(), 'targetAttribute' => ['sub_page_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sub_page_id' => Yii::t('app', 'Sub Page ID'),
            'document_id' => Yii::t('app', 'Document ID'),
            'allignment' => Yii::t('app', 'Allignment'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubPage()
    {
        return $this->hasOne(SubPage::className(), ['id' => 'sub_page_id']);
    }
}
