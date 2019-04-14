<?php
//echo '<pre>' . print_r($headerImages,true) . '</pre>';
?>
<?php foreach($headerImages as $imgType => $images): ?>
    <?php if ($imgType != 'parallax'): ?>
        <?php foreach($images as $idx => $image): ?>
            <?php
            //echo '<pre>' . print_r($image,true) . '</pre>';
            ?>
            <img src="<?=$image['image']['image_path']?>" height="<?=$image['image']['height']?>" class="<?=$image['class']?>" style="<?=$image['style']?>">
        <?php endforeach; ?>
    <?php endif; ?>
<?php endforeach; ?>