<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Agenda;
use frontend\models\AgendaMinutes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use yii\bootstrap4\ActiveForm;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use frontend\models\Audit;
use yii\data\Pagination;

/**
 * AgendaController implements the CRUD actions for Agenda model.
 */
class AgendaController extends Controller
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
     * Refresh nav-menu to current year.
     * @param string $y
     * @return mixed
     */
    public function actionGenerateMenu($yr)
    {
        $startDt = $yr.'-01-01';
        $endDt = $yr.'-12-31';
        $model = Agenda::find()->select(['id','type','date'])->where(
            ['between', 'date', $startDt, $endDt ])->orderBy('date')->all();
        //group results by month
        $meetings = [];
        
        foreach($model as $meeting) {
            $month = date("F", strtotime($meeting->date));
            $meetingLabel = ucfirst($meeting->type) . ' Meeting: ' . date("D M jS", strtotime($meeting->date));
            $meetings[$month][] = [
                'label' => $meetingLabel,
                'id' => $meeting->id
            ];
               
        }
        return $this->renderAjax('menu', [
            'meetings' => $meetings
        ]);
    }

    /**
     * Load the appropriate agenda and minutes based on provided agenda id.
     * @param integer $id
     * @return mixed
     */
    public function actionGetAgenda($id)
    {
        $model = Agenda::find()
            ->select([
                'agenda.id AS aId',
                'agenda.type',
                'agenda.date',
                'agenda.body AS aBody',
                'agenda.create_dt AS aCreateDt',
                'agenda.created_by AS aCreatedBy',
                'user.first_name As uFname',
                'user.last_name As uLname',
                'agenda_minutes.id AS mId',
                'agenda_minutes.attend',
                'agenda_minutes.absent',
                'agenda_minutes.body AS mBody',
                'agenda_minutes.video AS mVideo',
                'agenda_minutes.create_dt AS mCreateDt'
            ])
            ->leftJoin('agenda_minutes', '`agenda_minutes`.`agenda_id` = `agenda`.`id`')
            ->leftJoin('user', '`user`.`id` = `agenda`.`created_by`')
            ->where(['agenda.id'=>$id])->asArray()->one();
            
        //find document if exists
        $pdfFile = '';
        $pdfFileDetails = [];
        $minutesModel = new AgendaMinutes();
        $pdfFileInfo = $minutesModel->getAttachment($model['mId']);
        if (!empty($pdfFileInfo)) {
            $pdfFileDetails = $pdfFileInfo;
            $pdfFile = Yii::getAlias('@web') .'/'. $pdfFileInfo['path'] . $pdfFileInfo['name'];
        }

        return $this->renderAjax('agenda', [
            'agenda' => $model,
            'pdf' => [
                'pdfFile' => $pdfFile,
                'details' => $pdfFileDetails,
            ]
        ]);
    }
    /**
     * Lists all Link models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('/sibley/council');
    }
    /**
     * Displays a single Agenda model.
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
     * Creates a new Agenda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create_agenda')) {
            $model = new Agenda();
            $model->scenario ='create';   //helps with unique meeting on date validation

            if ($model->load(Yii::$app->request->post())) {

                $model->create_dt = date('Y-m-d');
                $model->created_by = Yii::$app->user->identity->id;
                $dateNote = date("l F jS", strtotime($model->date));
                $model->date = date("Y-m-d", strtotime($model->date));
                
                if ($model->save()) {
                    $audit = new Audit();
                    $audit->table = 'agenda';
                    $audit->record_id = $model->id;
                    $audit->field = 'Create';
                    $audit->update_user = $model->created_by;
                    $audit->save(false);
                    Yii::$app->session->setFlash('success', "Agenda successfully set for " . $dateNote . " meeting.");
                } else {
                    Yii::$app->session->setFlash('error', "Failed to set meeting agenda. Error: " . Html::error($model,'date'));
                }
                return $this->redirect(['sibley/council', 'id' => $model->id]);
            }

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

            //make sure only owner or site admin can update
            $user_id = Yii::$app->user->identity->id;
            if ($user_id != $model->created_by && $user_id != 1) {
                Yii::$app->session->setFlash('error', "It does not appear you created this agenda. Update request rejected.");
                return $this->goBack(Yii::$app->request->referrer);
            }

            if ($model->load(Yii::$app->request->post())) {
                $dateNote = date("l F jS", strtotime($model->date));
                $model->date = date("Y-m-d", strtotime($model->date));
                
                if ($model->save()) {
                    $audit = new Audit();
                    $audit->table = 'agenda';
                    $audit->record_id = $model->id;
                    $audit->field = 'Update';
                    $audit->update_user = Yii::$app->user->identity->id;
                    $audit->save(false);
                    Yii::$app->session->setFlash('success', "Agenda successfully updated for $dateNote meeting.");
                } else {
                    Yii::$app->session->setFlash('error', "Failed to update meeting agenda. Error: " . Html::error($model,'date'));
                }
                return $this->redirect(['sibley/council', 'id' => $model->id]);
            }

            $model->date = date("m/d/Y", strtotime($model->date));
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

            //make sure only owner or site admin can update
            $user_id = Yii::$app->user->identity->id;
            
            $model = Agenda::findOne($id);
            if ($model !== null) {

                if ($user_id != $model->created_by && $user_id != 1) {
                    Yii::$app->session->setFlash('error', "It does not appear you created this agenda. Delete request rejected.");
                    return $this->goBack(Yii::$app->request->referrer);
                }

                if ($model->agendaMinutes) {
                    //print_r($model->agendaMinutes);
                    $model->agendaMinutes[0]->id;
                    
                    $pdfFileInfo = $model->getAttachment($id);
                    if (!empty($pdfFileInfo)) {
                        $document = Document::find()->where(['id' => $pdfFileInfo['id']])->one();
                        if ($document) {
                            $sysPath = Url::to('@webroot') . '/' . $document->path . $document->name;
                            if ($document->delete()) {
                                
                                $audit = new Audit();
                                $audit->table = 'document';
                                $audit->record_id = $pdfFileInfo['id'];
                                $audit->field = 'Delete';
                                $audit->update_user = Yii::$app->user->identity->id;
                                $audit->save(false);

                                if(file_exists($sysPath)) {
                                    unlink($sysPath);
                                } 
                            }
                        }
                    }
                }
                if ($model->delete()) {
                    $audit = new Audit();
                    $audit->table = 'agenda';
                    $audit->record_id = $id;
                    $audit->field = 'Delete';
                    $audit->update_user = Yii::$app->user->identity->id;
                    $audit->save(false);
                           
                    Yii::$app->session->setFlash('success', "Agenda successfully deleted. Any associated minutes have also been deleted.");

                } else {
                    Yii::$app->session->setFlash('error', "An error occured while deleting this agenda");
                }
            } else {
                Yii::$app->session->setFlash('error', "Agenda record not found");
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
        $model = new Agenda();
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
     * Search City council meetings
     * return mixed
     */
    public function actionSearch() {
        if (Yii::$app->request->get()) {
            $search = Yii::$app->request->get();

            if (!isset($search['searchTerm'])) {
                throw new BadRequestHttpException('Invalid search term.');
            }

            $searchTerm = $search['searchTerm'];
            //cleanse the term
            $searchTerm = preg_replace("/[^a-zA-Z0-9 \&\/ ]+/", "", $searchTerm);

            //if(!preg_match("/^[a-zA-Z0-9\&\/ ]+$/", $searchTerm) == 1) {
            //    $searchTerm = '';
            //} 
            $query = Agenda::find()
            ->select([
                'agenda.id AS aId',
                'agenda.type',
                'agenda.date',
                'agenda.body AS aBody',
                'agenda.create_dt AS aCreateDt',
                'agenda.created_by AS aCreatedBy',
                'user.first_name As uFname',
                'user.last_name As uLname',
                'agenda_minutes.id AS mId',
                'agenda_minutes.attend',
                'agenda_minutes.absent',
                'agenda_minutes.body AS mBody',
                'agenda_minutes.video AS mVideo',
                'agenda_minutes.create_dt AS mCreateDt'
            ])
            ->leftJoin('agenda_minutes', '`agenda_minutes`.`agenda_id` = `agenda`.`id`')
            ->leftJoin('user', '`user`.`id` = `agenda`.`created_by`')
            ->andFilterWhere(['or',
                ['like', 'agenda.body', $searchTerm ],
                ['like', 'agenda_minutes.body', $searchTerm ]
            ]);

            $countQuery = clone $query;
            $pages = new Pagination([
                'totalCount' => $countQuery->count(),
                'defaultPageSize' => 15
            ]);
            $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy('date')->asArray()
                ->all();
        
            return $this->render('search', [
                    'models' => $models,
                    'pages' => $pages,
            ]);      
        }
    }

    /**
     * 
     $boolean = FALSE;
		
		if (preg_match("/AND|and|And/",$criteria,$matches)) {
			$burst = $matches[0];
			$terms = explode($burst,$criteria);
			$boolean = TRUE;
			$split = "AND";
		} else if (preg_match("/OR|or|Or/",$criteria, $matches)) {
			$burst = $matches[0];
			$terms = explode($burst,$criteria);
			$boolean = TRUE;
			$split = "OR";
		}
		
		$strFmt = "LIKE \"%$criteria%\"";
		
		if (($boolean == TRUE) && ($split == "OR")) {
			$query = "SELECT agenda.agenda_type, agenda.agenda_date, agenda.agenda_detail, 
						minute.minute_title, minute.minute_detail, agenda.agenda_id,
						DATE_FORMAT(agenda.agenda_date, '%m/%d/%Y') AS agenda_date_mdy,
						DATE_FORMAT(agenda.agenda_date, '%Y') AS agenda_year,
						agenda.agenda_region_id
					  FROM agenda
					  LEFT JOIN minute
				  	  ON (minute.agenda_id = agenda.agenda_id)
					  WHERE $legBody
					  MATCH(".implode(',',$fields).") 
					  AGAINST ('$terms[0] $terms[1] $terms[2] $terms[3]' IN BOOLEAN MODE)
					  ORDER BY agenda.agenda_date".$limitPart;
		} elseif (($boolean == TRUE) && ($split == "AND")) {
			$query = "SELECT agenda.agenda_type, agenda.agenda_date, agenda.agenda_detail, 
						minute.minute_title, minute.minute_detail, agenda.agenda_id,
						DATE_FORMAT(agenda.agenda_date, '%m/%d/%Y') AS agenda_date_mdy,
						DATE_FORMAT(agenda.agenda_date, '%Y') AS agenda_year,
						agenda.agenda_region_id
					  FROM agenda
					  LEFT JOIN minute
				  	  ON (minute.agenda_id = agenda.agenda_id)
					  WHERE $legBody
					  MATCH(".implode(',',$fields).") AGAINST ('+$terms[0] +$terms[1] +$terms[2] +$terms[3]' IN BOOLEAN MODE)
					  ORDER BY agenda.agenda_date".$limitPart;
		} else {
			$likeString = '';
			for($i=0; $i < count($fields); $i++){ 
				$likeString .= ($i==0 ? 'AND (' : 'OR '). $fields[$i]." $strFmt "; 
			}
			$query = "SELECT agenda.agenda_id, agenda.agenda_type, agenda.agenda_date, agenda.agenda_detail, 
						minute.minute_title, minute.minute_detail, agenda.agenda_id,
						DATE_FORMAT(agenda.agenda_date, '%m/%d/%Y') AS agenda_date_mdy,
						DATE_FORMAT(agenda.agenda_date, '%Y') AS agenda_year,
						agenda.agenda_region_id
					  FROM agenda
					  LEFT JOIN minute
				  	  ON (minute.agenda_id = agenda.agenda_id) 
					  WHERE $legBody
					  ".$likeString.")
					  ORDER BY agenda.agenda_date ASC".$limitPart;
					  
					  
		}
     */

    /**
     * Finds the Agenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agenda::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
