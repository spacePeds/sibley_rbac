<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$js = <<<JS
console.log('initing ajax upload');

$(document).on('change','#Fileinput',function(){
    var imgpreview = DisplayImagePreview(this);
    $(".img_preview").show();
    var url="/sub-page/ajax-upload";
    ajaxFormSubmit(url,'#Ajaxform',function(data){
        //var data=JSON.parse(output);
        if(data.status=='success'){
            $('.im_progress').fadeOut();
        }else{
            alert("Something went wrong.Please try again.");
            $(".img_preview").hide();
        }
    })  
}); 

function DisplayImagePreview(input){
    console.log(input.files);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
    
function ajaxFormSubmit(url, form, callback) {
    var formData = new FormData($(form)[0]);
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        datatype: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            // do some loading options
        },
        success: function(data) {
            console.log('callback:',data);
            callback(data);
        },
        complete: function() {
            // success alerts
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        },
        
    });
}
JS;
?>
<style>
.img_preview {
width: 300px;
position: relative;
display: none;
}
img#img_preview {
width: 100%;
}
.overlay{
position: absolute;
width: 100%;
height: 100%;   
}
.im_progress {
position: absolute;
width: 100%;
height: 100%;
background: #000;
opacity: 0.5;
}
.loader_img{
position: absolute;
top: 50%;
left: 50%;  
}
</style>

<?php $form = ActiveForm::begin([
    'id'=>'Ajaxform',
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<?= $form->field($model, 'ajax_file')->fileInput(['id'=>"Fileinput"]) ?>
<div class="img_preview">
    <div class="im_progress">
        <img class="loader_img" src="/img/ajax-loader.gif">
    </div>
    <img src="" id="img_preview">
</div>
<div class="All_images"></div>

<?php ActiveForm::end(); ?>

<?= $this->registerJs($js); ?>