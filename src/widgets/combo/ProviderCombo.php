<?php

namespace hipanel\modules\integrations\widgets\combo;

use hipanel\helpers\ArrayHelper;
use hiqdev\combo\Combo;

class ProviderCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'integrations/provider';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/integrations/provider/get-providers';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'label'];

    public $_primaryFilter = 'name_like';

    /** {@inheritdoc} */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'limit' => ['format' => '20'],
        ]);
    }
}
