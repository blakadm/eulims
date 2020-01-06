<?php

namespace common\models\lab;

use Yii;
use common\models\system\Rstl;
use common\components\Functions;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "tbl_request".
 *
 * @property int $request_id
 * @property string $request_ref_num
 * @property string $request_datetime
 * @property int $rstl_id
 * @property int $lab_id
 * @property int $customer_id
 * @property int $payment_type_id
 * @property string $modeofrelease_ids
 * @property string $discount
 * @property int $discount_id
 * @property int $purpose_id
 * @property string $total
 * @property string $report_due
 * @property string $conforme
 * @property string $contact_num
 * @property string $receivedBy
 * @property int $created_at
 * @property int $posted
 * @property int $status_id
 * @property int $selected 
 * @property int $request_type_id

 *
 * @property string $position 
 * @property string $recommended_due_date 
 * @property string $est_date_completion 
 * @property string $items_receive_by 
 * @property string $equipment_release_date 
 * @property string $certificate_release_date 
 * @property string $released_by 
 * @property string $received_by 
 * @property int $customer_old_id 
 * $property int $payment_status_id
 * 
 * @property Analysis[] $analyses
 * @property Cancelledrequest[] $cancelledrequests
 * @property Generatedrequest[] $generatedrequests
 * @property Lab $lab
 * @property Customer $customer
 * @property Discount $discount0
 * @property Purpose $purpose
 * @property Status $status
 * @property Customer $customer0
 * @property Paymenttype $paymentType
 * @property Sample[] $samples
 * @property Testreport[] $testreports
 * @property Rstl[] $rstl
 */
class Request extends \yii\db\ActiveRecord
{
    //public $customer_name;
    //public $modeofreleaseids;
    //public $request_date;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_request';
    }
    public function behaviors(){
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'created_at',
                ],
                'value' => function() { 
                    return date('U'); // unix timestamp 
                },
            ]
        ];
    }
     public function beforeSave($insert) {
        if ($insert) {
            if(!$this->customer_old_id)
                $this->request_ref_num=NULL;
        }
        return parent::beforeSave($insert);
    }
    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('labdb');
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_datetime', 'rstl_id', 'lab_id', 'customer_id', 'payment_type_id', 'discount_id', 'purpose_id', 'report_due', 'conforme', 'contact_num','receivedBy', 'created_at','request_type_id',], 'required'],
            [['request_datetime', 'report_due', 'recommended_due_date', 'est_date_completion', 'equipment_release_date', 'certificate_release_date'], 'safe'],
            [['rstl_id', 'lab_id', 'customer_id', 'payment_type_id', 'discount_id', 'purpose_id', 'created_at', 'posted', 'status_id', 'selected', 'request_type_id','payment_status_id' ], 'integer'],
            [['discount', 'total', ], 'number'],
            [['request_ref_num', 'modeofrelease_ids', 'conforme', 'contact_num','receivedBy'], 'string', 'max' => 50],
            [['position', 'items_receive_by', 'released_by', 'received_by'], 'string', 'max' => 100],
            [['request_ref_num'], 'unique'],
            /*['report_due', 'compare','compareAttribute'=>'request_date','operator'=>'>=','message'=>'Report Due should not be less than the request date!', 'when' => function($model) {
                return false;
            }],
            ['request_date', 'compare','compareAttribute'=>'report_due','operator'=>'<=','message'=>'Request Date should not be greater than the Report Due!', 'when' => function($model) {
                return false;
            }],
            */
            [['lab_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lab::className(), 'targetAttribute' => ['lab_id' => 'lab_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discount::className(), 'targetAttribute' => ['discount_id' => 'discount_id']],
            [['purpose_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purpose::className(), 'targetAttribute' => ['purpose_id' => 'purpose_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'status_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['payment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paymenttype::className(), 'targetAttribute' => ['payment_type_id' => 'payment_type_id']],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'Request ID',
            'request_ref_num' => 'Request Reference Number',
            'request_datetime' => 'Request Datetime',
            'rstl_id' => 'Rstl ID',
            'lab_id' => 'Laboratory',
            'customer_id' => 'Customer',
            'payment_type_id' => 'Payment Type',
            'modeofrelease_ids' => 'Modeofrelease',
            'modeofreleaseids' => 'Mode of Release',
            'discount' => 'Discount',
            'discount_id' => 'Discount',
            'purpose_id' => 'Purpose',
            'total' => 'Total',
            'report_due' => 'Report Due',
            'conforme' => 'Conforme',
            'receivedBy' => 'Received By',
            'created_at' => 'Tracking code',
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
            'customer_old_id'=>'customer_old_id',
            'contact_num'=>'Conforme Contact Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalyses()
    {
        return $this->hasMany(Analysis::className(), ['request_id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelledrequests()
    {
        return $this->hasMany(Cancelledrequest::className(), ['request_id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneratedrequests()
    {
        return $this->hasMany(Generatedrequest::className(), ['request_id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLab()
    {
        return $this->hasOne(Lab::className(), ['lab_id' => 'lab_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscount0()
    {
        return $this->hasOne(Discount::className(), ['discount_id' => 'discount_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurpose()
    {
        return $this->hasOne(Purpose::className(), ['purpose_id' => 'purpose_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['status_id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer0()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }
                                                                                                                                                    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentType()
    {
        return $this->hasOne(Paymenttype::className(), ['payment_type_id' => 'payment_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSamples()
    {
        return $this->hasMany(Sample::className(), ['request_id' => 'request_id']);
    }

    public function getAnalysis()
    {
       // array(self::HAS_MANY, 'Analysis', array('id'=>'sample_id'), 'through'=>'samps', 'order'=>'sample_id ASC, package DESC'),
        return $this->hasMany(Analysis::className(), ['sample_id' => 'sample_id', 'through'=>'samples']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestreports()
    {
        return $this->hasMany(Testreport::className(), ['request_id' => 'request_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRstl()
    {
        return $this->hasOne(Rstl::className(), ['rstl_id' => 'request_id']);
    }

    public function getPaymentStatusDetails($RequestID){
        $func=new Functions();
        $Connection= Yii::$app->financedb;
        $rows=$func->ExecuteStoredProcedureRows("spGetPaymentStatusDetails(:mRequestID)", [':mRequestID'=> $RequestID], $Connection);
        //Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        return $rows;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferralrequest()
    {
        return $this->hasOne(ReferralRequest::className(), ['request_id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequesttype()
    {
        return $this->hasOne(RequestType::className(), ['request_type_id' => 'request_type_id']);
    }
}
