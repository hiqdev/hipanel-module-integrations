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
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkDomainSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Seller $I)
    {
        $this->index->containsFilters([
            Input::asAdvancedSearch($I, 'Name'),
            Select2::asAdvancedSearch($I, 'Provider'),
            Select2::asAdvancedSearch($I, 'Client'),
            (Dropdown::asAdvancedSearch($I, 'Status'))->withItems([
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
