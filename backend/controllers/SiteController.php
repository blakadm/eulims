<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\system\LoginForm;
use backend\models\UploadForm;
use yii\web\UploadedFile;
use common\models\lab\Loginlogs;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['login','logout','index'],
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function actionUpload()
    {
        var_dump($_POST);
    }
   
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }else{
            return $this->render('index');
        }
        
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $loginlogs = new Loginlogs();
            $loginlogs->user_id = (int) Yii::$app->user->identity->profile->user_id;
            $loginlogs->rstl_id = (int) Yii::$app->user->identity->profile->rstl_id;
            $loginlogs->login_date = date('Y-m-d H:i:s');
            $loginlogs->backend = 1;
            //return $this->goBack();

            if($loginlogs->save()){
                return $this->goBack();
            } else {
                Yii::$app->user->logout();
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
