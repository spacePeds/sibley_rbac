<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Staff;
use common\models\User;
use frontend\models\Page;
use frontend\models\SubPage;
use frontend\models\Event;
use frontend\models\Agenda;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use frontend\components\FrontendController;

/**
 * Recoreation Controller implements the CRUD actions for Recreation model.
 */
class RecreationController extends FrontendController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]  
        ];
    }
    
    /**
     * Displays sibley generic parks page.
     *
     * @return mixed
     */
    public function actionParks()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 11;
        $page = $this->getGenericPage($pageKey);
        return $this->render('/sibley/generic', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }

    /**
     * Displays sibley generic golf course page.
     *
     * @return mixed
     */
    public function actionGolf()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 10;
        $page = $this->getGenericPage($pageKey);
        return $this->render('/sibley/generic', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }

    /**
     * Displays sibley generic golf course page.
     *
     * @return mixed
     */
    public function actionCamping()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 12;
        $page = $this->getGenericPage($pageKey);
        return $this->render('/sibley/generic', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }
    
    /**
     * Displays sibley generic golf course page.
     *
     * @return mixed
     */
    public function actionFishing()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 13;
        $page = $this->getGenericPage($pageKey);
        return $this->render('/sibley/generic', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }

    /**
     * Displays sibley generic golf course page.
     *
     * @return mixed
     */
    public function actionSwimming()
    {
        //define semantic url for page
        $slug = '';
        $pageKey = 14;
        $page = $this->getGenericPage($pageKey);
        return $this->render('/sibley/generic', [
            'details' => $page,
            'key' => $pageKey
        ]);
    }
}