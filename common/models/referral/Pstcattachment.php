<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "tbl_pstcattachment".
 *
 * @property int $pstc_attachment_id
 * @property string $filename
 * @property int $pstc_request_id
 * @property int $uploadedby_user_id
 * @property string $uploadedby_name
 * @property string $upload_date
 */
class Pstcattachment extends Model
{
	
	public $pstc_attachment_id,$filename,$pstc_request_id,$upload_date,$uploadedby_user_id,$uploadedby_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename', 'pstc_request_id', 'uploadedby_user_id', 'uploadedby_name', 'upload_date'], 'required'],
            [['pstc_request_id', 'uploadedby_user_id'], 'integer'],
            [['upload_date'], 'safe'],
            [['filename'], 'string', 'max' => 400],
            [['filename'], 'file', 'extensions' => 'png,jpg,jpeg,pdf','maxSize' => 2048000,'tooBig' => 'Limit is 2,048KB or 2MB','skipOnEmpty'=>false,'wrongExtension'=>'Only {extensions} files  are allowed!'], //2000 * 1024 bytes, Only files with these extensions are allowed: png, jpg, pdf, jpeg.
            [['uploadedby_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pstc_attachment_id' => 'Pstc Attachment ID',
            'filename' => 'Filename',
            'pstc_request_id' => 'Pstc Request ID',
            'uploadedby_user_id' => 'Uploadedby User ID',
            'uploadedby_name' => 'Uploadedby Name',
            'upload_date' => 'Upload Date',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    //public function getRequest()
    //{
    //    return $this->hasOne(Pstcrequest::className(), ['pstc_request_id' => 'pstc_request_id']);
    //}
}
