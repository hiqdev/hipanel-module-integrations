<?php

/**
 * @var \yii\web\View $this
 * @var \hipanel\modules\integrations\models\IntegrationSearch $model
 * @var \hipanel\models\IndexPageUiOptions $uiModel
 * @var \hipanel\modules\server\grid\HubRepresentations $representationCollection
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use hipanel\modules\integrations\grid\IntegrationGridLegend;
use hipanel\modules\integrations\grid\IntegrationGridView;
use hipanel\modules\integrations\models\Integration;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('hipanel.integrations', 'Integrations');
$this->params['breadcrumbs'][] = $this->title;
$subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$createVariants = [
    [
        'url' => ['@integration/create-' . Integration::ACCESS_TYPE_PAYMENT],
        'label' => Yii::t('hipanel.integrations', 'Create payment access'),
        'icon' => 'dollar',
        'visible' => true, // Yii::getAlias('@finance', false)
    ],
    [
        'url' => ['@integration/create-' . Integration::ACCESS_TYPE_DOMAIN],
        'label' => Yii::t('hipanel.integrations', 'Create domain access'),
        'icon' => 'globe',
        'visible' => true, //  Yii::getAlias('@domain', false)
    ],
    [
        'url' => ['@integration/create-' . Integration::ACCESS_TYPE_CERTIFICATE],
        'label' => Yii::t('hipanel.integrations', 'Create certificate access'),
        'icon' => 'shield',
        'visible' => true, // Yii::getAlias('@certificate', false)
    ],
];
?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?php $page->beginContent('main-actions') ?>
            <?php if (true) : ?>
                <div class="btn-group btn-block" style="margin-bottom: 1em;">
                    <button type="button" class="btn btn-block btn-success dropdown-toggle" data-toggle="dropdown">
                        <?= Yii::t('hipanel.integrations', 'Create integration') ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php foreach ($createVariants as $key => $item) : ?>
                            <?php if ($item['visible']) : ?>
                                <li>
                                    <?= Html::beginTag('a', ['href' => Url::to($item['url'])]) ?>
                                        <span class="fa fa-fw fa-<?= $item['icon'] ?? 'cogs' ?>"></span> <?= $item['label'] ?>
                                    <?= Html::endTag('a') ?>
                                </li>
                                <?php if ($key !== array_key_last($createVariants)) : ?>
                                    <li class="divider"></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= IntegrationGridView::widget([
                    'boxed' => false,
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $model,
                    'rowOptions' => function ($model) {
                        return GridLegend::create(new IntegrationGridLegend($model))->gridRowOptions();
                    },
                    'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
