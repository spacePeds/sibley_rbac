<?php foreach($images as $idx => $image): ?>
    <section class="p-3" style="<?=$image['style']?>">
        <div class="parallax-overlay" style="background: rgba(0,0,0,<?=$image['image']['brightness']?>);">
            <div class="row">
                <div class="col">
                    <div class="container pt-5">
                </div>
            </div>
            </div>
        </div>
    </section>
<?php endforeach; ?>