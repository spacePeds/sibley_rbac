<?php

namespace frontend\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $route
 * @property string $title
 * @property string $body
 * @property string $slug
 * @property string $fb_token
 * @property string $fb_link
 * @property string $last_edit_dt
 * @property int $user_id
 *
 * @property PageCategory[] $pageCategories
 */
class Page extends \yii\db\ActiveRecord
{
    public static $fbToggle;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['route', 'title', 'body'], 'required'],
            [['body','slug','fb_token','fb_link'], 'string'],
            [['last_edit_dt'], 'safe'],
            [['user_id'], 'integer'],
            [['route', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'route' => Yii::t('app', 'Route'),
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
            'fb_token' => Yii::t('app', 'Facebook App ID'),
            'fb_link' => Yii::t('app', 'Facebook Page ID'),
            'last_edit_dt' => Yii::t('app', 'Last Edit Dt'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                // 'slugAttribute' => 'slug',   //don't need because default is 'slug'
                'immutable' => true,            //dont update the slug since search engines may depend on it
                'ensureUnique'=>true,           //auto append like slugs
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageCategories()
    {
        return $this->hasMany(PageCategory::className(), ['page_id' => 'id']);
    }

    /**
     * Many-To-Many Relation
     * @return \yii\db\ActiveQuery
     */
    public function getCategories() {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
          ->viaTable('page_category', ['page_id' => 'id']);
    }

    
}
