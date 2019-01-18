<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'About Sibley';
$this->params['breadcrumbs'][] = $this->title;

//determine counts so we know when to break rows
$electedCount = 0;
$staffCount = 0;
foreach($staff as $key=>$value) {
    if ($staff[$key]['elected']) {
        $electedCount++;
    } else {
        $staffCount++;
    }
}
?>
<?php if (Yii::$app->user->can('update_staff')) : ?>
    <div class="adminFloater shadow-sm p-3 mb-5 bg-white rounded">
        <a href="<?= Url::to(['/page/update']) . '/'.$model['id'] ?>" role="button" class="btn btn-primary">Edit City Page</a>
        <?php if (Yii::$app->user->can('create_staff')): ?>
            <?= Html::a('Create Staff', ['staff/create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
        
    </div>

<?php endif; ?>

<!-- paralex calendar -->
<section id="city-heading" class="p-5">
    
        <div class="row">
            <div class="col">
                <div class="container pt-5 text-left"><h2>City of Sibley</h2></div>
            </div>
        </div>
    
</section>


<header >
    <?= $model['body'] ?>

</header>




<section id="elected" class="">
    <div class="container bg-white">

        <p>Sibley's real pride is in its people. The dedicated, professional, caring individuals who create the growth and spirit of opportunity make the community a great place to live</p>

        <?php //echo '<pre>' . print_r($staff,true) . '</pre>' ?>

        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">City Staff</h5>
                <div class="row">
                    <?php foreach($staff as $key=>$person): ?>
                        <?php 
                        if ($staff[$key]['elected']) {
                            continue;
                        } 
                        ?>
                        
                            <div class="col-md-4 mb-1">
                                
                                <?php if (Yii::$app->user->can('update_staff')) : ?>
                                    <div class="cardEdit">
                                        <a class="btn btn-outline-primary" href="<?= Url::to(['/staff/update/' . $staff[$key]['id']]) ?>" title="Update" aria-label="Update"><i class="fas fa-edit"></i></a>

                                        <?php if (Yii::$app->user->can('delete_staff')): ?>
                                            <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $staff[$key]['id']], [
                                                'class' => 'btn btn-outline-danger',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif;?>
                                
                                <div class="card h-100 text-center">
                                    
                                    <img src="<?= Yii::getAlias('@web') ?><?= isset($person['image']) ? $person['image']['path'] . $person['image']['name'] : '/img/person.png' ?>" alt="" class="card-img-top rounded-circle p-3">
                                    
                                    
                                    <div class="card-body">
                                        <h4 class="card-title"><?= $staff[$key]['first_name'] ?> <?= $staff[$key]['last_name'] ?></h4>
                                        <h6 class="card-subtitle mb-1 text-muted"><?= $staff[$key]['position'] ?></h6>
                                    </div>
                                    <div class="card-footer">
                                        Phone: <?= $staff[$key]['phone'] ?>
                                        Email: <?= $staff[$key]['email'] ?>
                                    </div>
                                </div>

                            </div>
                        
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">City Council</h5>
                <?php foreach($staff as $key=>$person): ?>
                    <?php 
                    if (!$staff[$key]['elected']) {
                        continue;
                    } 
                    ?>
                    <div class="row">
                        <div class="col-md-2">
                            <img src="<?= Yii::getAlias('@web') ?><?= isset($person['image']) ? $person['image']['path'] . $person['image']['name'] : '/img/person.png' ?>" alt="" class="card-img-top rounded-circle m-3 img-fluid" height="100">
                        </div>
                        <div class="col-md-5">
                            <?php if (Yii::$app->user->can('update_staff')) : ?>
                                <div class="staffEdit">
                                    <a class="btn btn-outline-primary" href="<?= Url::to(['/staff/update/' . $staff[$key]['id']]) ?>" title="Update" aria-label="Update"><i class="fas fa-edit"></i></a>

                                    <?php if (Yii::$app->user->can('delete_staff')): ?>
                                        <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model['id']], [
                                            'class' => 'btn btn-outline-danger',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif;?>
                            <h4><?= $staff[$key]['position'] ?></h4>
                            <h5><?= $staff[$key]['first_name'] ?> <?= $staff[$key]['last_name'] ?></h5>
                            <div>Phone: <?= $staff[$key]['phone'] ?></div>
                            <div>Email: <?= $staff[$key]['email'] ?></div>
                        </div>
                        <div class="col-md-5">
                            <div class="border border-primary rounded p-3">
                                <h5>Elected Term:</h5>
                                <p><?= $staff[$key]['termStartFmtd']?> - <?= $staff[$key]['termEndFmtd']?></p>
                            </div>
                        </div>
                    </div>
                
                    
                <?php endforeach; ?>
            </div>
        </div>


        


        
</section>