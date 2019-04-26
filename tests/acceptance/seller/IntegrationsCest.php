<?php

namespace hipanel\modules\integrations\tests\acceptance\seller;

use Codeception\Example;
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

    /**
     * @return array
     */
    protected function variantsProvider(): array
    {
        return [
            ['type' => self::PROVIDER_TYPE_PAYMENT, 'name' => 'paypal'],
            ['type' => self::PROVIDER_TYPE_DOMAIN, 'name' => 'directi'],
            ['type' => self::PROVIDER_TYPE_CERTIFICATE, 'name' => 'certum'],
        ];
    }

    public function ensureIntegrationsPageWorks(Seller $I): void
    {
        $variants = [
            self::PROVIDER_TYPE_DOMAIN,
            self::PROVIDER_TYPE_CERTIFICATE,
            self::PROVIDER_TYPE_PAYMENT,
        ];
        $indexPage = new Index($I);
        $indexPage->ensureIndexPageWorks();
        $indexPage->ensureICanSeeAdvancedSearchBox();
        $indexPage->ensureICanSeeColumns();
        $indexPage->ensureICanSeeVariantsOfCreateIntegrations($variants);
    }

    /**
     * @dataProvider variantsProvider
     * @param Seller $I
     * @param Example $provider
     */
    public function ensureICanCreateIntegrations(Seller $I, Example $provider): void
    {
        $formData = $this->createFormData[$provider['name']]['create'];
        $createPage = new Create($I);
        $createPage->openModalByProviderType($provider['type']);
        $createPage->createByProviderName($provider['name'], $formData);
        $createPage->hasNotErrors();
        $viewPage = new View($I, $provider['name']);
        $viewPage->visitIntegration();
        $viewPage->verifyFields($formData);
    }


    /**
     * @dataProvider variantsProvider
     * @param Seller $I
     * @param Example $provider
     */
    public function ensureICanUpdateIntegrations(Seller $I, Example $provider): void
    {
        $formData = $this->createFormData[$provider['name']]['update'];
        $updatePage = new Update($I, $provider['name']);
        $updatePage->visitIntegration();
        $updatePage->updateByProviderName($provider['name'], $formData);
        $updatePage->hasNotErrors();
        $viewPage = new View($I, $provider['name']);
        $viewPage->visitIntegration();
        $viewPage->verifyFields($formData);
    }

    /**
     * @dataProvider variantsProvider
     * @param Seller $I
     * @param Example $provider
     */
    public function ensureICanDeleteIntegrations(Seller $I, Example $provider): void
    {
        $deletePage = new Delete($I, $provider['name']);
        $deletePage->visitIntegration();
        $deletePage->deleteIntegration();
    }

    public function ensureICanNotCreateMoreThenOneIntegrationWithTheSameNameAndClient(Seller $I): void
    {
        $formData = $this->createFormData['paypal']['create'];

        // Create the first item
        $createFirstItemPage = new Create($I);
        $createFirstItemPage->openModalByProviderType(self::PROVIDER_TYPE_PAYMENT);
        $createFirstItemPage->createByProviderName('paypal', $formData);
        $createFirstItemPage->hasNotErrors();

        // Try to create the second item with the same Name and Client
        $createSecondItemPage = new Create($I);
        $createSecondItemPage->openModalByProviderType(self::PROVIDER_TYPE_PAYMENT);
        $createSecondItemPage->createByProviderName('paypal', $formData, true);
        $createSecondItemPage->hasErrors();
        $I->waitForText('Fields Client and Name are not unique');

        // Delete the first item
        $deletePage = new Delete($I, 'paypal');
        $deletePage->visitIntegration();
        $deletePage->deleteIntegration();
    }
}
