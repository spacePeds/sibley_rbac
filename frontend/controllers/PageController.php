<?php

namespace frontend\controllers;

use Yii;
use frontend\models\PageWithCategories;
use yii\web\ForbiddenHttpException;
use frontend\models\Category;
use frontend\models\Page;
use frontend\models\PageSearch;
use frontend\models\ImageAsset;
use frontend\models\UploadForm;
use frontend\models\HeaderImage;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\User;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\db\Query;
use frontend\models\Audit;
use frontend\models\Business;
use yii\helpers\Json;
use frontend\models\SubPage;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
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
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //return $this->render('index', [
        //    'searchModel' => $searchModel,
        //    'dataProvider' => $dataProvider,
        //]);
        return $this->redirect(['/']);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        //return $this->render('view', [
        //    'model' => $this->findModel($id),
        //]);

        $model = $this->findModel($id);

        return $this->redirect([$model->route]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create_page')) {
            $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();
            $model = new PageWithCategories();

            if ($model->load(Yii::$app->request->post())) {
                $model->last_edit_dt = date('Y-m-d H:i:s');
                $model->user_id = $user_id;
                if ($model->save()) {
                
                    //special FB sub-page?
                    if ($model->sub_pages > 0 && !empty($model->fb_token) && !empty($model->fb_link)) {
                        $subPage = new SubPage();
                        $subPage->page_id = $this->id;  //Yii::$app->db->getLastInsertID();
                        $subPage->title = 'Facebook';
                        $subPage->path = '#facebook';
                        $subPage->type = 'fb';
                        $subPage->last_edit = date('Y-m-d H:i:s');
                        $subPage->created_by = $user_id;
                        $subPage->save();
                    }

                    $audit = new Audit();
                    $audit->table = 'page';
                    $audit->record_id = $model->id;
                    $audit->field = 'Create';
                    $audit->update_user = Yii::$app->user->identity->id;
                    $audit->save(false);
                    $model->saveCategories();

                    Yii::$app->session->setFlash('success', 'Insert successful.');
                    return $this->redirect([Yii::$app->request->post('route')]);
                }
            }

    /*
            if ($model->load(Yii::$app->request->post())) {
                $model->last_edit_dt = date('Y-m-d H:i:s');
                $model->user_id = 1;    
                $valid = $model->validate();
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($model->save()) {
                            $model->saveCategories();
                            Yii::$app->session->setFlash('success', 'Insert successful.');
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        Yii::$app->session->setFlash('warning', 'Insert failed.' .print_r($e,false));
                        $transaction->rollBack();
                        return $this->redirect(['']);
                    }
                }
            }
    */
            return $this->render('create', [
                'model' => $model,
                'role' => \Yii::$app->authManager->getRolesByUser($user_id),
                'categories' => Category::getAvailableCategories(),
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }

    /**
     * View / Update Generic assets.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionMultiple()
    {
        $model = new UploadForm();
        $assets = $this->retrieveImgAssets();
        $user_id = Yii::$app->user->identity->id;

        if (Yii::$app->user->can('update_page')) {
            if (Yii::$app->request->isPost) {
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

                $sysPath = '/' . Yii::$app->params['assetPath'];
                if (!is_dir(Url::to('@webroot') . $sysPath)) {
                    mkdir(Url::to('@webroot') . $sysPath); 
                }

                if ($model->upload(Yii::$app->params['assetPath'])) {
                    //file uploaded successfully
                    Yii::$app->session->setFlash('success', "Successfully Uploaded image(s) to $sysPath.");
                    return $this->redirect([Yii::$app->request->post('route')]);
                } else {             
                    return $this->redirect([Yii::$app->request->post('route')]);
                }
            } 
            else
            {
                return $this->render('multiple',[
                    'upload' => $model,
                    'role' => \Yii::$app->authManager->getRolesByUser($user_id),
                    'assets' => $assets
                ]);
            }
        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update_page')) {
        
            $user_id = User::findByUsername(Yii::$app->user->identity->username)->getId();
            $model = PageWithCategories::findOne($id);

            if ($model->load(Yii::$app->request->post())) {
                $model->last_edit_dt = date('Y-m-d H:i:s');
                $model->user_id = $user_id;    //($model->getUser()) ? $model->getUser()->id :
                if ($model->save()) {
                    $audit = new Audit();
                    $audit->table = 'page';
                    $audit->record_id = $model->id;
                    $audit->field = 'Update';
                    $audit->update_user = Yii::$app->user->identity->id;
                    $audit->save(false);
                    $model->saveCategories();

                    //special FB sub-page?
                    SubPage::deleteAll(['page_id' => $model->id,'type' => 'fb']);
                    if ($model->sub_pages > 0 && !empty($model->fb_token) && !empty($model->fb_link)) {
                        $subPage = new SubPage();
                        $subPage->page_id = $model->id;
                        $subPage->title = 'Facebook';
                        $subPage->path = '#facebook';
                        $subPage->type = 'fb';
                        $subPage->last_edit = date('Y-m-d H:i:s');
                        $subPage->created_by = $user_id;
                        $subPage->save();
                    }

                    Yii::$app->session->setFlash('success', 'Update of "'.Yii::$app->request->post('PageWithCategories')['title'].'" successful.');
                    //echo '<pre>' . print_r($_POST, true) . '</pre>';
                    return $this->redirect(Yii::$app->request->post('PageWithCategories')['route']);
                }
                Yii::$app->session->setFlash('error', 'Update of "'.$_POST['PageWithCategories']['title'].'" failed.');
            }
            
            $model->loadCategories();
            
            $headImages = HeaderImage::find()->where(['image_idx'=>'page_'.$id])->asArray()->all();
            
            return $this->render('update', [
                'model' => $model,
                'categories' => Category::getAvailableCategories(),
                'role' => \Yii::$app->authManager->getRolesByUser($user_id),
                'headImages' => $headImages
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete_page')) {
        
            $model = $this->findModel($id);

            if ($model->delete()) {
                $audit = new Audit();
                $audit->table = 'page';
                $audit->record_id = $model->id;
                $audit->field = 'Delete';
                $audit->update_user = Yii::$app->user->identity->id;
                $audit->save(false);
                Yii::$app->session->setFlash('success', 'Page deleted successfully.');
            }

            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException('You do not have permission to perform this action.');
        }
    }

    /**
     * Deletes an existing Image asset and model record.
     * If deletion is successful, the browser will be redirected back to asset page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete2($id)
    {
        if (($model = ImageAsset::findOne($id)) !== null) {

            //make sure only owner or site admin can delete
            $user_id = Yii::$app->user->identity->id;
            if ($user_id != $model->created_by && $user_id != 1 && $model->created_by != 1) {
                Yii::$app->session->setFlash('error', "It does not appear you are the owner of this asset. Delete request rejected.");
                return $this->goBack(Yii::$app->request->referrer);
            }

            $imgName = $model->name;
            if ($model->delete()) {
                $sysPath = '/' . Yii::$app->params['assetPath'];
                if (file_exists(Url::to($sysPath) . $imgName)) {
                    if (!unlink(Url::to($sysPath.$imgName))) {
                        Yii::$app->session->setFlash('warning', 'Unable to delete image: '. Url::to($sysPath) . $imgName.'. Please conteact your website administrator to correct this.');                        
                    }                   
                }
                Yii::$app->session->setFlash('success', 'Image '.$imgName .' deleted successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to delete record for image '.$imgName .'.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Unable to locate record associated with image. Please conteact your website administrator to correct this.');
        }
        return $this->redirect(['multiple']);
    }

    /**
     * Retrieve organization details matching provided category ids
     * @param array categories
     */
public function actionAjaxOrganizationDetails() {

    $posted = Yii::$app->request->post();
    if (isset($posted['categories'])) {
        $categories = $posted['categories'];

        $result['orgs'] = '';
        $result['status'] = 'error';
        $result['message'] = 'No matching Organizations';
        
        $organizations = Business::find()
            ->select('business.id as bid, business.*, business_category.*, contact_method.*')
            ->leftJoin('business_category', '`business_category`.`business_id` = `business`.`id`')
            ->leftJoin('contact_method', '`contact_method`.`business_id` = `business`.`id`')
            ->where(['in', 'business_category.category_id', $categories])->asArray()->all();

        if (!empty($organizations)) {
            foreach ($organizations as $organization) {
                $bid = $organization['bid'];
                $page['linkedOrganizations'][$bid]['name'] = $organization['name'];
                $page['linkedOrganizations'][$bid]['address1'] = $organization['address1'];
                $page['linkedOrganizations'][$bid]['address2'] = $organization['address2'];
                $page['linkedOrganizations'][$bid]['city'] = $organization['city'];
                $page['linkedOrganizations'][$bid]['state'] = $organization['state'];
                $page['linkedOrganizations'][$bid]['zip'] = $organization['zip'];
                $page['linkedOrganizations'][$bid]['url'] = $organization['url'];
                $page['linkedOrganizations'][$bid]['note'] = $organization['note'];
                $page['linkedOrganizations'][$bid]['member'] = $organization['member'];
                $page['linkedOrganizations'][$bid]['contact'][] = [
                    'method' => $organization['method'],
                    'contact'=> $organization['contact'],
                    'description' => $organization['description']
                ];
            }
            return $this->renderAjax('/sibley/_linkedOrg', ['page' => $page,]);
            //$result['status'] = 'success';
            //$result['message'] = '';
        }
    }
    //echo Json::encode($result);
}

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    /**
     * Search Asset directory for all image assets
     * Return a list of found images and some details about images
     * @return array
     */
    protected function retrieveImgAssets() 
    {
        
        $assets = (new Query())
            ->select(['id','path','type','size','name','DATE_FORMAT(created_dt,"%m/%d/%Y") as upldDt'])
            ->from('image_asset')
            ->all();
        if (count($assets) < 1) {
            $assets = [
                0 => [
                    'id' => 0,
                    'name' => 'placeholder-image.jpg',
                    'path' => 'img/assets/',    //Url::to('@web/img/assets/')
                    'size' => 0,
                    'type' => '',
                    'upldDt' => ''
                ]
            ];
        }
        //foreach ($assets as $idx => $asset) {
        //    //echo $asset . '<br>';
        //    if ($asset == 'size') {
        //        $assets[$idx]['size'] = $this->formatSizeUnits($asset['size']);
        //    }           
        //}

        return $assets;
    }
    /**
     * Helper function to provide user friendly fiel siZe
     * Snippet from PHP Share: http://www.phpshare.org
     */
    protected function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
