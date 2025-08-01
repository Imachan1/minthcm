<?php
namespace Api\Core\Config;

#[\AllowDynamicProperties]
class ApiConfig
{
    // we still support 5.5.9
    private static $slimSettings = [
        'Api/Core/Config/slim.php',
    ];

    private static $containers = [
        'Api/V8/Config/services.php',
    ];

    private static $routes = [
        'Api/V8/Config/routes.php',
    ];

    const OAUTH2_PRIVATE_KEY = '../api/configs/private.key'; //CR o ile dobrze rozumiem, ta zmiana oznacza, że klucze są w inyym miejscu więc by skorzyatć z V8 i tak trzeba wyegenrowac klucze, ale teraz tu wiec trzeba zmienić dokumentacje?
    const OAUTH2_PUBLIC_KEY = '../api/configs/public.key';
    
    /**
     *
     * @var boolean
     */
    private static $debugExceptions = false;
    
    /**
     *
     * @return boolean
     */
    public static function getDebugExceptions()
    {
        return self::$debugExceptions;
    }

    /**
     * @return array
     */
    public static function getSlimSettings()
    {
        return self::$slimSettings;
    }

    /**
     * @return array
     */
    public static function getContainers()
    {
        return self::$containers;
    }

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }
}
