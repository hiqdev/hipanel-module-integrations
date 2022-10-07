<?php

namespace hipanel\modules\integrations\tests\_support\Page\Integration;

use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;

abstract class Integration extends Authenticated
{
    public string $name;

    public function openModalByProviderType(string $type): self
    {
        $I = $this->tester;
        $I->seeInCurrentUrl('/index');
        $I->click('Create integration');
        $I->waitForElement('ul.dropdown-menu');
        $I->click(['css' => "a[data-target$=\"for-$type\"]"]);
        $I->waitForElement('div.provider-variants');

        return $this;
    }

    public function hasNotErrors(): self
    {
        $this->tester->dontSeeElement("//*[contains(@class, 'has-error')]");

        return $this;
    }

    public function hasErrors(): self
    {
        $this->tester->seeElement("//*[contains(@class, 'has-error')]");

        return $this;
    }

    protected function setItem(string $providerName, string $action, array $formData): void
    {
        $I = $this->tester;

        $I->seeInCurrentUrl("/$action-$providerName");
        $this->fillAndSubmitFormByProviderName($formData);
    }

    protected function fillAndSubmitFormByProviderName(array $formData): void
    {
        $I = $this->tester;
        $I->seeElementInDom(['css' => 'select[name$="[client_id]"]']);

        foreach ($formData as $field => $value) {
            if ($field === 'currency') {
                (new Dropdown($I, "select[name$=\"[$field]\"]"))->setValue($value);
            } else {
                (new Input($I, "input[name$=\"[$field]\"]"))->setValue($value);
            }
        }
        $I->pressButton('Save');
        $I->waitForJS("return $.active == 0;", 30);
    }
}
