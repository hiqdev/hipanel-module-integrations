<?php

namespace hipanel\modules\integrations\tests\_support\Page\Integration;

use hipanel\helpers\Url;

class Delete extends View
{
    public function deleteIntegration(): self
    {
        $I = $this->tester;

        $I->click("//a[contains(text(), 'Delete')]");
        $I->seeInPopup('Are you sure you want to delete this item?');
        $I->acceptPopup();
        $I->closeNotification('Integration has been deleted');
        $I->seeInCurrentUrl(Url::to('@integration/index'));
        $I->seeInTitle('Integrations');

        return $this;
    }
}
