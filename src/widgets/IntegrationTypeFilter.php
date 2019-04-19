<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\integrations\widgets;

use hipanel\models\Ref;
use hipanel\widgets\RefFilter;
use yii\helpers\ArrayHelper;

class IntegrationTypeFilter extends RefFilter
{
    protected function getRefs()
    {
        $options = ['select' => 'full', 'orderby' => 'name_asc', 'with_hierarchy' => true];
        $types = Ref::findCached('type,api', 'hipanel.integrations.types', $options);

        return $this->prefixBillTypes(ArrayHelper::map($types, 'name', 'label'));
    }

    /**
     * Prefixes bill types (not categories of types) with `--` string.
     *
     * @param array $types
     * @param string $prefix
     * @return array
     */
    private function prefixBillTypes($types, $prefix = '-- ')
    {
        foreach ($types as $key => $title) {
            if (count(explode(',', $key)) > 1) {
                $types[$key] = $prefix . $title;
            }
        }

        return $types;
    }
}
