<?php
declare(strict_types=1);
namespace Tanks;


class InvalidGuidException extends \InvalidArgumentException
{
    
    /**
     * InvalidGuidException constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid id');
    }
}