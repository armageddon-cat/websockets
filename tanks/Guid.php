<?php
declare(strict_types = 1);
namespace Tanks;


use Validators\GuidValidator;

//SELECT UUID() AS Ref
class Guid
{
    public static function newRef()
    {
        if (function_exists('com_create_guid')) {
            $ref = com_create_guid();
        } else {
            $seed = microtime() * 1000000;
            mt_srand((int)$seed);
            $charid = md5(uniqid((string)mt_rand(), true));
            $hyphen = chr(45);// "-"
            $ref    = substr($charid, 0, 8) . $hyphen
                      . substr($charid, 8, 4) . $hyphen
                      . substr($charid, 12, 4) . $hyphen
                      . substr($charid, 16, 4) . $hyphen
                      . substr($charid, 20, 12);
        }
        if (!GuidValidator::validate($ref)) {
            throw new \UnexpectedValueException('Invalid Ref');
        }
        
        return $ref;
    }
}