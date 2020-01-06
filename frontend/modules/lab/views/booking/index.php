<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\BookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Booking';
$this->params['breadcrumbs'][] = ['label' => 'Manage Booking', 'url' => ['/lab/booking/manage']];
$this->params['breadcrumbs'][] = $this->title;

$DragJS = <<<EOF
/* initialize the external events
-----------------------------------------------------------------*/
$('#external-events .fc-event').each(function() {
    // store data so the calendar knows to render an event upon drop
    $(this).data('event', {
        title: $.trim($(this).text()), // use the element's text as the event title
        stick: true // maintain when user navigates (see docs on the renderEvent method)
    });
    // make the event draggable using jQuery UI
    $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
    });
});
EOF;
$this->registerJs($DragJS);

$JSDayClick = <<<EOF
function (date, allDay, jsEvent, view) { 
    alert('haha');
}
EOF;

$JSCode = <<<EOF
function(start, end) {
    var title = prompt('Event Title:');
    var eventData;
    if (title) {
        eventData = {
            title: title,
            start: start,
            end: end
        };
        $('#w0').fullCalendar('renderEvent', eventData, true);
    }
    $('#w0').fullCalendar('unselect');
}
EOF;
$JSDropEvent = <<<EOF
function(date) {
    alert("Dropped on " + date.format());
    if ($('#drop-remove').is(':checked')) {
        // if so, remove the element from the "Draggable Events" list
        $(this).remove();
    }
}
EOF;
$JSEventClick = <<<EOF
function(calEvent, jsEvent, view) {


    // $.ajax({
    //     url: Url::to(['/inventory/products/dayClickCalendarEvent']),
    //     dataType: 'json',
    //     data: { 
    //           title: calEvent.title,
             
    //     },
        
    // });

    // alert('Event: id is  ' + calEvent.id);
    // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
    // alert('View: ' + view.name);

    // change the border color just for fun
    // $(this).css('border-color', 'red');

    window.open( "/inventory/equipmentservice/update?id=" + calEvent.id, "_blank", "" );
}
EOF;

?>
<div class="booking-index">

    <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Create Booking', ['value'=>'/lab/booking/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Booking"),'id'=>'btnBooking','onclick'=>'addBooking(this.value,this.title)'])?>
    
  <?php $rstl_id= Yii::$app->user->identity->profile->rstl_id;?>  

   <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
          'events'=> Url::to(["/lab/booking/jsoncalendar?id=$rstl_id"]),
          'clientOptions' => [
                       // 'selectable' => true,
                        'selectHelper' => true,
                      //  'droppable' => true,
                        'editable' => true,
                        'height'=>500,
                        // 'select' => new JsExpression($JSCode),
                        // 'drop' => new JsExpression($JSDropEvent),
                        'eventClick' => new JsExpression($JSEventClick),
                        // 'dayClick'=>new \yii\web\JsExpression($JSDayClick),
                        'defaultDate' => date('Y-m-d')
                  ],
      ));
    ?>
</div>
<script type="text/javascript">
    function addBooking(url,title){
        LoadModal(title,url,'true','700px');
    }
    $('#btnBooking').click(function(){
        $('.modal-title').html($(this).attr('title'));
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
</script>
