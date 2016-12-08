<?php
declare(strict_types=1);

namespace Validators;

/**
 * Class UnixTimeStampFloatValidator
 * @package Validators
 */
class UnixTimeStampFloatValidator extends DateTimeValidator
{
    /**
     * Validates string like 1481039154.9632
     *
     * @param $value
     *
     * @return bool
     */
    public static function validate($value): bool
    {
        return preg_match('/^\d{10}\.\d{1,4}$/', (string)$value) === 1;
    }
}
