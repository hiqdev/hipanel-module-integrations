<?php

namespace hipanel\modules\integrations\tests\acceptance\seller;

use hipanel\modules\integrations\tests\_support\Page\Integration\Create;
use hipanel\modules\integrations\tests\_support\Page\Integration\Delete;
use hipanel\modules\integrations\tests\_support\Page\Integration\Index;
use hipanel\modules\integrations\tests\_support\Page\Integration\Update;
use hipanel\modules\integrations\tests\_support\Page\Integration\View;
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
                'currency' => 'eur',
            ],
            'update' => [
                'login' => 'login_test_paypal_updated',
                'currency' => 'usd',
            ],
        ]
    ];

    private function getVariants(): array
    {
        return [
            self::PROVIDER_TYPE_DOMAIN => 'directi',
            self::PROVIDER_TYPE_CERTIFICATE => 'certum',
            self::PROVIDER_TYPE_PAYMENT => 'paypal',
        ];
    }

    public function ensureIntegrationsPageWorks(Seller $I): void
    {
        $variants = array_keys($this->getVariants());
        $indexPage = new Index($I);
        $indexPage->ensureIndexPageWorks();
        $indexPage->ensureICanSeeAdvancedSearchBox();
        $indexPage->ensureICanSeeColumns();
        $indexPage->ensureICanSeeVariantsOfCreateIntegrations($variants);
    }

    public function ensureICanCreateIntegrations(Seller $I): void
    {
        foreach ($this->getVariants() as $providerType => $providerName) {
            $formData = $this->createFormData[$providerName]['create'];
            $createPage = new Create($I);
            $createPage->openModalByProviderType($providerType);
            $createPage->createByProviderName($providerName, $formData);
            $I->dontSeeElement("//*[contains(@class, 'has-error')]");
            $viewPage = new View($I, $providerName);
            $viewPage->visitIntegration();
            $viewPage->verifyFields($formData);
        }
    }

    public function ensureICanUpdateIntegrations(Seller $I): void
    {
        foreach ($this->getVariants() as $providerType => $providerName) {
            $formData = $this->createFormData[$providerName]['update'];
            $updatePage = new Update($I, $providerName);
            $updatePage->visitIntegration();
            $updatePage->updateByProviderName($providerName, $formData);
            $I->dontSeeElement("//*[contains(@class, 'has-error')]");
            $viewPage = new View($I, $providerName);
            $viewPage->visitIntegration();
            $viewPage->verifyFields($formData);
        }
    }

    public function ensureICanDeleteIntegrations(Seller $I): void
    {
        foreach ($this->getVariants() as $providerType => $providerName) {
            $deletePage = new Delete($I, $providerName);
            $deletePage->visitIntegration();
            $deletePage->deleteIntegration();
        }
    }

    public function ensureICanNotCreateMoreThenOneIntegrationWithTheSameNameAndClient(Seller $I): void
    {
        $formData = $this->createFormData['paypal']['create'];

        // Create the first item
        $createFirstItemPage = new Create($I);
        $createFirstItemPage->openModalByProviderType(self::PROVIDER_TYPE_PAYMENT);
        $createFirstItemPage->createByProviderName('paypal', $formData);
        $I->dontSeeElement("//*[contains(@class, 'has-error')]");

        // Try to create the second item with the same Name and Client
        $createSecondItemPage = new Create($I);
        $createSecondItemPage->openModalByProviderType(self::PROVIDER_TYPE_PAYMENT);
        $createSecondItemPage->createByProviderName('paypal', $formData, true);
        $I->waitForText('Fields Client and Name are not unique');

        // Delete the first item
        $deletePage = new Delete($I, 'paypal');
        $deletePage->visitIntegration();
        $deletePage->deleteIntegration();
    }
}
