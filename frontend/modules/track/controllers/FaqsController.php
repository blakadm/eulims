<?php

namespace frontend\modules\track\controllers;
use yii\db\Query;


class FaqsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionTopics($id)
    {
       return $this->render('topics', [
            'id' => $id,
        ]);
    }

}
