<?php

namespace hipanel\modules\integrations\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Seller;

class IntegrationsCest
{
    const PROVIDER_TYPE_DOMAIN = 'domain';

    const PROVIDER_TYPE_CERTIFICATE = 'certificate';

    const PROVIDER_TYPE_PAYMENT = 'payment';

    private $createFormData = [
        'directi' => [
            'create' => [
                'url' => null,
                'login' => 'login_test_directi',
                'password' => 'password_test_directi',
            ],
            'update' => [
                'url' => 'https://test.domain.com.updated',
                'login' => 'login_test_directi_updated',
                'password' => 'password_test_directi_updated',
            ],
        ],
        'certum' => [
            'create' => [
                'url' => null,
                'login' => 'login_test_certum',
                'password' => 'password_test_certum',
            ],
            'update' => [
                'url' => null,
                'login' => 'login_test_certum_updated',
                'password' => 'password_test_certum_updated',
            ],
        ],
        'paypal' => [
            'create' => [
                'login' => 'login_test_paypal',
            ],
            'update' => [
                'login' => 'login_test_paypal_updated',
            ],
        ]
    ];

    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Seller $I)
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Seller $I): void
    {
        $I->login();
        $I->needPage(Url::to('@integration'));
        $I->see('Integrations', 'h1');
        $I->see('Create integration', 'a');
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkDomainSearchBox();
    }

    public function ensureICanSeeVariantsOfCreateIntegrations(Seller $I): void
    {
        $variants = [
            self::PROVIDER_TYPE_DOMAIN,
            self::PROVIDER_TYPE_CERTIFICATE,
            self::PROVIDER_TYPE_PAYMENT,
        ];

        $I->amOnPage(Url::to('@integration'));
        $I->click('Create integration');
        $I->waitForElement('ul.dropdown-menu');

        foreach ($variants as $variant) {
            $I->seeElement(['css' => "a[data-target*=\"for-{$variant}\"]"]);
        }
    }

    public function ensureICanCRUDOperations(Seller $I): void
    {
        foreach ([
            self::PROVIDER_TYPE_DOMAIN => 'directi',
            self::PROVIDER_TYPE_CERTIFICATE => 'certum',
            self::PROVIDER_TYPE_PAYMENT => 'paypal',
                 ] as $type => $provider) {
            $I->amOnPage(Url::to('@integration/index'));
            $this->openModalByProviderType($I, $type);
            $this->createByProviderName($I, $provider);
            $id = $this->checkByProviderName($I, $provider);
            $I->click("//a[contains(text(), 'Update')]");
            $I->seeInTitle('Update');
            $this->updateByProviderName($I, $provider);
            $this->deleteItem($I, $id);
            $I->wait(2); // coffee break
        }
    }

    public function ensureICanNotCreateTwoItemsWithTheSameNameAndClient(Seller $I)
    {
        $I->amOnPage(Url::to('@integration/index'));
        $this->openModalByProviderType($I, self::PROVIDER_TYPE_CERTIFICATE);
        $this->createByProviderName($I, 'paypal');
        $firstItemId = $this->checkByProviderName($I, 'paypal');
        $I->amOnPage(Url::to('@integration/index'));
        $this->openModalByProviderType($I, self::PROVIDER_TYPE_CERTIFICATE);
        $this->createByProviderName($I, 'paypal');
        $I->waitForText('Fields Client and Name are not unique');
        $this->deleteItem($I, $firstItemId);
    }

    private function deleteItem(Seller $I, string $itemId)
    {
        $I->amOnPage(Url::to(['@integration/view', 'id' => $itemId]));
        $I->click("//a[contains(text(), 'Delete')]");
        $I->seeInPopup('Are you sure you want to delete this item?');
        $I->acceptPopup();
        $I->seeInCurrentUrl(Url::to('@integration/index'));
        $I->seeInTitle('Integrations');
    }

    private function checkByProviderName(Seller $I, $providerName): string
    {
        $formData = $this->createFormData[$providerName]['create'];
        $I->amOnPage(Url::to('@integration/index'));
        $I->click("//a[contains(text(), '{$providerName}')]"); // [@class=\"bold\"]
        $I->waitForJS("return $.active == 0;", 30);
        $I->seeInTitle($providerName);
        $I->seeInCurrentUrl('/integration/view');
        foreach ($formData as $value) {
            if ($value) {
                $I->see($value);
            }
        }

        return $I->grabFromCurrentUrl('~id=(\d+)~');
    }

    private function createByProviderName(Seller $I, string $providerName): void
    {
        $action = 'create';
        $formData = $this->createFormData[$providerName]['create'];
        $I->seeElementInDOM(['css' => "a[href$=\"{$action}-{$providerName}\"]"]);
        $I->amOnPage(Url::to("@integration/{$action}-{$providerName}"));
        $this->setItem($I, $providerName, $action, $formData);
    }

    private function updateByProviderName(Seller $I, string $providerName): void
    {
        $formData = $this->createFormData[$providerName]['update'];
        $this->setItem($I, $providerName, 'update', $formData);
    }

    private function setItem(Seller $I, string $providerName, string $action, array $formData)
    {
        $I->seeInCurrentUrl(Url::to("@integration/{$action}-{$providerName}"));
        $this->fillAndSubmitFormByProviderName($I, $formData);
    }

    private function ensureICanSeeAdvancedSearchBox(Seller $I): void
    {
        $this->index->containsFilters([
            Input::asAdvancedSearch($I, 'Integration name'),
            Select2::asAdvancedSearch($I, 'Client'),
            (Dropdown::asAdvancedSearch($I, 'Status'))->withItems([
                'Ok',
                'Disabled',
            ]),
        ]);
    }

    private function ensureICanSeeBulkDomainSearchBox(): void
    {
        $this->index->containsColumns([
            'Name',
            'Provider',
            'Client',
            'Type',
            'Status',
        ]);
    }

    private function openModalByProviderType(Seller $I, string $type): void
    {
        $I->click('Create integration');
        $I->waitForElement('ul.dropdown-menu');
        $I->click(['css' => "a[data-target$=\"for-{$type}\"]"]);
        $I->waitForElement('div.provider-variants');
    }

    /**
     * @param Seller $I
     * @param array $formData
     * @throws \Codeception\Exception\ModuleException
     */
    private function fillAndSubmitFormByProviderName(Seller $I, array $formData): void
    {
        $I->seeElementInDom(['css' => 'select[name$="[client_id]"]']);

        foreach ($formData as $field => $value) {
            if ($value) {
                (new Input($I, "input[name$=\"[{$field}]\"]"))->setValue($value);
            } else {
                $I->seeElement(['css' => "input[name$=\"[{$field}]\"]"]);
            }
        }

        $I->pressButton('Save');
        $I->waitForJS("return $.active == 0;", 30);
    }
}
