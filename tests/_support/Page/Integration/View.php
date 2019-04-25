<?php

namespace hipanel\modules\integrations\tests\_support\Page\Integration;

use hipanel\helpers\Url;
use hipanel\tests\_support\AcceptanceTester;

class View extends Integration
{
    public function __construct(AcceptanceTester $I, string $name)
    {
        parent::__construct($I);

        $this->name = $name;
    }

    public function visitIntegration()
    {
        $I = $this->tester;

        $I->amOnPage(Url::to('@integration/index'));
        $I->click("//a[contains(text(), '{$this->name}')]");
        $I->waitForJS("return $.active == 0;", 30);
        $I->seeInTitle($this->name);
        $I->seeInCurrentUrl('/integration/view?id=');

        return $this;
    }

    public function seeIntegration(array $formData)
    {
        $I = $this->tester;

        $I->seeInTitle($this->name);
        $I->seeInCurrentUrl('/integration/view');
        foreach ($formData as $value) {
            if ($value) {
                $I->see($value);
            }
        }
    }

    public function verifyFields(array $formData): self
    {
        $I = $this->tester;

        $I->seeInCurrentUrl('/integration/view?id=');
        $I->seeInTitle($this->name);
        foreach ($formData as $value) {
            if ($value) {
                $I->see($value);
            }
        }

        return $this;
    }
}
