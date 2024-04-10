<?php

declare(strict_types=1);

namespace App\Library\Time;

use Illuminate\Http\Request;
use App\Trait\CheckHeaderTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Carbon;

class TimeLibrary
{
    // デフォルトのフォーマット
    public const DEFAULT_DATE_TIME_FORMAT = 'Y-m-d H:i:s'; // ex: 2022-01-01 00:00:00
    public const DEFAULT_DATE_TIME_FORMAT_SLASH = 'Y/m/d H:i:s'; // ex: 2022/01/01 00:00:00
    public const DEFAULT_DATE_TIME_FORMAT_DATE_ONLY = 'Y-m-d'; // ex: 2022-01-01
    public const DEFAULT_DATE_TIME_FORMAT_YEAR_MONTH_ONLY = 'Y-m'; // ex: 2022-01

    public const DATE_TIME_FORMAT_YMD = 'Ymd'; // ex: 20220101
    public const DATE_TIME_FORMAT_HIS = 'His'; // ex: 125959
    public const DATE_TIME_FORMAT_YMDHIS = 'YmdHis'; // ex: 20220101125959
    public const DATE_TIME_FORMAT_START_DATE = 'Y-m-d 00:00:00'; // ex: 2022-01-01 00:00:00
    public const DATE_TIME_FORMAT_END_DATE = 'Y-m-d 23:59:59'; // ex: 2022-01-01 23:59:59

    public const HALF_AN_HOUR_SECOND_VALUE = 1800; // 30分=1800秒
    public const AN_HOUR_SECOND_VALUE = 3600; // 1時間=3600秒
    public const ONE_DAY_SECOND_VALUE = 86400; // 1日=86400秒

    // 偽装時刻
    private static ?int $fakerTimeStamp = null;

    /**
     * setFaker time stamp.
     *
     * @param ?int $timeStamp timestamp
     * @return void
     */
    public static function setFakerTimeStamp(?int $timeStamp): void
    {
        // production環境以外で設定する
        if (config('app.env') !== 'productinon') {
            static::$fakerTimeStamp = $timeStamp;
        }
    }

    /**
     * get current date time.
     *
     * @param string $format datetime format
     * @return string
     */
    public static function getCurrentDateTime(string $format = self::DEFAULT_DATE_TIME_FORMAT): string
    {
        /* $carbon = new Carbon();
        $test = $carbon->now()->format('Y-m-d H:i:s'); */
        // $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        // return Carbon::now()->format(self::DEFAULT_DATE_TIME_FORMAT);
        // return Carbon::now()->timezone(Config::get('app.timezone'))->format($format);

        $dateTime = null;
        // 偽装時刻が設定されている場合
        if (!is_null(static::$fakerTimeStamp)) {
            $dateTime = static::$fakerTimeStamp;
        }

        return (new Carbon($dateTime))->timezone(Config::get('app.timezone'))->format($format);
    }

    /**
     * get timestamp of current date time.
     *
     * @return int timestamp
     */
    public static function getCurrentDateTimeTimeStamp(): int
    {
        // 偽装時刻が設定されている場合
        if (!is_null(static::$fakerTimeStamp)) {
            return static::$fakerTimeStamp;
        }
        // return Carbon::now()->timezone(Config::get('app.timezone'))->timestamp;
        // return (new Carbon())->timezone(Config::get('app.timezone'))->timestamp;
        return time();
    }

    /**
     * get current date timestamp.
     *
     * @param string $dateTime 日時
     * @return int タイムスタンプ
     */
    public static function strToTimeStamp(string $dateTime): int
    {
        return strtotime($dateTime);
    }

    /**
     * convert timestamp to date time.
     *
     * @param int $timeStamp timestamp
     * @param string $format datetime format
     * @return string datetime
     */
    public static function timeStampToDate(int $timeStamp, string $format = self::DEFAULT_DATE_TIME_FORMAT): string
    {
        return date($format, $timeStamp);
    }

    /**
     * get formatted date time.
     *
     * @param string $dateTime 日時
     * @param string $format datetime format
     * @return array 曜日
     */
    public static function format(string $dateTime, string $format = self::DEFAULT_DATE_TIME_FORMAT_SLASH): string
    {
        return (new Carbon($dateTime))->format($format);
    }

    /**
     * get parameter days.
     *
     * @param string $dateTime 日時
     * @return array 曜日
     */
    public static function getDays(string $dateTime): array
    {
        return (new Carbon($dateTime))->getDays();
    }

    /**
     * add hours to dateTime parameter.
     *
     * @param string $dateTime 日時
     * @param int $value 加算分数
     * @param string $format datetime format
     * @return string $dateTimeから$value日後の$dateTime
     */
    public static function addHours(string $dateTime, int $value, string $format = self::DEFAULT_DATE_TIME_FORMAT): string
    {
        return (new Carbon($dateTime))->addHours($value)->format($format);
        // return date($format, strtotime("+$value hour"));
    }

    /**
     * add days to dateTime parameter.
     *
     * @param string $dateTime 日時
     * @param int $value 加算日数
     * @param string $format datetime format
     * @return string $dateTimeから$value日後の$dateTime
     */
    public static function addDays(string $dateTime, int $value, string $format = self::DEFAULT_DATE_TIME_FORMAT): string
    {
        return (new Carbon($dateTime))->addDays($value)->format($format);
    }

    /**
     * add mounth to dateTime parameter.
     *
     * @param string $dateTime 日時
     * @param int $value 加算月数
     * @param string $format datetime format
     * @return string $dateTimeから$valueヶ月後の$dateTime
     */
    public static function addMonths(string $dateTime, int $value, string $format = self::DEFAULT_DATE_TIME_FORMAT): string
    {
        return (new Carbon($dateTime))->addMonths($value)->format($format);
    }

    /**
     * add mounth to dateTime parameter.
     *
     * @param string $dateTime 日時
     * @param int $value 加算年数
     * @param string $format datetime format
     * @return string $dateTimeの$value年後のdateTime
     */
    public static function addYears(string $dateTime, int $value, string $format = self::DEFAULT_DATE_TIME_FORMAT): string
    {
        return (new Carbon($dateTime))->addYears($value)->format($format);
    }

    /**
     * sub days to dateTime parameter.
     *
     * @param string $dateTime 日時
     * @param int $value 減算日数
     * @param string $format datetime format
     * @return string $dateTimeから$value日前の$dateTime
     */
    public static function subDays(string $dateTime, int $value, string $format = self::DEFAULT_DATE_TIME_FORMAT): string
    {
        return (new Carbon($dateTime))->subDays($value)->format($format);
    }

    /**
     * sub mounth to dateTime parameter.
     *
     * @param string $dateTime 日時
     * @param int $value 減算月数
     * @param string $format datetime format
     * @return string $dateTimeから$valueヶ月前の$dateTime
     */
    public static function subMonths(string $dateTime, int $value, string $format = self::DEFAULT_DATE_TIME_FORMAT): string
    {
        return (new Carbon($dateTime))->subMonths($value)->format($format);
    }

    /**
     * sub mounth to dateTime parameter.
     *
     * @param string $dateTime 日時
     * @param int $value 減算年数
     * @param string $format datetime format
     * @return string $dateTimeの$value年前のdateTime
     */
    public static function subYears(string $dateTime, int $value, string $format = self::DEFAULT_DATE_TIME_FORMAT): string
    {
        return (new Carbon($dateTime))->subYears($value)->format($format);
    }

    /**
     * add mounth to dateTime parameter.
     *
     * @param string $dateTime 日時
     * @param string $targetDateTime 比較対象の日付
     * @param string $format datetime format
     * @return int 日数
     */
    public static function diffDays(string $dateTime, string $targetDateTime): int
    {
        return (new Carbon($dateTime))->diffInDays($targetDateTime);
    }

    /**
     * check dateTime is greater than target.
     *
     * @param string $dateTime 日時
     * @param string $targetDateTime 比較対象の日付
     * @param string $format datetime format
     * @return bool
     */
    public static function greater(string $dateTime, string $targetDateTime): bool
    {
        return (new Carbon($dateTime))->greaterThan($targetDateTime);
    }

    /**
     * check dateTime is less than target.
     *
     * @param string $dateTime 日時
     * @param string $targetDateTime 比較対象の日付
     * @param string $format datetime format
     * @return bool
     */
    public static function lesser(string $dateTime, string $targetDateTime): bool
    {
        return (new Carbon($dateTime))->lessThan($targetDateTime);
    }

    /**
     * get first day of month of parameter day.
     *
     * @param ?int $timestamp タイムスタンプ
     * @param string $format フォーマット
     * @return string 月初
     */
    public static function startDayOfMonth(
        ?int $timestamp,
        string $format = self::DEFAULT_DATE_TIME_FORMAT_DATE_ONLY
    ): string {
        $month = is_null($timestamp)
            ? null
            : self::timeStampToDate($timestamp, self::DEFAULT_DATE_TIME_FORMAT_YEAR_MONTH_ONLY);
        return date($format, strtotime('first day of ' . $month));
    }

    /**
     * get lasf day of month of parameter day.
     *
     * @param ?int $timestamp タイムスタンプ
     * @param string $format フォーマット
     * @return string 月末
     */
    public static function lastDayOfMonth(
        ?int $timestamp,
        string $format = self::DEFAULT_DATE_TIME_FORMAT_DATE_ONLY
    ): string {
        $month = is_null($timestamp)
            ? null
            : self::timeStampToDate($timestamp, self::DEFAULT_DATE_TIME_FORMAT_YEAR_MONTH_ONLY);
        return date($format, strtotime('last day of ' . $month));
    }

    /**
     * check date format.(Y/m/d)
     *
     * @param string $date 日
     * @return bool
     */
    public static function checkDateFormat(string $date): bool
    {
        return (bool)preg_match('/^[1-9]{1}[0-9]{0,3}\/[0-9]{1,2}\/[0-9]{1,2}$/', $date);
    }

    /**
     * check date format separated by hyphen.(Y-m-d)
     *
     * @param string $date 日時
     * @return bool
     */
    public static function checkDateFormatByHyphen(string $date): bool
    {
        return (bool)preg_match('/^[1-9]{1}[0-9]{0,3}-[0-9]{1,2}-[0-9]{1,2}$/', $date);
    }

    /**
     * check dateTime format.(Y/m/d H:i:s)
     *
     * @param string $dateTime 日時
     * @return bool 日数
     */
    public static function checkDateTimeFormat(string $dateTime): bool
    {
        return (bool)preg_match('/^[1-9]{1}[0-9]{0,3}\/[0-9]{1,2}\/[0-9]{1,2} ([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $dateTime);
    }

    /**
     * check dateTime format separated by hyphen.(Y-m-d H:i:s)
     *
     * @param string $dateTime 日時
     * @return bool
     */
    public static function checkDateTimeFormatByHyphen(string $dateTime): bool
    {
        return (bool)preg_match('/^[1-9]{1}[0-9]{0,3}-[0-9]{1,2}-[0-9]{1,2} ([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $dateTime);
    }
}
