<?php
use yii\helpers\Html;
use common\models\lab\Visitedpage;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {
    backend\assets\AppAsset::register($this);
    dmstr\web\AdminLteAsset::register($this);
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>

    <?php 
      //for logging pages accessed by the user
      if(isset(Yii::$app->user->identity->profile->rstl_id)) {

            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

            $page_request = Yii::$app->request;
            $page_controller = Yii::$app->controller;
            $pstcId = !empty(Yii::$app->user->identity->profile->pstc_id) ? (int) Yii::$app->user->identity->profile->pstc_id : NULL;

            $logpage = new Visitedpage();            
            $logpage->absolute_url = $page_request->absoluteUrl;
            $logpage->home_url = $page_request->hostInfo;
            $logpage->module =  $page_controller->module->id;
            $logpage->controller = $page_controller->id;
            $logpage->action = $page_controller->action->id;
            $logpage->user_id = (int) Yii::$app->user->identity->profile->user_id;
            $logpage->params = $page_request->queryString;
            $logpage->rstl_id = $rstlId;
            $logpage->pstc_id = $pstcId;
            $logpage->date_visited = date('Y-m-d H:i:s');
            if($logpage->save()) {
              //echo 'save';
            } else {
              print_r($logpage->getErrors());
            }
      } else {
          //return 'Session time out!';
          return Yii::$app->controller->redirect(['/site/login']);
      }

    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>EULIMS | <?= Html::encode($this->title) ?></title>
        <link rel="shortcut icon" href="<?php echo $GLOBALS['frontend_base_uri'] ?>/favicon.ico" type="image/x-icon" />
        <?php $this->head() ?>
        <?php echo PHP_EOL; ?>
        <script type="text/javascript">
            function MessageBox(Message,Title="System Message",labelYes="",labelCancel="", WithCallback=false) {
        var labelButton=(labelYes==="") && (labelCancel==="");
        if(labelButton && !WithCallback){
            bootbox.alert({
                title: Title,
                message: Message,
                size: 'medium'
            });
            return true;
        }else if(!labelButton && !WithCallback){
            bootbox.confirm({
                title: Title,
                message: Message,
                buttons: {
                    cancel: {
                        label: labelCancel,
                        className: 'btn-default'
                    },
                    confirm: {
                        label: labelYes,
                        className: 'btn-success'
                    }
                },
                callback: function (result) {
                    return true;
                }
            });
        }else if(!labelButton && WithCallback){
            bootbox.confirm({
                title: Title,
                message: Message,
                buttons: {
                    cancel: {
                        label: labelCancel,
                        className: 'btn-default'
                                    },
                                    confirm: {
                                        label: labelYes,
                                        className: 'btn-success'
                                    }
                                },
                                callback: function (result) {
                                    try {
                                        if (result) {//yes
                                            ConfirmCallback();
                                        } else {//No
                                            CancelCallBack();
                                        }
                                    } catch (err) {
                                        krajeeDialog.alert(err.message + "!");
                                        /*bootbox.alert({
                                         title: Title,
                                         message: err.message+'!',
                                         size: 'medium'
                                         });
                                         */
                                    }

                                }
                            });
                    }
        }
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-collapse">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
