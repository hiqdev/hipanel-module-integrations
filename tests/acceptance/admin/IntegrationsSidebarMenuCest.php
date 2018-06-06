<?php

namespace hipanel\modules\integrations\tests\acceptance\admin;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Admin;

class IntegrationsSidebarMenuCest
{
    public function ensureMenuIsOk(Admin $I)
    {
        (new SidebarMenu($I))->ensureDoesNotContain('Settings');
    }
}
