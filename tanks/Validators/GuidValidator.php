<?php
declare(strict_types=1);
namespace Validators;


class GuidValidator implements AbstractValidator
{
    public static function validate($ref) : bool
    {
        return preg_match('/^([0-9abcdef]{8}-[0-9abcdef]{4}-[0-9abcdef]{4}-[0-9abcdef]{4}-[0-9abcdef]{12})$/',$ref)===1;
    }
}