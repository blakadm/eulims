<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\system\User;
use common\models\system\Package;
use common\models\system\Message;
use yii\helpers\ArrayHelper;
use common\components\Functions;

/* @var $this \yii\web\View */
/* @var $content string */
$Request_URI=$_SERVER['REQUEST_URI'];
$func= new Functions;

if($Request_URI=='/'){//alias ex: http://admin.eulims.local
    $Backend_URI=Url::base();//Yii::$app->urlManager->createUrl('/');
    $Backend_URI=$Backend_URI."/uploads/user/photo/";
}else{//http://localhost/eulims/backend/web
    $Backend_URI=Url::base().'/uploads/user/photo/';
}
Yii::$app->params['uploadUrl']=\Yii::$app->getModule("profile")->assetsUrl."/photo/";//$GLOBALS['upload_url'];
$imagePath=Yii::$app->params['uploadUrl'];
if(Yii::$app->user->isGuest){
    $CurrentUserName="Visitor";
    $CurrentUserAvatar=Yii::$app->params['uploadUrl'] . 'no-image.png';
    $CurrentUserDesignation='Guest';
    $UsernameDesignation=$CurrentUserName;
}else{
    $CurrentUser= User::findOne(['user_id'=> Yii::$app->user->identity->user_id]);
    $CurrentUserName=$CurrentUser->profile ? $CurrentUser->profile->fullname : $CurrentUser->username;
    if($CurrentUser->profile){
        $CurrentUserAvatar=!$CurrentUser->profile->getImageUrl()=="" ? Yii::$app->params['uploadUrl'].$CurrentUser->profile->getImageUrl() : Yii::$app->params['uploadUrl'] . 'no-image.png';
    }else{
        $CurrentUserAvatar=Yii::$app->params['uploadUrl'] . 'no-image.png';
    }
    $CurrentUserDesignation=$CurrentUser->profile ? $CurrentUser->profile->designation : '';
    if($CurrentUserDesignation==''){
       $UsernameDesignation=$CurrentUserName;
    }else{
       $UsernameDesignation=$CurrentUserName.'-'.$CurrentUserDesignation;
    }
}

$Packages= Package::find()->all();
$conditions = ['to' => Yii::$app->user->id, 'status' => 0];
$messages=Message::find()->where($conditions)->all();
$TotalMsg=count($messages);
if($TotalMsg<=0){
    $TotalUnreadMessage="You have no message.";
}elseif($TotalMsg==1){
    $TotalUnreadMessage="You have $TotalMsg unread message.";
}else{
    $TotalUnreadMessage="You have $TotalMsg unread messages.";
}
if($TotalMsg==0){
    $TotalMsg='';
}
$GLOBALS['rstl_id']= 11;
?>

<header class="main-header">
    <?= Html::a(Html::img(Yii::$app->request->baseUrl."/images/logo.png",['title'=>'Enhanced ULIMS','alt'=>'Enhanced ULIMS','height'=>'30px']), Yii::$app->homeUrl, ['class' => 'logo']) ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" onclick="ToggleLeftMenu()" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            
            <ul class="nav navbar-nav">
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"><?= $TotalMsg ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?= $TotalUnreadMessage ?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <?php foreach($messages as $message){ ?>
                                    <a href="<?= Url::to(['/message/message/view','hash'=>$message->hash]) ?>">
                                            <div class="pull-left">
                                                <span><?= $message->sender->username ?></span>
                                            </div>
                                            <h4>
                                                <?= $message->title ?>
                                                <small><i class="fa fa-clock-o"></i> <?= $message->created_at ?></small>
                                            </h4>
                                            <p><?= $message->message ?></p>
                                        </a> 
                                    <?php } ?>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="<?= Url::to($GLOBALS['frontend_base_uri'].'message/message/inbox') ?>">View all Messages</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                    <?php 
                        if (Yii::$app->user->isGuest){
                            $imagename = "no-image.png";
                        }else{
                            $CurrentUser = User::findOne(['user_id'=> Yii::$app->user->identity->user_id]);
                            $imagename = $CurrentUser->profile->image_url;

                              
                            if ($imagename){
                                $imagename = $CurrentUser->profile->image_url;
                            }else{
                                $imagename = "no-image.png";
                            }
                        }
                     ?>  
                 <?= Html::img("/uploads/user/photo/".$imagename, [ 
                    'class' => 'user-image',     
                    'data-target'=>'#w0'
                ]) 
                ?>
                        
                    <span class="hidden-xs"><?= $CurrentUserName ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                        <?php 
                        if (Yii::$app->user->isGuest){
                            $imagename = "no-image.png";
                        }else{
                            $CurrentUser = User::findOne(['user_id'=> Yii::$app->user->identity->user_id]);
                            $imagename = $CurrentUser->profile->image_url;
                        }
                     ?>  
                         <?= Html::img("/uploads/user/photo/".$imagename, [ 
                            'class' => 'img-circle',     
                            'data-target'=>'#w0'
                        ]) 
                        ?>
                            <p>
                                <?= $UsernameDesignation ?> 
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <hr style="margin: 0 0 0 0;padding: 0 0 0 0">
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?php if(Yii::$app->user->can('access-profile') || Yii::$app->user->can('access-his-profile')){ ?>
                                <a href="<?= Url::toRoute('/profile') ?>" class="btn btn-default btn-flat">Profile</a>
                                <?php }else{ ?>
                                <a href="#" class="btn btn-default btn-flat disabled">Profile</a>
                                <?php } ?>
                            </div>
                            <div class="pull-right">
                                <?php if(Yii::$app->user->isGuest){ ?>
                                <?= Html::a(
                                    'Login',
                                    ['/site/login'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?> 
                                <?php }else{ ?>
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?> 
                                <?php } ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less 
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
                !-->
            </ul>
        </div>
    </nav>
</header>