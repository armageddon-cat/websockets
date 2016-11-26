<?php
declare(strict_types=1);
namespace Validators;


interface ValidatorInterface
{
    public static function validate($value) : bool;
}