<?php
declare(strict_types=1);
namespace Tanks;


class EmptyPayLoadException extends EmptyValueException
{
    
    /**
     * EmptyPayLoadException constructor.
     */
    public function __construct()
    {
        parent::__construct('payload');
    }
}