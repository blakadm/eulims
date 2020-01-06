<?php
namespace frontend\modules\lab\components;

use common\models\lab\Lab;
use common\models\lab\Customer;
use common\models\lab\Discount;
use common\models\lab\Purpose;
use common\models\lab\Status;
use common\models\lab\Paymenttype;
/**
 * Description of eRequest
 *
 * @author OneLab
 */
class eRequest extends \common\models\lab\Request{
    public $customer_name;
    public $modeofreleaseids;
    public $request_date;
    public $testcategory_id;
    public $sampletype_id;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_datetime', 'rstl_id', 'lab_id', 'customer_id', 'payment_type_id', 'discount_id', 'purpose_id', 'report_due', 'conforme', 'receivedBy', 'created_at','modeofreleaseids', 'contact_num'], 'required'],
            [['request_type_id'],'required','message'=>'Request Type is required'],
            [['request_datetime', 'report_due','request_date', 'recommended_due_date', 'est_date_completion', 'equipment_release_date', 'certificate_release_date'], 'safe'],
            [['rstl_id', 'lab_id', 'customer_id', 'payment_type_id', 'discount_id', 'purpose_id', 'created_at', 'posted', 'status_id', 'selected', 'request_type_id','payment_status_id'], 'integer'],
            [['discount', 'total'], 'number'],
            [['request_ref_num', 'modeofrelease_ids', 'conforme', 'receivedBy', 'contact_num'], 'string', 'max' => 50],
            [['position', 'items_receive_by', 'released_by', 'received_by'], 'string', 'max' => 100],
            [['request_ref_num'], 'unique'],
            ['report_due', 'compare','compareAttribute'=>'request_date','operator'=>'>=','message'=>'Report Due should not be less than the request date!', 'when' => function($model) {
                return false;
            }],
            ['request_date', 'compare','compareAttribute'=>'report_due','operator'=>'<=','message'=>'Request Date should not be greater than the Report Due!', 'when' => function($model) {
                return false;
            }],
            
            [['lab_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lab::className(), 'targetAttribute' => ['lab_id' => 'lab_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discount::className(), 'targetAttribute' => ['discount_id' => 'discount_id']],
            [['purpose_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purpose::className(), 'targetAttribute' => ['purpose_id' => 'purpose_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'status_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['payment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paymenttype::className(), 'targetAttribute' => ['payment_type_id' => 'payment_type_id']],
        ];
    }
    
    public function compareDates($attribute,$params)
    {
        //$end_date = strtotime($this->report_due);
        //$start_date = strtotime($this->request_datetime);
        $this->addError($attribute, 'Report Due must not be less than the request date!');
        return true;
    }
}
