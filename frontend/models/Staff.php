<?php

namespace frontend\models;
use yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "staff".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $position
 * @property string $elected
 * @property string $email
 * @property string $phone
 * @property string $image
 *
 * @property StaffElected[] $staffElected
 */
class Staff extends \yii\db\ActiveRecord
{
    //file to upload
    public $imageFile;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'position'], 'required'],
            [['elected'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['position'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 10],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, gif, png'],
            [['email'], 'email'],
            [['image'], 'safe'],
            [['phone'], 'number', 'message' => 'Please enter a phone number, with area code, without dashes.'],
            [['phone'], 'string', 'min'=>7,'max'=>11],
            //['elected', 'requiredWhenElected', 'params' => [
            //    'term_start' => 'term_start'  //whatever value happens to be
            //]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'position' => Yii::t('app', 'Position'),
            'elected' => Yii::t('app', 'Elected'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'image' => Yii::t('app', 'Attach a photo'),
        ];
    }

    /**
     * Upload a Image
     */
    public function upload($tableRecordId)
    {
        if (!empty($this->imageFile)) {        
            $type = $this->imageFile->extension;
            $size = $this->imageFile->size;
            $name = time().rand(100,999) .'_' . $tableRecordId . '.' . $type; 

            $sysPath = '/' . Yii::$app->params['staffImagePath'];
            $path = Yii::$app->params['staffImagePath'] . $name;
            //Yii::$app->session->setFlash('success', 'DEBUG: path exist? url webroot: ' . Yii::getAlias('@webroot') . ', url frontend: ' .Url::to('@frontend/web/') . ', param: ' . Yii::$app->params['orgImagePath']);
                    
            //https://stackoverflow.com/questions/5246114/php-mkdir-permission-denied-problem
            //chown -R www-data:www-data /path/to/webserver/www
            //chmod -R g+rw /path/to/webserver/www
            if (!is_dir(Url::to('@webroot') . $sysPath)) {
                mkdir(Url::to('@webroot') . $sysPath); 
            }
            //reletive url with no leading slash
            if (!$this->imageFile->saveAs($path)) {
                return false;
            }
            $this->image = $name;
        }
        return true;
        
        
        // if ($this->validate()) {
        //     $baseName = time().rand(100,999);
        //     $documentName = date('YmdHms') . '_' . $baseName . '.' . $this->imageFile->extension;
        //     $documentPath = Url::to('@webroot/') . 'img/staff/' . $documentName;
            
        //     $this->imageFile->saveAs($documentPath);
        //     return true;
        // } else {
        //     Yii::$app->session->setFlash('error', 'Validation failed during image upload. <div class="small">' . Html::error($this,'imageFiles') .'</div>');
        //     return false;
        // }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffElected()
    {
        return $this->hasMany(StaffElected::className(), ['staff_id' => 'id']);
    }

    /**
     * Custom Validator
     */
    //public function requiredWhenElected($attribute, $params) {
        //$myDate = $this->attribute;
        //$elected = $this->$params['elected'];
        //if ($elected) {
    //        $this->addError('staffElected[term_start]', "Term start is required when staff memeber is elected: , <pre>" . print_r($this,true) . '</pre>');
        //}  
    //}
    
}
