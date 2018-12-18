<?php

namespace frontend\models;

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
        /*
        ['term_start', 'required','when' => 
                function ($model, $attribute) {
                    return $model->staff->elected == 1;
                },
                'whenClient' => "function (attribute, value) {
                    return ($('#staff-elected').val() == 1);
                ",
            ],
        */
        return [
            [['staff_id'], 'required'],
            [['staff_id'], 'integer'],
            [['term_start', 'term_end'], 'safe'],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::className(), 'targetAttribute' => ['staff_id' => 'id']],
            [['term_start'], 'requiredWhenElected', 'params' => [],'skipOnEmpty' => false, 'skipOnError' => false],
            //[['term_end'], 'required', 'when' => function($model){return ($model->term_start == null);}],
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

    /**
     * Custom Validator
     */
    public function requiredWhenElected($attribute, $params) { 
        $post = isset($_POST) ? $_POST : []; 
        if (!$post) {
            return false;
        }
        
        $electedFlag = $post['Staff']['elected'];
        
        if ($electedFlag) {
            $eVal = 'yes, this person is indeed elected';
            if (empty($this->term_start)) {
                $this->addError('term_start', "Term start is required when staff member is elected");
                return true;
            }
            if (empty($this->term_end)) {
                $this->addError('term_end', "Term end is required when staff member is elected");
                return true;
            }
        } 
        
        
        //if ($elected) {
        //    $this->addError($attribute, "Term start is required when staff member is elected: $eVal, <pre>" . print_r($_POST,true) . '</pre>');
        //}
        
        return false;
        
    }
}
