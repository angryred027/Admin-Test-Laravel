<?php

declare(strict_types=1);

namespace App\Trait;

use Illuminate\Support\Facades\Config;

trait HelperTrait
{
    private const ENV_PRD = 'production';

    /**
     * get url by route name
     * @description now route helper is bagged in unit test setup(). (return double host ex: http://localhost/localhost/)
     * so made custom url helper.
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public function getRouteUrl(string $name, array $parameters = [])
    {
        // return "http://".Config::get('app.url').route($name, $parameters, false);
        return (Config::get('app.env') === self::ENV_PRD) ? "https://" : "http://" .Config::get('app.url').route($name, $parameters, false);
    }
}
