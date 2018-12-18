<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "business_category".
 * https://www.yiiframework.com/wiki/836/how-to-createupdate-a-model-with-its-related-items-using-listbox-or-checkboxlist#controller-actions-and-views
 *
 */
class BusinessWithCategories extends Business
{
    /**
     * @var array IDs of the categories
     */
    public $category_ids = [];
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            // each category_id must exist in category table 
            // In the rules for the validation, we use EachValidator to validate the array of category_ids attribute
            ['category_ids', 'each', 'rule' => [
                    'exist', 'targetClass' => Category::className(), 'targetAttribute' => 'id'
                ]
            ],
        ]);
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'category_ids' => 'Categories',
        ]);
    }

    /**
     * load the post's categories 
     * loadCategories method loads the IDs of the post's categories into this model instance.
     */
    public function loadCategories()
    {
        $this->category_ids = [];
        if (!empty($this->id)) {
            $rows = BusinessCategory::find()
                ->select(['category_id'])
                ->where(['business_id' => $this->id])
                ->asArray()
                ->all();
            foreach($rows as $row) {
               $this->category_ids[] = $row['category_id'];
            }
        }
    }

    /**
     * save the business's categories 
     * saveCategories method saves the businesses's categories specified in category_ids attribute
     */
    public function saveCategories()
    {
        /* clear the categories of the business before saving */
        BusinessCategory::deleteAll(['business_id' => $this->id]);
        if (is_array($this->category_ids)) {
            foreach($this->category_ids as $category_id) {
                $pc = new BusinessCategory();
                $pc->business_id = $this->id;
                $pc->category_id = $category_id;
                $pc->save();
            }
        }
        /* Be careful, $this->category_ids can be empty */
    }
}