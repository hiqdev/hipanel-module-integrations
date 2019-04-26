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

    public function createByProviderName(string $providerName, array $formData, bool $forceCreate = false): self
    {
        $action = 'create';
        $I = $this->tester;
        if (!$forceCreate) {
            $I->dontSeeElement("//td/a[contains(text(), '{$providerName}')][@class='bold']");
        }
        $I->seeElementInDOM(['css' => "a[href$=\"{$action}-{$providerName}\"]"]);
        $I->amOnPage(Url::to("@integration/{$action}-{$providerName}"));
        $this->setItem($providerName, $action, $formData);

        return $this;
    }
}
