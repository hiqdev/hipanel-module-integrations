<?php

namespace hipanel\modules\integrations\tests\_support\Page\Integration;

class Update extends View
{
    public function updateByProviderName(string $providerName, $formData): void
    {
        $this->tester->click("//a[contains(text(), 'Update')]");
        $this->setItem($providerName, 'update', $formData);
    }
}
