<?php

namespace frontend\controllers;

use Yii;
use frontend\models\AgendaMinutes;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\bootstrap4\ActiveForm;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use frontend\models\Audit;


/**
 * AgendaController implements the CRUD actions for Agenda model.
 */
class AgendaMinutesController extends Controller
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
     * Displays a single Minutes model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Minutes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $agendaId
     * @return mixed
     */
    public function actionCreate($agendaId)
    {
        if (Yii::$app->user->can('create_agenda')) {
        
            $model = new AgendaMinutes();
            $model->agenda_id = $agendaId;

            if ($model->load(Yii::$app->request->post())) {

                $model->create_dt = date('Y-m-d');
                $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');

                if ($model->save()) {
                    $audit = new Audit();
                    $audit->table = 'minutes';
                    $audit->record_id = $model->id;
                    $audit->field = 'Create';
                    $audit->update_user = Yii::$app->user->identity->id;
                    $audit->save(false);
                    if ($model->pdfFile) {
                        if ($model->upload($model->id)) { 
                            Yii::$app->session->setFlash('success', "Minutes created successfully with attachment: " . $model->pdfFile->name);
                        } else {
                            Yii::$app->session->setFlash('error', "Minutes created successfully but PDF failed to attach.");
                        }
                    } else {
                        Yii::$app->session->setFlash('success', "Minutes successfully posted for meeting.");
                    }
                    
                } else {
                    Yii::$app->session->setFlash('error', "Failed to post minutes. Error: " . Html::error($model,'date'));
                }
                return $this->redirect(['sibley/council', 'id' => $model->agenda_id]);
            }

            //print_r($model);

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }

    /**
     * Updates an existing Agenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update_agenda')) {
        
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                
                $model->pdfFile = UploadedFile::getInstance($model, 'pdfFile');

                if ($model->save()) {
                    $audit = new Audit();
                    $audit->table = 'minutes';
                    $audit->record_id = $model->id;
                    $audit->field = 'Update';
                    $audit->update_user = Yii::$app->user->identity->id;
                    $audit->save(false);
                    if ($model->pdfFile) {
                        if ($model->upload($model->id)) { 
                            Yii::$app->session->setFlash('success', "Minutes updated successfully with attachment: " . $model->pdfFile->name);
                        } else {
                            Yii::$app->session->setFlash('error', "Minutes updated successfully but PDF failed to attach.");
                        }
                    } else {
                        Yii::$app->session->setFlash('success', "Minutes successfully updated.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', "An error occured while updating minutes. Your modifications were not saved.");
                }
                return $this->redirect(['sibley/council', 'id' => $model->agenda_id]);
            }

            //find document if exists
            $pdfFileInfo = $model->getAttachment($id);
            if (!empty($pdfFileInfo)) {
                $model->pdfFileDetails = $pdfFileInfo;
                $model->pdfFile = Yii::getAlias('@web') .'/'. $pdfFileInfo['path'] . $pdfFileInfo['name'];
            }

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }

    /**
     * Deletes an existing Agenda model.
     * If deletion is successful, the browser will be redirected to the 'meetings' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete_agenda')) {
        
            if ($this->findModel($id)->delete()) {
                $audit = new Audit();
                $audit->table = 'minutes';
                $audit->record_id = $id;
                $audit->field = 'Delete';
                $audit->update_user = Yii::$app->user->identity->id;
                $audit->save(false);

                $pdfFileInfo = $model->getAttachment($id);
                if (!empty($pdfFileInfo)) {
                    $document = Document::find()->where(['id' => $pdfFileInfo['id']])->one();
                    if ($document) {
                        $sysPath = Url::to('@webroot') . '/' . $document->path . $document->name;
                        if ($document->delete()) {
                            $jResult['status'] = 'success';
                            $jResult['message'] = 'Document Record deleted.';
                            if(file_exists($sysPath)) {
                                unlink($sysPath);
                            } 
                        }
                    }
                }
                Yii::$app->session->setFlash('success', "Minutes successfully deleted.");
            } else {
                Yii::$app->session->setFlash('error', "The minutes were not deleted due to an error.");
            }

            return $this->redirect(['sibley/council']);

        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }

    /**
     * Ajax Validation on Events form
     * 
     */
    public function actionValidation($id=0)
    {
        $model = new AgendaMinutes();
        if ($id > 0)
        {
            $model = $this->findModel($id);
        }
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }
    }

    /**
     * Finds the Agenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgendaMinutes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AgendaMinutes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
