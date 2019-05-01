<?php

namespace frontend\controllers;

use Yii;
use frontend\models\SubPage;
use frontend\models\Page;
use frontend\models\SubPageSearch;
use frontend\models\Document;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * SubPageController implements the CRUD actions for SubPage model.
 */
class SubPageController extends Controller
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
     * Lists all SubPage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubPageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->redirect(['/']);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubPage model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->redirect(['/']);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SubPage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCreate($id)
    {
        if ((Yii::$app->user->can('create_subPage')) && ($page = Page::findOne($id)) !== null) {
            $model = new SubPage();
            $model->pageLabel = $page->title;
            $model->page_id = $id;

            if ($model->load(Yii::$app->request->post())) {
                $model->last_edit = date('Y-m-d H:i:s');
                $model->created_by = Yii::$app->user->identity->id;
                if ($model->type == 'section') {
                    $model->path = '#' . str_replace(' ', '_', strtolower($model->title));                    
                }

                if ($model->save(false)) {
                    //find any newly uploaded documents and update soft key_link
                    $newId = $model->getPrimaryKey();
                    $documents = Document::find()->where(['table_record' => 'subPage_temp'])->all();
                    foreach($documents as $documentModel) {
                        $documentModel->table_record = 'subPage_' . $newId;
                        $documentModel->update();
                    }

                    Yii::$app->session->setFlash('success', 'Section Insert successful.');  //New id is: ' . $newId
                } else {
                    Yii::$app->session->setFlash('error', 'Sub-page did not save successfully.');
                }
                return $this->redirect([$page->route]);
            } 
                
            
    
            return $this->render('create', [
                'model' => $model,
                'documents' => [],
            ]);
        } else {
            throw new ForbiddenHttpException('You either do not have the correct access or you did not specify the correct parameters');
        }
        
    }

    /**
     * Updates an existing SubPage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update_subPage')) {
            $model = $this->findModel($id);
            $model->last_edit = date('Y-m-d H:i:s');

            $user_id = Yii::$app->user->identity->id;
            $page = Page::findOne($model->page_id);

            // $command = (new \yii\db\Query())
            // ->select(['username'])
            // ->from('user')
            // ->where(['id' => $model->created_by])
            // ->createCommand();
            // $rows = $command->queryAll();

            //append subsection documents (if any)  
            $documents = Document::find()->where(['table_record' => 'subPage_'.$id])->asArray()->all();
                
            
            //if ($user_id != $model->created_by && $user_id != 1 && $model->created_by != 1) {
            //    Yii::$app->session->setFlash('error', "It does not appear you are the person who created this record. Edit request rejected.");
            //    return $this->goBack(Yii::$app->request->referrer);
            //}
            
            if ($model->load(Yii::$app->request->post())) {
                $model->last_edit = date('Y-m-d H:i:s');
                if ($model->type == 'section') {
                    $model->path = '#' . str_replace(' ', '_', strtolower($model->title));                    
                }

                if ($model->save(false)) {
                    //find any newly uploaded documents and update soft key_link
                    $newId = $model->getPrimaryKey();
                    $documents = Document::find()->where(['table_record' => 'subPage_temp'])->all();
                    foreach($documents as $documentModel) {
                        $documentModel->table_record = 'subPage_' . $newId;
                        $documentModel->update();
                    }

                    Yii::$app->session->setFlash('success', 'Section Update successful.');  //New id is: ' . $newId
                } else {
                    Yii::$app->session->setFlash('error', 'Sub-page did not save successfully.');
                }
                return $this->redirect([$page->route]);
                    
                
            }

            return $this->render('update', [
                'model' => $model,
                'documents' => $documents
            ]);
        } else {
            throw new ForbiddenHttpException('You either do not have the correct access or you did not specify the correct parameters');
        }
    }

    // public function actionUpload()
    // {
    //     $model = new UploadForm();

    //     if (Yii::$app->request->isPost) {
    //         $model = UploadedFile::getInstance($model, 'ajax_file');
    //         if ($model->upload()) {
    //             // file is uploaded successfully
    //             return;
    //         }
    //     }

    //     return $this->renderAjax('upload', ['model' => $model]);
    // }

    /**
     * Deletes an existing SubPage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete_subPage')) {
            $this->findModel($id)->delete();
            $documents = Document::find()->where(['table_record' => 'subPage_'.$id])->all();
            foreach($documents as $documentModel) {
                $sysPath = Url::to('@frontend/web/') . $documentModel->path . $documentModel->name;
                $documentModel->delete();
                if(file_exists($sysPath)) {
                    unlink($sysPath);
                } 
            }
            return $this->goBack(Yii::$app->request->referrer);
        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }
    /**
     * Ajax-Upload SubPage model.
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAjaxUpload() {
        $model = new SubPage();
        
        if (Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();
            $model->ajax_file = UploadedFile::getInstance($model, 'ajax_file');
            $model->ajax_file_label = $data['SubPage']['ajax_file_label'];
            //var_dump($model);
            //var_dump($data);
            //var_dump($_FILES);
            if ($model->upload('temp','media/')) {
                // file is uploaded successfully
                return $this->asJson($model->ajaxResult);
            }
            return $this->asJson($model->ajaxResult);
        } else {
            return $this->renderAjax('ajax-upload', ['model' => $model]);
        }
    }

    /**
    * Delete a sub-page attached document
    * @param integer $docId
    * @return json
    */
    public function actionAjaxDelete() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $document = Document::find()->where(['id' => $data['docId']])->one();
            $sysPath = Url::to('@frontend/web/') . $document->path . $document->name;
            $jResult['status'] = 'error';
            $jResult['message'] = '';
            $jResult = [];
            if ($document) {
                if ($document->delete()) {
                    $jResult['status'] = 'success';
                    $jResult['message'] = 'Document Record deleted.';
                    if(file_exists($sysPath)) {
                        unlink($sysPath);
                    } 
                } else {
                    $jResult['message'] = 'Found record but unable to delete.';
                }               
            } else {
                $jResult['message'] = 'Document not found. Unable to delete.';
            }
            return $this->asJson($jResult);
        }
    }
    /**
    * Sort a sub-page attached document
    * @param array 
    * @return json
    */
    public function actionAjaxSort() {
        $data = Yii::$app->request->post();
        $sequence = $data['sequence'];
        $parentPageId = $data['parentPageId'];
        $jResult['payload']['original'] = $sequence;
        $arr = [];
        
        foreach ($sequence as $idx => $subPageKey) {
            if (!empty($subPageKey)) {
                $subIdArr = explode('_', $subPageKey);
                $subPgId = $subIdArr[0];
                $subPgNewSeq = $subIdArr[1];
                $arr[$subPgNewSeq] = $subPgId;
            }
        }
        $count = 1;
        foreach($arr as $idx => $subPageId) {
            if (isset($sequence[$idx]) && $idx == $sequence[$idx]) {
                //skip
                $count++;
                continue;
            }
            $subPgModel = $this->findModel($subPageId);
            $subPgModel->sort_order = $count;
            $subPgModel->save();
            $count++;
        }

        if (!isset($jResult['status'])) {
            //must habe been successful
            //just refresh here
            return $this->redirect(Yii::$app->request->referrer);

            $jResult['status'] = 'success';
            $jResult['message'] = 'Sort completed successfully!';
        }

        return $this->asJson($jResult);
    }
    /**
     * Finds the SubPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubPage::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
