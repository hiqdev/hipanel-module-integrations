<?php

namespace hipanel\modules\integrations\tests\_support\Page\Integration;

use hipanel\helpers\Url;

class Create extends Integration
{
    public function __construct($I)
    {
        parent::__construct($I);

        $I->amOnPage(Url::toRoute(['@integration/index']));
    }

    public function createByProviderName(string $providerName, array $formData): self
    {
        $action = 'create';
        $I = $this->tester;
        $I->seeElementInDOM(['css' => "a[href$=\"$action-$providerName\"]"]);
        $I->amOnPage(Url::to("@integration/$action-$providerName"));
        $this->setItem($providerName, $action, $formData);

        return $this;
    }
}
