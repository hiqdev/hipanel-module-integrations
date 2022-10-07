<?php

namespace hipanel\modules\integrations\tests\acceptance\seller;

use hipanel\modules\integrations\tests\_support\Page\Integration\Create;
use hipanel\modules\integrations\tests\_support\Page\Integration\Delete;
use hipanel\modules\integrations\tests\_support\Page\Integration\Index;
use hipanel\modules\integrations\tests\_support\Page\Integration\Update;
use hipanel\modules\integrations\tests\_support\Page\Integration\View;
use hipanel\tests\_support\Step\Acceptance\Seller;

class IntegrationsCRUDCest
{
    public const PROVIDER_TYPE_PAYMENT = 'payment';
    private static array $formData = [];

    public function _before()
    {
        if (empty(self::$formData)) {
            $name = 'paypal_' . mt_rand();
            self::$formData = [
                'type' => self::PROVIDER_TYPE_PAYMENT,
                'name' => 'paypal',
                'create' => [
                    'name' => $name,
                    'login' => 'login_test_paypal_' . mt_rand(),
                    'currency' => 'eur',
                ],
                'update' => [
                    'name' => $name,
                    'login' => 'login_test_paypal_updated_' . mt_rand(),
                    'currency' => 'usd',
                    'commission' => '',
                ],
            ];
        }
    }

    public function ensureIntegrationsPageWorks(Seller $I): void
    {
        $indexPage = new Index($I);
        $indexPage->ensureIndexPageWorks();
        $indexPage->ensureICanSeeAdvancedSearchBox();
        $indexPage->ensureICanSeeColumns();
        $indexPage->ensureICanSeeVariantsOfCreateIntegrations([self::PROVIDER_TYPE_PAYMENT]);
    }

    public function ensureICanCreateIntegrations(Seller $I): void
    {
        $formData = self::$formData['create'];
        $createPage = new Create($I);
        $createPage->openModalByProviderType(self::$formData['type']);
        $createPage->createByProviderName(self::$formData['name'], $formData);
        $createPage->hasNotErrors();
        $viewPage = new View($I, $formData['name']);
        $viewPage->visitIntegration();
        $viewPage->verifyFields($formData);
    }


    public function ensureICanUpdateIntegrations(Seller $I): void
    {
        $formData = self::$formData['update'];
        $updatePage = new Update($I, self::$formData['create']['name']);
        $updatePage->visitIntegration();
        $I->wait(10);
        $updatePage->updateByProviderName(self::$formData['name'], $formData);
        $updatePage->hasNotErrors();
        $viewPage = new View($I, $formData['name']);
        $viewPage->visitIntegration();
        $viewPage->verifyFields($formData);
    }

    public function ensureICanDeleteIntegrations(Seller $I): void
    {
        $deletePage = new Delete($I, self::$formData['update']['name']);
        $deletePage->visitIntegration();
        $deletePage->deleteIntegration();
    }

    public function ensureICanNotCreateMoreThenOneIntegrationWithTheSameNameAndClient(Seller $I): void
    {
        $formData = self::$formData['create'];

        // Create the first item
        $createFirstItemPage = new Create($I);
        $createFirstItemPage->openModalByProviderType(self::PROVIDER_TYPE_PAYMENT);
        $createFirstItemPage->createByProviderName('paypal', $formData);
        $createFirstItemPage->hasNotErrors();

        // Try to create the second item with the same Name and Client
        $createSecondItemPage = new Create($I);
        $createSecondItemPage->openModalByProviderType(self::PROVIDER_TYPE_PAYMENT);
        $createSecondItemPage->createByProviderName('paypal', $formData);
        $I->waitForText('Fields Client and Name are not unique');

        // Delete the first item
        $deletePage = new Delete($I, 'paypal');
        $deletePage->visitIntegration();
        $deletePage->deleteIntegration();
    }
}
