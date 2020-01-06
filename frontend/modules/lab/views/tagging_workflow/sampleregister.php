<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\models\lab\SampleregisterUser;
use frontend\modules\lab\models\Sampleextend;
use kartik\grid\DataColumn;
use kartik\daterange\DateRangePicker;
use kartik\export\ExportMenu;
use common\models\lab\Lab;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;


$this->title = 'Sample Register';
$this->params['breadcrumbs'][] = $this->title;
?>