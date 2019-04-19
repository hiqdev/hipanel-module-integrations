<?php

/** @var array $typeVariants */

use yii\bootstrap\Html;

$this->registerCss('
    .provider-variants {
        display: grid;
        grid-template-rows: 1fr 1fr 1fr;
        grid-template-columns: 1fr 1fr 1fr;
        grid-gap: 2vw;
        align-items: center;
    }
    
    .provider-variants .provider-variant .without-image {
        font-size: larger;
    }
    
    .provider-variants .provider-variant a {
        display: block;
        padding: 3em 2em;
        text-align: center;
        cursor: pointer;
        border: 2px solid transparent;
        border-radius: 2px;
        background-color: #fcfcfc;
    }
    
    .provider-variants .provider-variant a img {
        -webkit-filter: grayscale(100%);
        filter: grayscale(100%);
        transition: .1s;
    }
    
    .provider-variants .provider-variant a {
        text-transform: uppercase;
        font-weight: bold;
        color: #666666;
    }
    
    .provider-variants .provider-variant:hover a {
        border-color: #3c8dbc;
    }
    
    .provider-variants .provider-variant:hover a img {
        -webkit-filter: grayscale(0);
        filter: grayscale(0);
    }
    
    .provider-variants .provider-variant:hover a {
        color: #3c8dbc;
    }
');

?>

<div class="dropdown">
    <a type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">
        <?= Yii::t('hipanel.integrations', 'Create integration') ?> <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <?php foreach ($typeVariants as $type => $item) : ?>
            <?php if ($item['visible']) : ?>
                <li>
                    <?= Html::beginTag('a', [
                        'href' => '#',
                        'data' => [
                            'toggle' => 'modal',
                            'target' => '#choose-provider-modal-for-' . $type,
                        ],
                    ]) ?>
                        <span class="fa fa-fw fa-<?= $item['icon'] ?? 'cogs' ?>"></span> <?= $item['label'] ?>
                    <?= Html::endTag('a') ?>
                </li>
                <?php if ($type !== array_key_last($typeVariants)) : ?>
                    <li class="divider"></li>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
