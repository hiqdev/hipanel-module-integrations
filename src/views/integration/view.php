<?php

use hipanel\modules\integrations\grid\IntegrationGridView;
use hipanel\modules\integrations\menus\IntegrationDetailMenu;
use hipanel\modules\integrations\models\Provider;
use hipanel\widgets\Box;
use hipanel\widgets\MainDetails;
use yii\helpers\Html;
use yii\helpers\Json;

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.integrations', 'Integrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$onlyColumns = Json::decode($model->provider->data);
unset($onlyColumns['api_type_id']);

?>
<div class="row">
    <div class="col-md-3">
        <?= MainDetails::widget([
            'title' => $model->pageTitle,
            'icon' => 'fa-share-alt',
            'subTitle' => Html::a($model->client, ['@client/view', 'id' => $model->client_id]),
            'menu' => IntegrationDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']),
        ]) ?>
    </div>
    <div class="col-md-4">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel.integrations', 'Information about integration') ?></h3>
            </div>
            <div class="box-body no-padding">
                <?= IntegrationGridView::detailView([
                    'model' => $model,
                    'boxed' => false,
                    'columns' => array_merge(['name', 'access'], array_keys($onlyColumns)),
                ]) ?>
            </div>
        </div>
    </div>
</div>
