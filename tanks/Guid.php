<?php
declare(strict_types = 1);
namespace Tanks;


use Validators\GuidValidator;

//SELECT UUID() AS Ref
/**
 * Class Guid
 * @package Tanks
 */
class Guid
{
    /**
     * @return string
     * @throws \UnexpectedValueException
     */
    public static function newRef(): string
    {
        if (function_exists('com_create_guid')) {
            $ref = com_create_guid();
        } else {
            $seed = microtime() * 1000000;
            mt_srand((int)$seed);
            $charId = md5(uniqid((string)mt_rand(), true));
            $hyphen = chr(45);// "-"
            $ref    = substr($charId, 0, 8) . $hyphen
                      . substr($charId, 8, 4) . $hyphen
                      . substr($charId, 12, 4) . $hyphen
                      . substr($charId, 16, 4) . $hyphen
                      . substr($charId, 20, 12);
        }
        if (!GuidValidator::validate($ref)) {
            throw new \UnexpectedValueException('Invalid Ref');
        }
        
        return $ref;
    }
}