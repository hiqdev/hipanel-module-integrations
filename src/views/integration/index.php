<?php

use hipanel\modules\integrations\grid\IntegrationGridView;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Html;

$this->title = Yii::t('hipanel.integrations', 'Integrations');
$this->params['breadcrumbs'][] = $this->title;
$subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?php $page->beginContent('main-actions') ?>
            <?php Modal::begin([
                'header' => Html::tag('h4', Yii::t('hipanel.integrations', 'Select provider'), ['class' => 'modal-title']),
                'toggleButton' => ['label' => Yii::t('hipanel', 'Create'), 'class' => 'btn btn-sm btn-success', 'tag' => 'a'],
            ]) ?>
                <?= $this->render('_providers', compact('providers')) ?>
            <?php Modal::end() ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= IntegrationGridView::widget([
                    'boxed' => false,
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $model,
                    'columns'      => [
                        'checkbox',
                        'actions',
                        'name',
                        'provider',
                        'client',
                        'state',
                    ],
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
