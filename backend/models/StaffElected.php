<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "staff_elected".
 *
 * @property int $id
 * @property int $staff_id
 * @property string $term_start
 * @property string $term_end
 *
 * @property Staff $staff
 */
class StaffElected extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_elected';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id', 'term_start', 'term_end'], 'required'],
            [['staff_id'], 'integer'],
            [['term_start', 'term_end'], 'safe'],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::className(), 'targetAttribute' => ['staff_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'staff_id' => Yii::t('app', 'Staff ID'),
            'term_start' => Yii::t('app', 'Term Start'),
            'term_end' => Yii::t('app', 'Term End'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'staff_id']);
    }
}
