<?php
declare(strict_types=1);
namespace Tanks;


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