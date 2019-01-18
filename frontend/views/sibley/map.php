<?php
    /* @var $this yii\web\View */

    use yii\helpers\Html;
    use yii\helpers\Url;

    $this->title = 'Sibley Map';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div id="mapContainer" class="container mb-4">
    <div class="col-sm-8 offset-md-2 embed-responsive embed-responsive-16by9">
        <iframe max-width="750" height="550" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps/ms?client=firefox-a&amp;channel=s&amp;hl=en&amp;ie=UTF8&amp;msa=0&amp;ll=43.40062,-95.744863&amp;spn=0.022637,0.038581&amp;t=h&amp;msid=207012372340447616466.00049c182f3bf83754b25&amp;output=embed"></iframe>
        
    </div>
    <h4 class="text-center small">
        View <a href="https://maps.google.com/maps/ms?client=firefox-a&amp;channel=s&amp;hl=en&amp;ie=UTF8&amp;msa=0&amp;ll=43.40062,-95.744863&amp;spn=0.022637,0.038581&amp;t=h&amp;msid=207012372340447616466.00049c182f3bf83754b25&amp;source=embed" style="color:#0000FF;text-align:left">Sibley Tour</a> in a larger map
    </h4>
</div>