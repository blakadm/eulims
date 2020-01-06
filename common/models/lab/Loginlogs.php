<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_loginlogs".
 *
 * @property int $loginlogs_id
 * @property int $user_id
 * @property int $rstl_id
 * @property string $login_date
 */
class Loginlogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_loginlogs';
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
            [['user_id', 'rstl_id', 'login_date'], 'required'],
            [['user_id', 'rstl_id', 'backend'], 'integer'],
            [['login_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'loginlogs_id' => 'Loginlogs ID',
            'user_id' => 'User ID',
            'rstl_id' => 'Rstl ID',
            'login_date' => 'Login Date',
			'backend' => 'Backend',
        ];
    }
}
