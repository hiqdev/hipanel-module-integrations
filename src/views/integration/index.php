<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\integrations\grid\IntegrationGridLegend;
use hipanel\modules\integrations\grid\IntegrationGridView;
use hipanel\modules\integrations\grid\IntegrationRepresentations;
use hipanel\modules\integrations\models\IntegrationSearch;
use hipanel\modules\integrations\widgets\CreateIntegrationButton;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var View $this
 * @var array $providerTypes
 * @var IntegrationSearch $model
 * @var IndexPageUiOptions $uiModel
 * @var IntegrationRepresentations $representationCollection
 * @var ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('hipanel.integrations', 'Integrations');
$this->params['breadcrumbs'][] = $this->title;
$subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>

    <?php $page->beginContent('main-actions') ?>
        <?php if (Yii::$app->user->can('integration.create')) : ?>
            <?= CreateIntegrationButton::widget([
                'allowedTypes' => $providerTypes,
            ]) ?>
        <?php endif; ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?php if (Yii::$app->user->can('integration.update')) : ?>
            <?= $page->renderBulkButton('@integration/disable', Yii::t('hipanel', 'Disable')) ?>
            <?= $page->renderBulkButton('@integration/enable', Yii::t('hipanel', 'Enable')) ?>
        <?php endif ?>
        <?php if (Yii::$app->user->can('integration.delete')) : ?>
            <?= $page->renderBulkDeleteButton('@integration/delete') ?>
        <?php endif ?>
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
