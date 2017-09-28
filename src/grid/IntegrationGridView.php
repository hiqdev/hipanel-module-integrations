<?php

namespace hipanel\modules\integrations\grid;

class IntegrationGridView extends \hipanel\grid\BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), []);
    }
}

