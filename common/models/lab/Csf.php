<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_csf".
 *
 * @property int $id
 * @property string $name
 * @property string $ref_num
 * @property string $nob
 * @property string $tom
 * @property string $service
 * @property int $d_deliverytime
 * @property int $d_accuracy
 * @property int $d_speed
 * @property int $d_cost
 * @property int $d_attitude
 * @property int $d_overall
 * @property int $i_deliverytime
 * @property int $i_accuracy
 * @property int $i_speed
 * @property int $i_cost
 * @property int $i_attitude
 * @property int $i_overall
 * @property int $recommend
 * @property string $essay
 * @property string $r_date
 */
class Csf extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_csf';
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
            [['name', 'ref_num', 'nob', 'tom', 'service', 'd_deliverytime', 'd_accuracy',  'd_speed', 'd_cost','d_attitude', 'd_overall','i_deliverytime',  'i_accuracy', 'i_speed', 'i_cost', 'i_attitude',  'i_overall', 'recommend'], 'required'],
            [['d_deliverytime', 'd_accuracy', 'd_speed', 'd_cost', 'd_attitude', 'd_overall', 'i_deliverytime', 'i_accuracy', 'i_speed', 'i_cost', 'i_attitude', 'i_overall', 'recommend'], 'integer'],
            [['r_date'], 'safe'],
            [['name', 'essay', 'ref_num'], 'string', 'max' => 500],
            [['nob', 'tom', 'service'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Customer Name',
            'ref_num' => 'Request Reference Number',
            'nob' => 'Nature of Business',
            'tom' => 'Type of Market',
            'service' => 'What services of the RSTL have you availed?',
            'd_deliverytime' => 'Delivery Time',
            'd_accuracy' => 'Correctness and accuracy of test results',
            'd_speed' => 'Speed of Service',
            'd_cost' => 'Cost',
            'd_attitude' => 'Attitude os staff',
            'd_overall' => 'Over-all customer experience',
            'i_deliverytime' => 'Delivery Time',
            'i_accuracy' => 'Correctness and accuracy of test results',
            'i_speed' => 'Speed of Service',
            'i_cost' => 'Cost',
            'i_attitude' => 'Attitude of Staff',
            'i_overall' => 'Over-all customer experience',
            'recommend' => 'Recommend',
            'essay' => 'Essay',
            'r_date' => 'R Date',
        ];
    }

    public function getMarkettype()
    {
        return $this->hasOne(Markettype::className(), ['id' => 'tom']);
    }
    public function getLab()
    {
        return $this->hasOne(Lab::className(), ['lab_id' => 'service']);
    }
}
