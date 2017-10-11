<?php

/**
 * @var $providers array
 */

$this->registerCss("
.child-brand {
    height: 148px;
    background: #fff;
    margin-bottom: 15px;
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    border: 1px solid #f4f4f4;
}
.child-brand:hover {
    border: 1px solid #62c3df;
}
.child-brand img {
    display: block;
}
");

?>

<div class="row">
    <?php if (!empty($providers)) : ?>
        <?php foreach ($providers as $provider) : ?>
            <div class="col-sm-6">
                <a href="#">
                    <div class="child-brand border-orange">
                        <img src="//html.coderexpert.com/agency/assets/image/brand1.png" alt="">
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-md-12">
            <p class="bg-warning" style="padding: 1em;"><?= Yii::t('hipanel.integrations', 'No available providers.')?></p>
        </div>
    <?php endif; ?>
</div>
