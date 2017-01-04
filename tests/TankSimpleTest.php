<?php
declare(strict_types=1);

namespace Tests;

use ClassesAbstract\Canvas;
use PHPUnit_Framework_TestCase;
use Tanks\Tank;
use Tanks\TankMoveRoute;
use Validators\GuidValidator;

/**
 * Class TankSimpleTest
 */
class TankSimpleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Tank
     */
    private $tank;

    public function setUp(): void
    {
        $this->tank = new Tank(new \DateTime());
    }

    public function testNewTank(): void
    {
        $this->assertInstanceOf(Tank::class, $this->tank);
    }

    public function testGetId(): void
    {
        $this->assertRegExp(GuidValidator::GUID_PATTERN, $this->tank->getId());
    }

    public function testGetX(): void
    {
        $this->assertContains($this->tank->getX(), range(Canvas::CANVAS_START, Canvas::CANVAS_SIZE - Tank::TANK_SIZE));
    }

    public function testGetY(): void
    {
        $this->assertContains($this->tank->getY(), range(Canvas::CANVAS_START, Canvas::CANVAS_SIZE - Tank::TANK_SIZE));
    }

    public function testGetRandomRespPoint(): void
    {
        $this->assertContains(
            $this->tank->getRandomRespPoint(),
            range(Canvas::CANVAS_START, Canvas::CANVAS_SIZE - Tank::TANK_SIZE));
    }

    public function testGetTankBarrelX(): void
    {
        $this->assertInternalType('int', $this->tank->getTankBarrelX());
    }

    public function testGetTankBarrelY(): void
    {
        $this->assertInternalType('int', $this->tank->getTankBarrelY());
    }

    public function testGetTankCenterX(): void
    {
        $this->assertInternalType('int', $this->tank->getTankCenterX());
    }

    public function testGetTankCenterY(): void
    {
        $this->assertInternalType('int', $this->tank->getTankCenterY());
    }

    public function testGetBulletEmpty(): void
    {
        $this->expectException(\TypeError::class);
        $this->tank->getBullet();
    }

    public function testGetDirection(): void
    {
        $this->assertContains($this->tank->getDirection(), Canvas::CODE_ALL_ARROWS);
    }

    public function testGetTime(): void
    {
        $this->assertInstanceOf(\DateTime::class, $this->tank->getTime());
    }

    public function testIsAlive(): void
    {
        $this->assertInternalType('bool', $this->tank->isAlive());
    }

    public function testPrepareToClientJson(): void
    {
        $this->assertJson($this->tank->prepareToClientJson());
    }

    public function testGetRoute(): void
    {
        $this->assertInstanceOf(TankMoveRoute::class, $this->tank->getRoute());
    }
}
