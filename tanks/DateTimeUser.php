<?php
declare(strict_types=1);
namespace Tanks;

/**
 * Class DateTimeUser
 * @package Tanks
 */
class DateTimeUser extends \DateTime
{
    public const UNIX_TIMESTAMP_MICROSECONDS = 'U.u';
    public const UNIX_TIMESTAMP_MICROSECONDS_SHORT = 'Uu';
    public const DATE_TIME = 'Y-m-d H:i:s';
    
    /**
     * Returns "(string)microtime(true)" value
     *
     * @param bool $get_as_float
     *
     * @return string
     */
    public static function getMicroTimeString(bool $get_as_float = true): string
    {
        return (string)microtime($get_as_float);
    }
    
    /**
     * Create datetime object with microseconds
     *
     * @return \DateTime
     */
    public static function createDateTimeMicro(): \DateTime
    {
        return \DateTime::createFromFormat(self::UNIX_TIMESTAMP_MICROSECONDS, self::getMicroTimeString());
    }
}
