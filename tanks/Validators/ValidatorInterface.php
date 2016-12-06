<?php
declare(strict_types=1);
namespace Validators;

/**
 * Interface ValidatorInterface
 * @package Validators
 */
interface ValidatorInterface
{
    /**
     * @param $value
     *
     * @return bool
     */
    public static function validate($value): bool;
}
