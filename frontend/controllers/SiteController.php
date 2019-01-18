<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Alert;
use frontend\models\Link;
use yii\db\Expression;
use frontend\models\Event;
use frontend\components\FrontendController;
//use grekts\rssParser\rssParser;

/**
 * Site controller
 */
class SiteController extends FrontendController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        //'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        //load alerts
        //SELECT * FROM `alert` WHERE NOW() <= end_dt and NOW() >= start_dt;
        $sd = new Expression('start_dt');
        $ed = new Expression('end_dt');
        $alertModel = Alert::find()
            ->where(['between', 'NOW()', $sd, $ed ])->asArray()->all();
        $alerts = [];
        
        //load links
        $linkModel = Link::find()->asArray()->all();
        $localInterest = [];
        //print_r($linkModel);
        foreach ($linkModel as $idx => $link) {
            //if ($link['group'] == 'Links of Local Interest') {
                $attInfo = [];
                $localInterest['group'] = $link['group'];
                if ($link['type'] == 'file') {
                    $attInfo = Link::getAttachment($link['id']);
                }
                $localInterest['links'][] = [
                    'type' => $link['type'],
                    'name' => $link['name'],
                    'label' => empty($link['label']) ? $link['name'] : $link['label'],
                    'desc' => $link['description'],
                    'id' => $link['id'],
                    'att' => $attInfo
                ];
            //}
        }

        //load rss
        //https://github.com/yurkinx/yii2-rss
        //http://framework.zend.com/manual/2.2/en/modules/zend.feed.reader.html
        $feed=Yii::$app->feed->reader()->import('http://kiwaradio.com/feed');
        $data = array(
            'title'        => $feed->getTitle(),
            'link'         => $feed->getLink(),
            'dateModified' => $feed->getDateModified(),
            'description'  => $feed->getDescription(),
            'language'     => $feed->getLanguage(),
            'entries'      => array(),
        );
        foreach ($feed as $entry) {
            $edata = array(
                'title'        => $entry->getTitle(),
                'description'  => $entry->getDescription(),
                'dateModified' => $entry->getDateModified(),
                'authors'      => $entry->getAuthors(),
                'link'         => $entry->getLink(),
                'content'      => $entry->getContent()
            );
            $data['entries'][] = $edata;
        }

        $yesterday = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')));
        $twoMoFut = date('Y-m-d', mktime(0, 0, 0, date('m')+2, date('d'), date('Y')));
        $events = Event::find()->
        orderBy(['start_dt' => SORT_ASC])->    
        andFilterWhere(['and',
                ['>=', 'start_dt', $yesterday],
                ['<=','start_dt', $twoMoFut]
            ])->
            orFilterWhere(['and',
                ['>=', 'end_dt', $yesterday],
                ['<=','end_dt', $twoMoFut]
            ])->asArray()->all();
        $enhancedEvents = $this->injectRepeatingEvents($events);
            

        //print_r($localInterest);
        //return;
        
        return $this->render('index', [
            //'model' => $model,
            'alerts' => $alertModel,
            'localInterest' => $localInterest,
            'feed' => $data,
            'events' => $enhancedEvents
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        //un comment on production
        //if (Yii::$app->user->can('create_user')) {

            $model = new SignupForm();
            if ($model->load(Yii::$app->request->post())) {
                if ($user = $model->signup()) {
                    if (Yii::$app->getUser()->login($user)) {
                        return $this->goHome();
                    }
                }
            }
    
            return $this->render('signup', [
                'model' => $model,
            ]);
        //} else {
        //    throw new ForbiddenHttpException('You are not allowed to access this page.');
        //}
        
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
