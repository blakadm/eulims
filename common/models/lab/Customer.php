<?php
namespace common\models\lab;
use common\models\address\Barangay;
use yii\db\ActiveRecord;
use Yii;
use common\components\Functions;


/**
 * This is the model class for table "tbl_customer".
 *
 * @property int $customer_id
 * @property int $rstl_id
 * @property string $customer_code
 * @property string $customer_name
 * @property double $latitude
 * @property double $longitude
 * @property string $head
 * @property int $barangay_id
 * @property string $address
 * @property string $tel
 * @property string $fax
 * @property string $email
 * @property int $customer_type_id
 * @property int $business_nature_id
 * @property int $industrytype_id
 * @property int $classification_id 
 * @property int $created_at
 * @property int $customer_old_id 
 * @property int $Oldcolumn_municipalitycity_id 
 * @property int $Oldcolumn_district
 * @property int $sync_status
 *
 * @property Customertype $customerType
 * @property Businessnature $businessNature
 * @property Industrytype $industrytype
  * @property Classification $classification
 * @property Request[] $requests
 * @property Request[] $requests0
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_customer';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            // [
            //     'class' => TimestampBehavior::className(),
            //     'createdAtAttribute' => 'created_at',
            //     'updatedAtAttribute' => 'last_update',
            //     'value' => new Expression('NOW()'),
            // ],
                [
                     'class' => 'yii\behaviors\TimestampBehavior',
                     'attributes' => [
                         ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                     ],
                 ],
        ];
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
            [['rstl_id', 'customer_name', 'head', 'tel', 'fax', 'email', 'business_nature_id','classification_id'], 'required'],
            [['rstl_id', 'barangay_id', 'customer_type_id', 'business_nature_id', 'industrytype_id', 'classification_id', 'created_at'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['customer_code'], 'string', 'max' => 11],
            [['customer_name', 'address'], 'string', 'max' => 200],
            [['head'], 'string', 'max' => 100],
            [['tel', 'fax', 'email'], 'string', 'max' => 50],
            [['customer_name', 'head', 'address'], 'unique', 'targetAttribute' => ['customer_name', 'head', 'address']],
            [['customer_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customertype::className(), 'targetAttribute' => ['customer_type_id' => 'customertype_id']],
            [['business_nature_id'], 'exist', 'skipOnError' => true, 'targetClass' => Businessnature::className(), 'targetAttribute' => ['business_nature_id' => 'business_nature_id']],
            [['industrytype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Industrytype::className(), 'targetAttribute' => ['industrytype_id' => 'industrytype_id']],
            [['classification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classification::className(), 'targetAttribute' => ['classification_id' => 'classification_id']],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'rstl_id' => 'Rstl ID',
            'customer_code' => 'Customer Code',
            'customer_name' => 'Customer Name',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'head' => 'Head',
            'barangay_id' => 'Barangay', 
            'tel' => 'Tel',
            'fax' => 'Fax',
            'email' => 'Email',
            'customer_type_id' => 'Customer Type',
            'business_nature_id' => 'Business Nature',
            'industrytype_id' => 'Industry Type',
            'classification_id' => 'Classification',
            'created_at' => 'Created At',
            'customer_old_id' => 'Customer Old ID', 
            'Oldcolumn_municipalitycity_id' => 'Oldcolumn Municipalitycity ID', 
            'Oldcolumn_district' => 'Oldcolumn District', 
            'sync_status'=> 'Sync Status'

        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerType()
    {
        return $this->hasOne(Customertype::className(), ['customertype_id' => 'customer_type_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessNature()
    {
        return $this->hasOne(Businessnature::className(), ['business_nature_id' => 'business_nature_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustrytype()
    {
        return $this->hasOne(Industrytype::className(), ['industrytype_id' => 'industrytype_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['customer_id' => 'customer_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassification()
    {
        return $this->hasOne(Classification::className(), ['classification_id' => 'classification_id']);
    }

    public function getCompleteaddress()
    {
         // return $this->hasOne(Classification::className(), ['classification_id' => 'classification_id']);
         // $address = Barangay::findOne($this->barangay_id);
         // return $address->cityMunicipality->province->region->region.' '.$address->cityMunicipality->province->province.' '.$address->cityMunicipality->city_municipality.' '.$address->barangay;
        if($this->address){
            return $this->address;
        }
        else{
            $address = Barangay::findOne($this->barangay_id);
            if($address)
                return $address->municipalityCity->province->region->reg_desc.', '.$address->municipalityCity->province->prov_desc.', '.$address->municipalityCity->citymun_desc.', '.$address->brgy_desc;
            else
                return "none";
        }

    }
}