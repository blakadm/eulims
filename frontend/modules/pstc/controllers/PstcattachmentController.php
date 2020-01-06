<?php

namespace frontend\modules\pstc\controllers;

use Yii;
use common\models\referral\Pstcattachment;
use common\models\lab\Request;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\components\PstcComponent;
//use linslin\yii2\curl;

/**
 * PstcattachmentController implements the CRUD actions for Pstcattachment model.
 */
class PstcattachmentController extends Controller
{

    public $apiUrl = 'http://localhost/eulimsapi.onelab.ph';

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

    //upload request form
    public function actionUpload()
    {
        set_time_limit(120);
        if(Yii::$app->request->get('local_request_id')){
            $requestId = (int) Yii::$app->request->get('local_request_id');
            $request = $this->findRequest($requestId);
        } else {
            Yii::$app->session->setFlash('error', "Request ID not valid!");
            return $this->redirect(['/pstc/pstcrequest/accepted']);
        }

        if(empty($request->request_ref_num)){
            Yii::$app->session->setFlash('error', "Request reference number not yet generated!");
            return $this->redirect(['/pstc/pstcrequest/view','request_id'=>$request->pstc_request_id,'pstc_id'=>$request->pstc_id]);
        }

        $model = new Pstcattachment();
        if($model->load(Yii::$app->request->post())){
        //if(Yii::$app->request->post()){
            $model->filename = UploadedFile::getInstances($model,'filename');
            //$model->filetype = 1;
            $model->pstc_request_id = $request->pstc_request_id;

            if($model->filename){
                $ref_num = $request->request_ref_num;
                $ch = curl_init();
                foreach ($model->filename as $filename) {
                    $file = $ref_num."_".date('YmdHis')."_PSTC.".$filename->extension; //.".".$filename->extension;
                    $file_data = curl_file_create($filename->tempName,$filename->type,$file);

                    $mi = !empty(Yii::$app->user->identity->profile->middleinitial) ? " ".substr(Yii::$app->user->identity->profile->middleinitial, 0, 1).". " : " "; 
                    $uploaderName = Yii::$app->user->identity->profile->firstname.$mi.Yii::$app->user->identity->profile->lastname;

                    $uploader_data = [
                        //'file_name' => $ref_num."_".date('YmdHis').".".$filename->extension,
                        'file_name' => $file,
                        'request_id' => $request->pstc_request_id,
                        'ref_number' => $ref_num,
                        'user_id' => Yii::$app->user->identity->profile->user_id,
                        'uploader' => $uploaderName,
                        'rstl_id' => Yii::$app->user->identity->profile->rstl_id,
                    ];

                    //$referralUrl='https://eulimsapi.onelab.ph/api/web/referral/attachments/upload_deposit';
                    $uploadUrl=$this->apiUrl.'/api/web/referral/pstcattachments/upload_request';

                    $data = ['file_data'=>$file_data,'uploader_data'=>json_encode($uploader_data)];

                    //hardcoded curl since the linslin\yii2\curl extension doesn't support create file
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, $uploadUrl);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

                    $response = curl_exec($ch);

                    if($response == 1){
                        Yii::$app->session->setFlash('success', "Request form successfully uploaded.");
                        return $this->redirect(['/pstc/pstcrequest/view','request_id'=>$request->pstc_request_id,'pstc_id'=>$request->pstc_id]);
                    } elseif($response == 0) {
                        Yii::$app->session->setFlash('error', "Attachment invalid!");
                        return $this->redirect(['/pstc/pstcrequest/view','request_id'=>$request->pstc_request_id,'pstc_id'=>$request->pstc_id]);
                    } else {
                        Yii::$app->session->setFlash('error', "Can't upload attachment!");
                        return $this->redirect(['/pstc/pstcrequest/view','request_id'=>$request->pstc_request_id,'pstc_id'=>$request->pstc_id]);
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', "Not valid upload attachment!");
                return $this->redirect(['/pstc/pstcrequest/view','request_id'=>$request->pstc_request_id,'pstc_id'=>$request->pstc_id]);
            }
        }
        
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('_formUploadResult', [
                'model' => $model,
            ]);
        }
    }

    //for download request form
    public function actionDownload()
    {
        set_time_limit(120);

        if(Yii::$app->request->get('local_request_id')){
            $requestId = (int) Yii::$app->request->get('local_request_id');
            $request = $this->findRequest($requestId);
        } else {
            Yii::$app->session->setFlash('error', "Request not valid!");
            return $this->redirect(['/pstc/pstcrequest/accepted']);
        }

        if(Yii::$app->request->get('file')){
            $fileId = (int) Yii::$app->request->get('file');
        } else {
            Yii::$app->session->setFlash('error', "Not a valid file!");
            return $this->redirect(['/pstc/pstcrequest/view','id'=>$request->pstc_request_id,'&pstc_id'=>$request->pstc_id]);
        }

        if($fileId > 0){
            $function = new PstcComponent();
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

            $file_download = $function->downloadRequest($request->pstc_request_id,$rstlId,$fileId);

            if($file_download == 'false'){
                Yii::$app->session->setFlash('error', "Can't download file!");
                return $this->redirect(['/pstc/pstcrequest/view','id'=>$request->pstc_request_id,'&pstc_id'=>$request->pstc_id]);
            } else {
                return $this->redirect($file_download);
            }
        }
    }

    protected function findRequest($id)
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Pstcattachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pstcattachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pstcattachment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
