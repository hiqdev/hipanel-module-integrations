<?php

use hipanel\widgets\PasswordInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin([
    'id' => 'integration-form',
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]) ?>

<div class="row">
    <div class="col-md-4">
        <div class="box box-widget">
            <div class="box-body">
                <?= $form->field($model, 'provider_id')->dropDownList($model->providers) ?>
                <?= $form->field($model, 'url') ?>
                <?= $form->field($model, 'login') ?>
                <?= $form->field($model, 'password')->widget(PasswordInput::class) ?>

                <?php if ($formName = $this->context->getSpecificFormName()) : ?>
                    <?= $this->render('forms' . DIRECTORY_SEPARATOR . $formName, compact('form', 'model')) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>
