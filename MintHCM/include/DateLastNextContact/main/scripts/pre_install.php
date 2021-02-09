<?php

function pre_install()
{
    require_once('modules/Import/ImportFileSplitter.php');
    $IFS = new ImportFileSplitter('modules/ev_PackageSubscriptions/Logic/src/SubscriptionTypes/SubscriptionTypeFactory.php');
    if ( !$IFS->fileExists() ) {
        displayInstallationIssues();
        sugar_cleanup(true);
    }
}

function displayInstallationIssues(){
        global $current_language;
        $LBL_MES = [
            'pl_PL' => 'Brak zainstalowanej Paczki "eVolpe Subscriptions Core"',
            'en_us' => '"eVolpe Subsciptions Core" Package not detected!',
        ];

        $LBL_MESD = [
            'pl_PL' => 'Brak zainstalowanej Paczki "eVolpe Subscriptions Core" <br> Jeśli nie wiesz gdzie ją zdobyć skontaktuj się z przedstawicielem eVolpe ',
            'en_us' => 'Plese install the "eVolpe Subscriptions Core" Package first.  <br> If you don\'t know where to get it please contact your eVolpe representative',
        ];
        $LBL_MISSING_EVOLPE_SUBSCRIPTION = $current_language=='pl_PL' ? $LBL_MES['pl_PL'] : $LBL_MES['en_us'];
        $LBL_MISSING_EVOLPE_SUBSCRIPTION_DESCRIPTION = $current_language=='pl_PL' ? $LBL_MESD['pl_PL'] : $LBL_MESD['en_us'];
        
        echo '<h2 class="error">' . translate('ML_INSTALLATION_FAILED') . '</h2><br><br>';
        echo '<div class="error"><h2>'.$LBL_MISSING_EVOLPE_SUBSCRIPTION . '</h2> </div>';
        echo '<div id="details" >';
        echo $LBL_MISSING_EVOLPE_SUBSCRIPTION_DESCRIPTION;
        echo '</div>';
        echo '</div>';
}

