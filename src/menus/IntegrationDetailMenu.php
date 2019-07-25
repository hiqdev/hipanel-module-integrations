<?php
/**
 * Integrations management plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-integrations
 * @package   hipanel-module-integrations
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\integrations\menus;

use hipanel\menus\AbstractDetailMenu;

class IntegrationDetailMenu extends AbstractDetailMenu
{
    public $model;

    public function items(): array
    {
        $actions = IntegrationActionsMenu::create(['model' => $this->model])->items();
        unset($actions['view']);

        return $actions;
    }
}
