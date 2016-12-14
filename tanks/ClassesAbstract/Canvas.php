<?php
declare(strict_types=1);
namespace ClassesAbstract;

/**
 * Class Canvas
 * @package ClassesAbstract
 */
abstract class Canvas
{
    public const CANVAS_START = 0;
    public const CANVAS_SIZE = 800;
    public const CODE_LEFT_ARROW = 37;
    public const CODE_UP_ARROW = 38;
    public const CODE_RIGHT_ARROW = 39;
    public const CODE_DOWN_ARROW = 40;
    public const AXIS_X = 'x';
    public const AXIS_Y = 'y';
    public const CODE_ALL_ARROWS = [
        self::CODE_LEFT_ARROW,
        self::CODE_UP_ARROW,
        self::CODE_RIGHT_ARROW,
        self::CODE_DOWN_ARROW,
    ];
}
