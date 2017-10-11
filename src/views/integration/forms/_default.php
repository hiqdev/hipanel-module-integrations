<?php
/**
 * Integrations management plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-integrations
 * @package   hipanel-module-integrations
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

use hipanel\widgets\PasswordInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin([
    'id' => 'integration-form',
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]) ?>

<div class="box box-widget">
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'provider_id')->dropDownList($providers) ?>
                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'url') ?>
                <div class="well">
                    <?= $form->field($model, 'login') ?>
                    <?= $form->field($model, 'password')->widget(PasswordInput::class) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>
