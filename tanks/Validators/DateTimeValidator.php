<?php
declare(strict_types=1);

namespace Validators;

/**
 * Class DateTimeValidator
 * @package tanks\Validators
 */
abstract class DateTimeValidator implements ValidatorInterface
{
    /**
     * @param $value
     *
     * @return bool
     */
    abstract public static function validate($value): bool;
}
