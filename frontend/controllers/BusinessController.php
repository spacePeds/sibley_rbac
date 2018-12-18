<?php

namespace frontend\controllers;

use Yii;
use frontend\models\BusinessWithCategories;
use frontend\models\Category;
use frontend\models\Business;
use frontend\models\ContactMethod;
use frontend\models\BusinessSearch;
use frontend\models\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * BusinessController implements the CRUD actions for Business model.
 */
class BusinessController extends Controller
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
     * Default display.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['list']);
        
    }
    /**
     * Lists all Business models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new BusinessSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Business model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = BusinessWithCategories::findOne($id);
        $contactMethods = $model->contactMethods;
        $model->loadCategories();

        return $this->render('view', [
            'model' => $model,
            'contactMethods' => $contactMethods,
            'categories' => Category::getAvailableCategories(),
        ]);
    }

    /**
     * Creates a new Business model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BusinessWithCategories();
        $modelsContactMethod = [new ContactMethod];

        if ($model->load(Yii::$app->request->post())) {
            Yii::debug('form has been posted', __METHOD__);
            $modelsContactMethod = Model::createMultiple(ContactMethod::classname());
            Model::loadMultiple($modelsContactMethod, Yii::$app->request->post());

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsContactMethod) && $valid;

            if ($valid) {
                Yii::debug('data is valid, beginning transaction', __METHOD__);
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsContactMethod as $modelContactMethod) {
                            $modelContactMethod->business_id = $model->id;
                            $modelContactMethod->created_dt = date('Y-m-d H:i:s');
                            if (! ($flag = $modelContactMethod->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $model->created_dt = date('Y-m-d H:i:s');
                        $model->saveCategories();
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Business '.Yii::$app->request->post('name').'created successfully.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    Yii::$app->session->setFlash('warning', 'Insert failed.');
                    $transaction->rollBack();
                }
            }


            //$model->created_dt = date('Y-m-d H:i:s');
            //$model->save();
            //$model->saveCategories();
            //return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => Category::getAvailableCategories(),
            'modelsContact' => (empty($modelsContactMethod)) ? [new ContactMethod] : $modelsContactMethod
        ]);
    }

    /**
     * Updates an existing Business model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = BusinessWithCategories::findOne($id);   
        $modelsContact = $model->contactMethods;
        $model->loadCategories();

        
        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsContact, 'id', 'id');
            $modelsContact = Model::createMultiple(ContactMethod::classname(), $modelsContact);
            Model::loadMultiple($modelsContact, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsContact, 'id', 'id')));

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsContact) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            ContactMethod::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsContact as $modelContact) {
                            $modelContact->business_id = $model->id;
                            if (! ($flag = $modelContact->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $model->saveCategories();
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        

        return $this->render('update', [
            'model' => $model,
            'categories' => Category::getAvailableCategories(),
            'modelsContact' => (empty($modelsContact)) ? [new ContactMethod] : $modelsContact
        ]);
    }

    /**
     * Deletes an existing Business model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $businessName = $model->name;

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Record  <strong>"' . $businessName . '"</strong> deleted successfully.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Business model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Business the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Business::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
