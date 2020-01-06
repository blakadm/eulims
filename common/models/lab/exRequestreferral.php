<?php

/*
 *
 * Module: exReferralrequest * 
 */

namespace common\models\lab;
use Yii;
use common\models\lab\Request;
/**
 *
 * @author OneLab
 */
class exRequestreferral extends Request{
	 
	public $sample_received_date;
	public $customer_name;
    public $modeofreleaseids;
    public $request_date;
    public $sampletype_id;
	
	/**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rstl_id', 'lab_id', 'customer_id', 'payment_type_id', 'discount_id', 'purpose_id', 'report_due', 'conforme', 'receivedBy', 'created_at','request_type_id','sample_received_date','modeofrelease_ids'], 'required'],
			[['request_datetime', 'report_due', 'recommended_due_date', 'est_date_completion', 'equipment_release_date', 'certificate_release_date'], 'safe'],
            [['rstl_id', 'lab_id', 'customer_id', 'payment_type_id', 'discount_id', 'purpose_id', 'created_at', 'posted', 'status_id', 'selected', 'request_type_id','payment_status_id','referral_id'], 'integer'],
            [['discount', 'total'], 'number'],
            [['request_ref_num', 'modeofrelease_ids', 'conforme', 'receivedBy'], 'string', 'max' => 50],
            [['position', 'items_receive_by', 'released_by', 'received_by'], 'string', 'max' => 100],
            [['request_ref_num'], 'unique'],
            [['lab_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lab::className(), 'targetAttribute' => ['lab_id' => 'lab_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discount::className(), 'targetAttribute' => ['discount_id' => 'discount_id']],
            [['purpose_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purpose::className(), 'targetAttribute' => ['purpose_id' => 'purpose_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'status_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['payment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paymenttype::className(), 'targetAttribute' => ['payment_type_id' => 'payment_type_id']],
        ];
    }
	
	/*public function beforeSave($insert) {
        if ($insert) {
            $this->request_datetime='0000-00-00 00:00:00';
			$this->request_ref_num=NULL;
        }
        return parent::beforeSave($insert);
    }*/
	
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
			'request_id' => 'Request ID',
            'request_ref_num' => 'Request Ref Num',
            'request_datetime' => 'Request Datetime',
            'rstl_id' => 'Rstl ID',
            'lab_id' => 'Laboratory',
            'customer_id' => 'Customer',
            'payment_type_id' => 'Payment Type',
            'modeofrelease_ids' => 'Mode of release',
            'modeofreleaseids' => 'Mode of Release',
            'discount' => 'Discount',
            'discount_id' => 'Discount',
            'purpose_id' => 'Purpose',
            'total' => 'Total',
            'report_due' => 'Estimated Due Date',
            'conforme' => 'Conforme',
            'receivedBy' => 'Received By',
            'created_at' => 'Created At',
            'posted' => 'Posted',
            'status_id' => 'Status',
            //'customer_name'=>'Customer Name',
            'selected' => 'Selected',
            'request_type_id' => 'Request Type', 
            'position' => 'Position',
            'recommended_due_date' => 'Recommended Due Date',
            'est_date_completion' => 'Estimated Date of Completion',
            'items_receive_by' => 'Items Receive By',
            'equipment_release_date' => 'Date Release of Equipment',
            'certificate_release_date' => 'Date Release of Certificate',
            'released_by' => 'Released By',
            'received_by' => 'Received By',
            'payment_status_id'=>'Payment Status',
            'sample_received_date' => 'Sample Received Date',
			'referral_id' => 'Referral ID',
        ];
		
    }
}
