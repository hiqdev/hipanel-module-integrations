<?php

/**
 * @var View $this
 * @var array $providerTypes
 * @var IntegrationSearch $model
 * @var IndexPageUiOptions $uiModel
 * @var HubRepresentations $representationCollection
 * @var ActiveDataProvider $dataProvider
 */

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\integrations\grid\IntegrationGridLegend;
use hipanel\modules\integrations\grid\IntegrationGridView;
use hipanel\modules\integrations\models\IntegrationSearch;
use hipanel\modules\integrations\widgets\CreateIntegrationButton;
use hipanel\modules\server\grid\HubRepresentations;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\data\ActiveDataProvider;
use yii\web\View;

$this->title = Yii::t('hipanel.integrations', 'Integrations');
$this->params['breadcrumbs'][] = $this->title;
$subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?php $page->beginContent('main-actions') ?>
            <?= CreateIntegrationButton::widget([
                'allowedTypes' => $providerTypes,
            ]) ?>
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
