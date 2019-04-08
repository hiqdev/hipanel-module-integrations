<?php
/**
 * @var $search \hipanel\widgets\AdvancedSearch
 */

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\integrations\widgets\combo\ProviderCombo;

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('provider')->widget(ProviderCombo::class) ?>
</div>

<?php if (Yii::$app->user->can('support')) : ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('client_id')->widget(ClientCombo::class, ['formElementSelector' => '.form-group']) ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('state')->dropDownList($search->model->states, ['prompt' => '--']) ?>
    </div>
<?php endif ?>
