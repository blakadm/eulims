<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_visited_page".
 *
 * @property int $visited_page_id
 * @property string $absolute_url
 * @property string $home_url
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property string $params
 * @property int $user_id
 * @property int $rstl_id
 * @property int $pstc_id
 * @property string $date_visited
 */
class Visitedpage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_visited_page';
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
            [['absolute_url', 'home_url', 'user_id', 'rstl_id', 'date_visited'], 'required'],
            [['user_id', 'rstl_id', 'pstc_id'], 'integer'],
            [['date_visited'], 'safe'],
            [['absolute_url'], 'string', 'max' => 500],
            [['home_url', 'controller', 'action'], 'string', 'max' => 150],
            [['module'], 'string', 'max' => 100],
            [['params'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'visited_page_id' => 'Visited Page ID',
            'absolute_url' => 'Absolute Url',
            'home_url' => 'Home Url',
            'module' => 'Module',
            'controller' => 'Controller',
            'action' => 'Action',
            'params' => 'Params',
            'user_id' => 'User ID',
            'rstl_id' => 'Rstl ID',
            'pstc_id' => 'Pstc ID',
            'date_visited' => 'Date Visited',
        ];
    }
}
