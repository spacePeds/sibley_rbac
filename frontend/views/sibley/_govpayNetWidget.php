<?php
use yii\helpers\Html;
?>

<div class="card text-center my-3">
    <div class="card-body p-2">
        <p class="card-text"><?=$text?> </p>
        <a class="btn btn-primary" target="_blank" href="<?=$link?>" role="button"><i class="far fa-credit-card"></i> Pay</a>
    </div>
    <div class="card-footer text-muted small">
        We've partnered with GovPayNet to make paying fees easier!
    </div>
</div>