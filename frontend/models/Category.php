<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $category
 * @property string $description
 * @property string $created_dt
 *
 * @property BusinessCategory[] $businessCategories
 * @property PageCategory[] $pageCategories
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['category'], 'unique'],
            [['description'], 'string'],
            [['created_dt'], 'safe'],
            [['category'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Category'),
            'description' => Yii::t('app', 'Description'),
            'created_dt' => Yii::t('app', 'Created Dt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessCategories()
    {
        return $this->hasMany(BusinessCategory::className(), ['category_id' => 'id']);
    }

    /**
     * Many-To-Many Relation
     * @return \yii\db\ActiveQuery
     */
    public function getBusinesses() {
        return $this->hasMany(Business::className(), ['id' => 'business_id'])
          ->viaTable('business_category', ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageCategories()
    {
        return $this->hasMany(PageCategory::className(), ['category_id' => 'id']);
    }

    /**
     * Many-To-Many Relation
     * @return \yii\db\ActiveQuery
     */
    public function getPages() {
        return $this->hasMany(Page::className(), ['id' => 'business_id'])
          ->viaTable('page_category', ['category_id' => 'id']);
    }

    /**
     * Get all the available categories
     * getAvailableCategories method is a static utility function 
     * to get the list of available categories. In the returned array, 
     * the keys are 'id' and the values are 'name' of the categories.
     * 
     * @return array available categories
     */
    public static function getAvailableCategories()
    {
        $categories = self::find()->orderBy('category')->asArray()->all();
        $items = ArrayHelper::map($categories, 'id', 'category');
        return $items;
    }
}
