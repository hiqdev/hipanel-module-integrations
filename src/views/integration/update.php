<?php
/**
 * Integrations management plugin for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-integrations
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */
$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.integrations', 'Integrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.integrations', $model->name), 'url' => ['@integration/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('forms/_default', compact('model', 'models', 'providers')) ?>
