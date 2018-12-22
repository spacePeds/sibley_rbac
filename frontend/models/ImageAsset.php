<?php

namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the model class for table "image_asset".
 *
 * @property string $id
 * @property string $path
 * @property string $type
 * @property int $size
 * @property string $name
 * @property string $created_dt
 */
class ImageAsset extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image_asset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['path', 'type', 'size', 'name'], 'required'],
            [['size'], 'integer'],
            [['created_dt'], 'safe'],
            [['path'], 'string', 'max' => 1024],
            [['type', 'name'], 'string', 'max' => 255],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'type' => 'Type',
            'size' => 'Size',
            'name' => 'Name',
            'created_dt' => 'Created Dt',
        ];
    }

    /**
     * Search Asset directory for all image assets
     * Return a list of found images and some details about images
     * @return array
     */
    public function retrieveAssets() 
    {
        
        $assets = (new Query())
            ->select(['id','path','type','size','name','DATE_FORMAT(created_dt,"%m/%d/%Y") as upldDt'])
            ->from('image_asset')
            ->all();
        if (count($assets) < 1) {
            $assets = [
                0 => [
                    'id' => 0,
                    'name' => 'N/A',
                    'path' => Url::to('@web/img/assets/') . 'placeholder-image.jpg',
                    'size' => '',
                    'type' => '',
                    'upldDt' => ''
                ]
            ];
        }

        return $assets;
    }
}
