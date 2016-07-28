<?php
declare(strict_types=1);
namespace Validators;


interface AbstractValidator
{
    public static function validate($value) : bool;
}