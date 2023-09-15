<?php

namespace MintHCM\Api\Controllers\Init;

use BeanFactory;
use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\UserPreferences;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class Preferences
{
    protected $entityManager;
    protected $user_preferences;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->setUserPreferences();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode($this->getPreferences()));
        return $response;
    }

    public function getPreferences()
    {
        $response['preferences'] = $this->getUserPreferences();
        $response['global'] = $this->getGlobalSettings();
        return $response;
    }

    public function getGlobalSettings()
    {
        global $sugar_config;

        return array(
            'calendar' => $sugar_config['calendar'],
            'currency' => $sugar_config['currency'],
            'date_format' => $sugar_config['datef'],
            'time_format' => $sugar_config['timef'],
            'default_date_format' => $sugar_config["default_date_format"],
            'default_time_format' => $sugar_config["default_time_format"],
            'default_language' => $sugar_config["default_language"],
            'languages' => $sugar_config["languages"],
            'date_formats' => $sugar_config["date_formats"],
            'time_formats' => $sugar_config["time_formats"],
            'name_format' => $sugar_config["default_locale_name_format"],
            'password_rules' => [
                'minpwdlength' => $sugar_config['passwordsetting']['minpwdlength'] ?? null,
                'oneupper' => $sugar_config['passwordsetting']['oneupper'] ?? false,
                'onelower' => $sugar_config['passwordsetting']['onelower'] ?? false,
                'onenumber' => $sugar_config['passwordsetting']['onenumber'] ?? false,
                'onespecial' => $sugar_config['passwordsetting']['onespecial'] ?? false,
            ],
            'time_zones' => \TimeDate::getTimezoneList(),
            'name_formats' => (new \Localization())->getUsableLocaleNameOptions($sugar_config['name_formats']),
            'currencies' => $this->getCurrenciesList(),
        );
    }

    protected function getCurrenciesList()
    {
        $return_list = [];
        chdir('../legacy/');
        $currency = BeanFactory::getBean('Currencies');
        $list = $currency->get_full_list('name');
        $currency->retrieve('-99');
        if (is_array($list)) {
            $list = array_merge(array($currency), $list);
        } else {
            $list = array($currency);
        }
        foreach($list as $currency_bean){
            $return_list[$currency_bean->id] = [
                'id' => $currency_bean->id,
                'iso4217' => $currency_bean->iso4217,
                'name' => $currency_bean->name,
                'status' => $currency_bean->status,
                'conversion_rate' => $currency_bean->conversion_rate,
                'symbol' => $currency_bean->symbol,
                'hidden' => $currency_bean->hidden,
                'currency_on_right' => $currency_bean->currency_on_right,
            ];
        }
        chdir('../api/');
        return $return_list;
    }

    public function getUserPreferences()
    {
        return array(
            'date_format' => $this->user_preferences['global']['datef'] ?? '',
            'time_format' => $this->user_preferences['global']['timef'] ?? '',
            'name_format' => $this->user_preferences["global"]["default_locale_name_format"] ?? '',
        );
    }

    public function getUserAllPreferences()
    {
        return $this->user_preferences;
    }

    private function setUserPreferences()
    {
        global $current_user, $sugar_config;
        if (empty($current_user->id)) {
            return array();
        }

        try {
            $rows = $this->entityManager->getRepository(UserPreferences::class)
                ->findAllUndeletedByUserId($current_user->id);

            foreach ($rows as $row) {
                $category = $row['category'];
                $preferences[$category] = unserialize(base64_decode($row['contents']));
            }

            $this->user_preferences = $preferences;
        } catch (\Exception $e) {
            // TODO: log 'Failed to load user preferences'
            throw ($e);
        }
    }
}
