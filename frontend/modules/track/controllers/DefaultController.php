<?php 

namespace frontend\modules\track\controllers;

use yii\web\Controller;
use common\models\lab\Request;
use common\models\lab\RequestSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\filters\AccessRule;
use Yii;

/**
 * Default controller for the `Lab` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['track', 'error','index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['track','logout','index'],
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

    public function actionIndex()
    {
        
        $model = new Request();
        $post= Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {

            $searchModel = new RequestSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
           
            $req = $_POST['Request']['request_ref_num'];
            $created = $_POST['Request']['created_at'];

            $request = Request::find()->where(['request_ref_num' => $req, 'created_at'=>$created])->one();     

            if ($request){
                return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'request'=>$request,
                ]);
            }else{
                return $this->redirect(['index']);
            }
           
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new Request();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->request_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}