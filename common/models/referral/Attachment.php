<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "tbl_attachment".
 *
 * @property int $attachment_id
 * @property string $filename
 * @property int $attachment_type 1=OR, 2=Receipt, 3=Test Result
 * @property int $referral_id
 * @property string $upload_date
 * @property int $uploadedby_user_id
 * @property string $uploadedby_name
 *
 * @property Referral $referral
 */
class Attachment extends Model
{
	public $attachment_id,$filename,$attachment_type,$referral_id,$upload_date,$uploadedby_user_id,$uploadedby_name;
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename', 'attachment_type', 'referral_id', 'upload_date', 'uploadedby_user_id', 'uploadedby_name'], 'required'],
            [['attachment_type', 'referral_id', 'uploadedby_user_id'], 'integer'],
            [['upload_date'], 'safe'],
            [['filename'], 'string', 'max' => 400],
			//[['filename'], 'file', 'extensions' => 'png,jpg,pdf','maxFiles'=>5,'skipOnEmpty'=>false],
			[['filename'], 'file', 'extensions' => 'png,jpg,jpeg,pdf','maxSize' => 2048000,'tooBig' => 'Limit is 2,048KB or 2MB','skipOnEmpty'=>false,'wrongExtension'=>'Only {extensions} files  are allowed!'], //2000 * 1024 bytes, Only files with these extensions are allowed: png, jpg, pdf, jpeg.
            [['uploadedby_name'], 'string', 'max' => 100],
            [['referral_id'], 'exist', 'skipOnError' => true, 'targetClass' => Referral::className(), 'targetAttribute' => ['referral_id' => 'referral_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attachment_id' => 'Attachment ID',
            'filename' => 'Filename',
            'attachment_type' => 'Attachment Type',
            'referral_id' => 'Referral ID',
            'upload_date' => 'Upload Date',
            'uploadedby_user_id' => 'Uploadedby User ID',
            'uploadedby_name' => 'Uploadedby Name',
        ];
    }
}
