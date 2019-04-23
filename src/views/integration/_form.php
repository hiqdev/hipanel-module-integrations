<?php

use hipanel\modules\integrations\widgets\IntegrationFormBuilder;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php $form = IntegrationFormBuilder::begin([
    'id' => 'integration-form',
    'validationUrl' => Url::toRoute(["validate-{$model->provider_name}-form", 'scenario' => $model->scenario]),
    'enableAjaxValidation' => true,
]) ?>

<div class="row">

    <div class="col-md-4">
        <div class="box box-widget">
            <div class="box-body">
                <?= $form->renderForm($model) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>

</div>

<?php IntegrationFormBuilder::end() ?>
