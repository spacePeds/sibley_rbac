<?php

namespace frontend\models;

use yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "business".
 *
 * @property int $id
 * @property string $name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $url
 * @property string $note
 * @property string $member
 * @property string $created_dt
 *
 * @property BusinessCategory[] $businessCategories
 * @property ContactMethod[] $contactMethods
 */
class Business extends \yii\db\ActiveRecord
{
    //img to upload
    public $imgFile;
    public $imgFileUrl;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address1', 'city', 'state', 'zip', 'member'], 'required'],
            [['note', 'member'], 'string'],
            [['created_dt','image'], 'safe'],
            [['imgFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif, JPG'],
            [['name', 'address1', 'address2', 'city'], 'string', 'max' => 100],
            [['state'], 'string', 'max' => 2],
            [['zip'], 'string', 'max' => 10],
            [['url'], 'string', 'max' => 255],
            [['url'], 'url', 'defaultScheme' => 'http'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'address1' => Yii::t('app', 'Address'),
            'address2' => Yii::t('app', 'Address2 (optional)'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'zip' => Yii::t('app', 'Zip'),
            'url' => Yii::t('app', 'Url'),
            'note' => Yii::t('app', 'Notes (business hours or additional details)'),
            'member' => Yii::t('app', 'Chamber Member'),
            'fullAddress' => Yii::t('app', 'Address'),
            'nameUrl' => Yii::t('app', 'Name'),
            'Business.ContactMethods.description' => Yii::t('app', 'Contacts')
        ];
    }

    /* Getter for organization's full address */
    public function getFullAddress() {
        if (!empty($this->address2)) {
            return $this->address1 . ', ' . $this->address2;
        }
        return $this->address1;
    }
    public function getNameUrl() {
        if (!empty($this->url)) {
            return '<a target="_blank" href="'.$this->url.'">'.$this->name.'</a>';
        }
        return $this->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessCategories()
    {
        return $this->hasMany(BusinessCategory::className(), ['business_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactMethods()
    {
        return $this->hasMany(ContactMethod::className(), ['business_id' => 'id']);
    }

    /**
     * Many-To-Many Relation
     * @return \yii\db\ActiveQuery
     */
    public function getCategories() {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
          ->viaTable('business_category', ['business_id' => 'id']);
    }

    /**
     * Needs to occur here to make sure validation occurs correctly
     * @param integer $businessRecordId
     */
    public function upload($businessRecordId)
    {
        if (!empty($this->imgFile)) { 
            
            $type = $this->imgFile->extension;
            $size = $this->imgFile->size;
            $name = $businessRecordId.'.'.$this->imgFile->extension; 
            $sysPath = '/' . Yii::$app->params['orgImagePath'];
            $path = Yii::$app->params['orgImagePath'] . $name;
            Yii::$app->session->setFlash('success', 'DEBUG: path exist? url webroot: ' . Yii::getAlias('@webroot') . ', url frontend: ' .Url::to('@frontend/web/') . ', param: ' . Yii::$app->params['orgImagePath']);
                    
            //https://stackoverflow.com/questions/5246114/php-mkdir-permission-denied-problem
            //chown -R www-data:www-data /path/to/webserver/www
            //chmod -R g+rw /path/to/webserver/www
            if (!is_dir(Url::to('@webroot') . $sysPath)) {
                mkdir(Url::to('@webroot') . $sysPath); 
            }
            //reletive url with no leading slash
            if (!$this->imgFile->saveAs($path)) {
                return false;
            }
            $this->image = $name;
        }
        return true;
    }
}
