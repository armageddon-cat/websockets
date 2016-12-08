<?php
declare(strict_types=1);

namespace Exceptions;

/**
 * Class InvalidDateTimeFormatException
 * @package Exceptions
 */
class InvalidDateTimeFormatException extends \InvalidArgumentException
{

    /**
     * InvalidDateTimeFormatException constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid Date Time Format');
    }
}
