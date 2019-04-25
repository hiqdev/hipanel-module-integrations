<?php
/**
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\integrations\tests\_support\Page\Integration;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;

class Index extends Integration
{
    public function ensureIndexPageWorks(): self
    {
        $I = $this->tester;

        $I->needPage(Url::to(['@integration/index']));
        $I->see('Integrations', 'h1');
        $I->see('Create integration', 'a');

        return $this;
    }

    public function ensureICanSeeAdvancedSearchBox(): self
    {
        $I = $this->tester;
        $indexPage = new IndexPage($I);

        $I->see('Advanced search', 'h3');
        $indexPage->containsFilters([
            Input::asAdvancedSearch($I, 'Integration name'),
            Select2::asAdvancedSearch($I, 'Client'),
            Dropdown::asAdvancedSearch($I, 'Status')->withItems([
                'Ok',
                'Disabled',
            ]),
        ]);

        return $this;
    }

    public function ensureICanSeeColumns(): self
    {
        $I = $this->tester;
        $indexPage = new IndexPage($I);

        $indexPage->containsColumns([
            'Name',
            'Provider',
            'Client',
            'Type',
            'Status',
        ]);

        return $this;
    }

    public function ensureICanSeeVariantsOfCreateIntegrations(array $variants): self
    {
        $I = $this->tester;
        $I->amOnPage(Url::to('@integration'));
        $I->click('Create integration');
        $I->waitForElement('ul.dropdown-menu');
        foreach ($variants as $variant) {
            $I->seeElement(['css' => "a[data-target$=\"for-{$variant}\"]"]);
        }

        return $this;
    }
}
