<?php
declare(strict_types=1);

namespace Tests;

use ClassesAbstract\Canvas;
use Tanks\Tank;
use Tanks\TankMove;
use Tanks\TankMoveRoute;

/**
 * Class TankTest
 * @package Tests
 */
class TankTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Tank
     */
    private $tank;

    public function setUp(): void
    {
        $this->tank = new Tank(new \DateTime());
    }

    public function testMoveParams(): void
    {
        $tank = $this->tank;

        $time = new \DateTime();
        $tank->moveTank($time);

        $this->assertInstanceOf(\DateTime::class, $tank->getTime());
        $this->assertSame($tank->getTime(), $time);
        $this->assertInstanceOf(TankMoveRoute::class, $tank->getRoute());
        $this->assertInstanceOf(TankMove::class, $tank->getRoute()->getMove($tank->getRoute()->getMoveIndex($tank)));
    }

    public function testMoveUp(): void
    {
        $tank = $this->tank;
        $tankYPos = $tank->getY();

        $tank->setDirection(Canvas::CODE_UP_ARROW);
        $time = new \DateTime();
        $tank->moveTank($time);

        $this->assertEquals($tank->getDirection(), Canvas::CODE_UP_ARROW);
        $this->assertEquals($tankYPos - Tank::TANK_STEP, $tank->getY());
    }

    public function testMoveDown(): void
    {
        $tank = $this->tank;
        $tankYPos = $tank->getY();

        $tank->setDirection(Canvas::CODE_DOWN_ARROW);
        $time = new \DateTime();
        $tank->moveTank($time);

        $this->assertEquals($tank->getDirection(), Canvas::CODE_DOWN_ARROW);
        $this->assertEquals($tankYPos + Tank::TANK_STEP, $tank->getY());
    }

    public function testMoveRight(): void
    {
        $tank = $this->tank;
        $tankXPos = $tank->getX();

        $tank->setDirection(Canvas::CODE_RIGHT_ARROW);
        $time = new \DateTime();
        $tank->moveTank($time);

        $this->assertEquals($tank->getDirection(), Canvas::CODE_RIGHT_ARROW);
        $this->assertEquals($tankXPos + Tank::TANK_STEP, $tank->getX());
    }

    public function testMoveLeft(): void
    {
        $tank = $this->tank;
        $tankXPos = $tank->getX();

        $tank->setDirection(Canvas::CODE_LEFT_ARROW);
        $time = new \DateTime();
        $tank->moveTank($time);

        $this->assertEquals($tank->getDirection(), Canvas::CODE_LEFT_ARROW);
        $this->assertEquals($tankXPos - Tank::TANK_STEP, $tank->getX());
    }

    // X coordinate

    public function testGetTankCenterXWithMoveUp(): void
    {
        $tank = $this->tank;
        $tankXPos = $tank->getX();

        $tank->setDirection(Canvas::CODE_UP_ARROW);
        $tankCenterX = $tank->getTankCenterX();

        $this->assertInternalType('int', $tankCenterX);
        $this->assertEquals($tankXPos + Tank::getTankWidthHalf() + Tank::TANK_PADDING_LEFT_BARREL_UP, $tankCenterX);
    }

    public function testGetTankCenterXWithMoveDown(): void
    {
        $tank = $this->tank;
        $tankXPos = $tank->getX();

        $tank->setDirection(Canvas::CODE_DOWN_ARROW);
        $tankCenterX = $tank->getTankCenterX();

        $this->assertInternalType('int', $tankCenterX);
        $this->assertEquals($tankXPos + Tank::getTankWidthHalf() + Tank::TANK_PADDING_LEFT_BARREL_UP, $tankCenterX);
    }

    public function testGetTankCenterXWithMoveRight(): void
    {
        $tank = $this->tank;
        $tankXPos = $tank->getX();

        $tank->setDirection(Canvas::CODE_RIGHT_ARROW);
        $tankCenterX = $tank->getTankCenterX();

        $this->assertInternalType('int', $tankCenterX);
        $this->assertEquals($tankXPos + Tank::getTankWidthHalf(), $tankCenterX);
    }

    public function testGetTankCenterXWithMoveLeft(): void
    {
        $tank = $this->tank;
        $tankXPos = $tank->getX();

        $tank->setDirection(Canvas::CODE_LEFT_ARROW);
        $tankCenterX = $tank->getTankCenterX();

        $this->assertInternalType('int', $tankCenterX);
        $this->assertEquals($tankXPos + Tank::getTankWidthHalf() + Tank::TANK_BARREL_LENGTH, $tankCenterX);
    }

    // Y coordinate

    public function testGetTankCenterYWithMoveUp(): void
    {
        $tank = $this->tank;
        $tankYPos = $tank->getY();

        $tank->setDirection(Canvas::CODE_UP_ARROW);
        $tankCenterY = $tank->getTankCenterY();

        $this->assertInternalType('int', $tankCenterY);
        $this->assertEquals($tankYPos + Tank::getTankWidthHalf() + Tank::TANK_BARREL_LENGTH, $tankCenterY);
    }

    public function testGetTankCenterYWithMoveDown(): void
    {
        $tank = $this->tank;
        $tankYPos = $tank->getY();

        $tank->setDirection(Canvas::CODE_DOWN_ARROW);
        $tankCenterY = $tank->getTankCenterY();

        $this->assertInternalType('int', $tankCenterY);
        $this->assertEquals($tankYPos + Tank::getTankWidthHalf(), $tankCenterY);
    }

    public function testGetTankCenterYWithMoveRight(): void
    {
        $tank = $this->tank;
        $tankYPos = $tank->getY();

        $tank->setDirection(Canvas::CODE_RIGHT_ARROW);
        $tankCenterY = $tank->getTankCenterY();

        $this->assertInternalType('int', $tankCenterY);
        $this->assertEquals($tankYPos + Tank::getTankWidthHalf() + Tank::TANK_PADDING_LEFT_BARREL_UP, $tankCenterY);
    }

    public function testGetTankCenterYWithMoveLeft(): void
    {
        $tank = $this->tank;
        $tankYPos = $tank->getY();

        $tank->setDirection(Canvas::CODE_LEFT_ARROW);
        $tankCenterY = $tank->getTankCenterY();

        $this->assertInternalType('int', $tankCenterY);
        $this->assertEquals($tankYPos + Tank::getTankWidthHalf() + Tank::TANK_PADDING_LEFT_BARREL_UP, $tankCenterY);
    }

}
