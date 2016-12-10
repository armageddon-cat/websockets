<?php
declare(strict_types=1);
namespace Tanks;

use Validators\GuidValidator;

//SELECT UUID() AS Guid
/**
 * Class Guid
 * @package Tanks
 */
abstract class Guid
{
    /**
     * @return string
     * @throws \UnexpectedValueException
     */
    public static function newRef(): string
    {
        if (function_exists('com_create_guid')) {
            $guid = com_create_guid();
        } else {
            // taken from http://stackoverflow.com/a/15875555
            // and from http://php.net/manual/ru/function.com-create-guid.php#117893
            $data = random_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
        if (!GuidValidator::validate($guid)) {
            throw new \UnexpectedValueException('Invalid Guid');
        }
        
        return $guid;
    }
}
