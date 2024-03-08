<?php

namespace MintHCM\Firebase\PushNotifications;

class MintTestPush extends \MintHCM\Firebase\PushNotification
{
    public function execute($data = [])
    {
        $bean = \BeanFactory::getBean('Users', 1); /** @var User $bean */
        $tokens = !empty($bean->id) ? $bean->getTokens() : null;
        if (empty($tokens)) {
            return;
        }
        $this->sendNotification(
            'Title Test',
            $bean->getTokens(),
            'Mint Test Push body',
            '',
            "minthcm://settingsScreen"
        );
    }
}
