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
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Seller $I)
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Seller $I)
    {
        $I->login();
        $I->needPage(Url::to('@integration'));
        $I->see('Integrations', 'h1');
        $I->see('Create', 'a');
        $this->ensureICanSeeAdvancedSearchBox();
        $this->ensureICanSeeBulkDomainSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox()
    {
        $this->index->containsFilters([
            new Input('Name'),
            new Select2('Provider'),
            new Select2('Client'),
            (new Dropdown('integrationsearch-state'))->withItems([
                'Ok',
                'Disabled',
            ]),
        ]);
    }

    private function ensureICanSeeBulkDomainSearchBox()
    {
        $this->index->containsColumns([
            'Name',
            'Provider',
            'Client',
            'Status',
        ]);
    }
}
