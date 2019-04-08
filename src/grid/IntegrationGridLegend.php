<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\integrations\grid;

use hipanel\widgets\gridLegend\BaseGridLegend;
use hipanel\widgets\gridLegend\GridLegendInterface;
use Yii;

class IntegrationGridLegend extends BaseGridLegend implements GridLegendInterface
{
    public function items()
    {
        return [];
    }
}
