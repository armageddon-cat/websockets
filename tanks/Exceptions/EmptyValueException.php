<?php
declare(strict_types=1);
namespace Exceptions;

/**
 * Class EmptyValueException
 * @package Exceptions
 */
class EmptyValueException extends \InvalidArgumentException
{
    
    /**
     * EmptyValueException constructor.
     *
     * @param string $valueName
     */
    public function __construct(string $valueName)
    {
        parent::__construct('empty ' . $valueName);
    }
}
