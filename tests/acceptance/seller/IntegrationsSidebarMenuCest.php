<?php

namespace hipanel\modules\integrations\tests\acceptance\seller;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Seller;

class IntegrationsSidebarMenuCest
{
    public function ensureMenuIsOk(Seller $I)
    {
        (new SidebarMenu($I))->ensureContains('Settings', [
            'Integrations' => '@integration/index',
        ]);
    }
}
