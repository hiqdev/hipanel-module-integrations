<?php

use hipanel\modules\integrations\grid\IntegrationGridView;
use hipanel\modules\integrations\menus\IntegrationDetailMenu;
use hipanel\widgets\Box;
use hipanel\widgets\ClientSellerLink;
use yii\helpers\Html;

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.integrations', 'Integrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <dvi class="col-md-3">

        <?php Box::begin([
            'options' => [
                'class' => 'box-solid',
            ],
            'bodyOptions' => [
                'class' => 'no-padding',
            ],
        ]) ?>
        <p class="text-center" style="margin-top: 3em">
            <span class="profile-user-name"><?= join(' | ', [$model->name, Html::a($model->provider, '#')]) ?></span>
        </p>

        <div class="profile-usermenu">
            <?= IntegrationDetailMenu::widget(['model' => $model]) ?>
        </div>
        <?php Box::end() ?>
    </dvi>
    <div class="col-md-4">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel.integrations', 'Information about integration') ?></h3>
            </div>
            <div class="box-body">
                <?= IntegrationGridView::detailView([
                    'model' => $model,
                    'boxed' => false,
                    'columns' => [
                        'name',
                        'provider',
                        'url:url',
                        'login',
                        'access'
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
