<?php
declare(strict_types=1);
namespace ClassesAbstract;

/**
 * Class Canvas
 * @package ClassesAbstract
 */
abstract class Canvas
{
    const CANVAS_START = 0;
    const CANVAS_SIZE = 800;
    const CODE_LEFT_ARROW = 37;
    const CODE_UP_ARROW = 38;
    const CODE_RIGHT_ARROW = 39;
    const CODE_DOWN_ARROW = 40;
    const AXIS_X = 'x';
    const AXIS_Y = 'y';
    const CODE_ALL_ARROWS = [self::CODE_LEFT_ARROW, self::CODE_UP_ARROW, self::CODE_RIGHT_ARROW, self::CODE_DOWN_ARROW];
}