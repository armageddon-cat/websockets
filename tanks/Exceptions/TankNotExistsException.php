<?php
declare(strict_types=1);
namespace Exceptions;

/**
 * Class TankNotExistsException
 * @package Exceptions
 */
class TankNotExistsException extends \LogicException
{
    /**
     * TankNotExistsException constructor.
     */
    public function __construct()
    {
        parent::__construct('tank does not exist');
    }
}
