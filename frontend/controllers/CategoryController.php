<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Category;
use frontend\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $subQry = (new Query())->select('COUNT(*)')->from('business_category')->where('category_id = c.id');
        //(select count(*) from business_category where category_id = c.id) catCount'
        $model = (new Query())
            ->select(['c.id','c.category','c.description','c.created_dt','catCount' => $subQry])
            ->from('category c')
            ->all();


        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create_category')) {
            $model = new Category();

            if ($model->load(Yii::$app->request->post())){
                $model->created_dt = date('Y-m-d H:i:s');
                $model->save();
                
                //$request=Yii:$app->request->post('UserFormModel')['username']
                $myPost = Yii::$app->request->post('Category');            
                Yii::$app->session->setFlash('success', 'Successfully Inserted Category: '.$myPost['category'].'.');
                return $this->redirect(['index']);
            }

            return $this->renderAjax('create', ['model' => $model]);

        } else {
            throw new ForbiddenHttpException("You probably shouldn't be here.");
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update_category')) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $myPost = Yii::$app->request->post('Category'); 
                Yii::$app->session->setFlash('success', 'Successfully Updated Category: '.$myPost['category'].'.');
                return $this->redirect(['index']);
            }

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete_category')) {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'Successfully deleted category ' . $id . '.');
            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
