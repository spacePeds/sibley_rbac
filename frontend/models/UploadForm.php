<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use frontend\models\ImageAsset;
use yii\helpers\Html;
use yii\helpers\Url;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif, JPG', 'maxFiles' => 4],
        ];
    }
    
    /**
     * Needs to occur here to make sure validation occurs correctly
     * @param string $path
     */
    public function upload($path)
    {
        if ($this->validate()) { 
            foreach ($this->imageFiles as $image) {
                $model = new ImageAsset();
                $model->path = $path;
                $model->type = $image->extension;
                $model->size = $image->size;
                $model->name = time().rand(100,999).'.'.$image->extension;
                if($model->save(false)) {
                    $image->saveAs(Url::to('@frontend/web') . $path . $model->name);
                    //$image->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                } else {
                    Yii::$app->session->setFlash('error', 'Could not save image: ' . $path . $model->name);
                }
            }
            return true;
        } else {
            //$errors = Model::getModelErrors();
            //Yii::$app->session->setFlash('error', 'Validation failed during image upload. <pre>' . print_r($this,true) .'</pre>');
            Yii::$app->session->setFlash('error', 'Validation failed during image upload. <div class="small">' . Html::error($this,'imageFiles') .'</div>');
            return false;
        }
    }
    
}