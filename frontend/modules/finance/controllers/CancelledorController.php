<?php

namespace frontend\modules\finance\controllers;

use Yii;
use common\models\finance\CancelledOr;
use common\models\finance\CancelledOrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\finance\Receipt;
use common\models\finance\Customertransaction;
use common\components\Functions;
use common\models\finance\Customerwallet;

/**
 * CancelledorController implements the CRUD actions for CancelledOr model.
 */
class CancelledorController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all CancelledOr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CancelledOrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CancelledOr model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CancelledOr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $get= \Yii::$app->request->get();
        $model = new CancelledOr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //Update Request
            $func= new Functions();
            $receipt_id=$model->receipt_id;
            
            $receipt= Receipt::find()->where(['receipt_id'=>$receipt_id])->one();
            $receipt->cancelled=1; //Means to be cancelled
            $receipt->save(false);
            $custrans=Customertransaction::find()->where(['source' => $receipt_id])->one();
            if ($custrans){
                $wallet = Customerwallet::find()->where(['customerwallet_id' => $custrans->customerwallet_id])->one();
                $customer_id=$wallet->customer_id;
                if($custrans->transactiontype == 1){
                    $source= $receipt_id;
                    $func->SetWallet($customer_id,  $custrans->amount, $source,0);
                }
                else if ($custrans->transactiontype == 0){
                    $source= $receipt_id;
                    $func->SetWallet( $customer_id,  $custrans->amount, $source,1);
                }
            }
            
            return $this->redirect(['/finance/cashier/viewreceipt', 'receiptid' => $model->receipt_id]);
        } else {
            $receipt_id=$get['id'];
            $model->receipt_id= $receipt_id;
            $model->cancel_date=date('Y-m-d H:i:s');
            $model->cancelledby= Yii::$app->user->id;
            if(\Yii::$app->request->isAjax){
                return $this->renderAjax('create', [
                    'model' => $model
                ]);
            }else{
                return $this->render('create', [
                    'model' => $model
                ]);
            }
        }
    }

    /**
     * Updates an existing CancelledOr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cancelled_or_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CancelledOr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CancelledOr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CancelledOr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CancelledOr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
