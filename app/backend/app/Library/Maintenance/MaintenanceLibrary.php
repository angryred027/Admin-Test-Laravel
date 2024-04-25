<?php

declare(strict_types=1);

namespace App\Library\Maintenance;

use App\Library\Time\TimeLibrary;

class MaintenanceLibrary
{
    /**
     * is maintenace mode.
     *
     * @return bool
     */
    public static function isMaintenance(): bool
    {
        return config('myappMaintenance.isMaintenance');
    }

    /**
     * is maintenace period.
     *
     * @return bool
     */
    public static function isMaintenancePeriod(): bool
    {
        $currentDateTimestamp = TimeLibrary::getCurrentDateTimeTimeStamp();
        $startTimeStamp = TimeLibrary::strToTimeStamp(config('myappMaintenance.startTime'));
        $endTimeStamp = TimeLibrary::strToTimeStamp(config('myappMaintenance.endTime'));
        return ($startTimeStamp <= $currentDateTimestamp) && ($currentDateTimestamp <= $endTimeStamp);
    }

    /**
     * is pass maintenace.
     *
     * @return bool
     */
    public static function isEnabplePass(): bool
    {
        return config('myappMaintenance.isEnabplePass');
    }

    /**
     * get maintenance except routes
     *
     * @return array
     */
    public static function getExceptRoutes(): array
    {
        return config('myappMaintenance.exceptRoutes');
    }

    /**
     * get maintenance except ip address
     *
     * @return array
     */
    public static function getExceptIps(): array
    {
        return config('myappMaintenance.exceptIps');
    }

    /**
     * is maintenace except route.
     * @param string $path path
     * @return bool
     */
    public static function isExceptRoute(string $path): bool
    {
        return in_array($path, self::getExceptRoutes(), true);
    }

    /**
     * is maintenace except ip address.
     * @param string $ip ip address
     * @return bool
     */
    public static function isExceptIp(string $ip): bool
    {
        return in_array($ip, self::getExceptIps(), true);
    }
}
