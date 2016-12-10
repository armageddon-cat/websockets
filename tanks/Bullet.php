<?php
declare(strict_types=1);
namespace Tanks;

use ClassesAbstract\BulletAbstract;
use ClassesAbstract\Canvas;

/**
 * Class Bullet
 * @package Tanks
 */
class Bullet extends BulletAbstract
{
    /**
     * Bullet constructor.
     *
     * @param ClientMessageContainer $cliMessage
     */
    public function __construct(ClientMessageContainer $cliMessage)
    {
        $this->setId($cliMessage->getId());
        $this->setTank(TankRegistry::get($this->getId()));
        $this->setDirection($this->getTank()->getDirection());
        $this->setPath(); // todo check if needed
        $this->setX($this->getTank()->getTankBarrelX());
        $this->setY($this->getTank()->getTankBarrelY());
        $this->setShootTime($cliMessage->getTime());
        $this->setPathTime();
    }
    
    /**
     * @param ClientMessageContainer $message
     *
     * @return void
     */
    public static function create(ClientMessageContainer $message): void
    {
        if ($message->getType() === ClientMessageContainer::TYPE_BULLET) {
            if (!BulletRegistry::exists($message->getId())) {
                $bullet = new self($message);
                BulletRegistry::add($bullet);
                $tank = TankRegistry::get($message->getId());
                $tank->setBullet($bullet);
            }
        }
    }
    
    /**
     * canvas has form
     * 0 xx 100
     * y
     * y
     * 100
     *
     * @return void
     */
    public function setPath(): void
    {
        $direction = $this->getDirection();
        $tank = $this->getTank();
        $startPosition = 0;
        $endPosition = 0;
        if ($direction === Canvas::CODE_LEFT_ARROW || $direction === Canvas::CODE_RIGHT_ARROW) {
            $startPosition = $tank->getTankBarrelX();
        }
        if ($direction === Canvas::CODE_UP_ARROW || $direction === Canvas::CODE_DOWN_ARROW) {
            $startPosition = $tank->getTankBarrelY();
        }
        if ($direction === Canvas::CODE_DOWN_ARROW || $direction === Canvas::CODE_RIGHT_ARROW) {
            $endPosition = Canvas::CANVAS_SIZE;
        }
        if ($direction === Canvas::CODE_UP_ARROW || $direction === Canvas::CODE_LEFT_ARROW) {
            $endPosition = Canvas::CANVAS_START;
        }
        $this->path = range($startPosition, $endPosition);
    }
    
    /**
     * @param int $position
     *
     * @return \DateTime
     */
    public function getBulletTimeByPosition(int $position): \DateTime
    {
        return $this->getPathTime()[$position];
    }
    
    /**
     * return bool
     */
    public function isHit(): bool
    {
        $direction = $this->getDirection();
        $tanksStorage = TankRegistry::getInstance();
        $tanksStorage->rewind();
        while ($tanksStorage->valid()) {
            $tank = $tanksStorage->current();
            if ($this->getId() === $tank->getId()) {
                $tanksStorage->next();
                continue;
            }
            if ($direction === Canvas::CODE_LEFT_ARROW || $direction === Canvas::CODE_RIGHT_ARROW) {
                for ($i = -Tank::TANK_HIT_AREA; $i <= Tank::TANK_HIT_AREA; $i++) { // intersection area = tank center +- 20
//                    var_dump('TCY'.$tank->getTankCenterY().'TCX'.$tank->getTankCenterX().
//                             'TCYi'.($tank->getTankCenterY()+$i).'TCXi'.($tank->getTankCenterX()+$i).
//                             'BY'.($this->getY()).'BX'.($this->getX())); // useful. don't remove
                    if ($tank->getTankCenterY()+$i === $this->getY() && $tank->getTankCenterX()+$i === $this->getX()) {
                        $bulletTime = $this->getBulletTimeByPosition($tank->getTankCenterX() + $i);
                        if ($this->checkTimeIntersection($bulletTime, $tank)) {
                            return true;
                        }
                    }
                }
            }
            if ($direction === Canvas::CODE_UP_ARROW || $direction === Canvas::CODE_DOWN_ARROW) {
                for ($i = -Tank::TANK_HIT_AREA; $i <= Tank::TANK_HIT_AREA; $i++) { // intersection area = tank center +- 20
                    if ($tank->getTankCenterX()+$i === $this->getX() && $tank->getTankCenterY()+$i === $this->getY()) {
                        $bulletTime = $this->getBulletTimeByPosition($tank->getTankCenterY() + $i);
                        if ($this->checkTimeIntersection($bulletTime, $tank)) {
                            return true;
                        }
                    }
                }
            }
            $tanksStorage->next();
        }
        
        return false;
    }
    
    /**
     * Not used. Replaced with prepareToClientJson
     * @return string
     */
    public function __toString(): string
    {
        $result = [];
        foreach ($this as $key => $value) {
            if (is_object($value)) {
                continue;
            }
            if ($key === 'path' || $key === 'pathTime') {
                continue; // todo refactor
            }
            
            $result[$key] = $value;
        }
        return json_encode($result);
    }
    
    /**
     * Fields send to client
     * x
     * y
     *
     * @return string
     */
    public function prepareToClientJson(): string
    {
        $result = [];
        foreach ($this as $property => $value) {
            if (in_array($property, BulletAbstract::CLIENT_FIELDS, true)) {
                $result[$property] = $value;
            }
        }
        return json_encode($result);
    }
    
    public function setPathTime(): void
    {
        // take microseconds as a number
        $timeStrMicroseconds = (int)$this->getShootTime()->format('u') + self::BULLET_DELAY_REAL_MICROSECONDS;
        foreach ($this->getPath() as $path) {
            $iteratedDT            = new \DateTime($this->getShootTime()->format(DateTimeUser::DATE_TIME) . '.' . $timeStrMicroseconds);
            $this->pathTime[$path] = $iteratedDT;
            $timeStrMicroseconds = (int)$iteratedDT->format('u') + self::BULLET_DELAY_REAL_MICROSECONDS;
        }
    }
    
    public function move(): void
    {
        $direction = $this->getDirection();
        if ($direction === Canvas::CODE_LEFT_ARROW) {
            $this->x -= Bullet::BULLET_STEP;
        }
        if ($direction === Canvas::CODE_UP_ARROW) {
            $this->y -= Bullet::BULLET_STEP;
        }
        if ($direction === Canvas::CODE_RIGHT_ARROW) {
            $this->x += Bullet::BULLET_STEP;
        }
        if ($direction === Canvas::CODE_DOWN_ARROW) {
            $this->y += Bullet::BULLET_STEP;
        }
    }
    
    /**
     * @param BulletAbstract $bullet
     *
     * @return bool
     */
    public function inBounds(BulletAbstract $bullet): bool
    {
        $bulX = $bullet->getX();
        $bulY = $bullet->getY();
        if ($bulX < Canvas::CANVAS_START || $bulX > Canvas::CANVAS_SIZE ||
            $bulY < Canvas::CANVAS_START || $bulY > Canvas::CANVAS_SIZE
        ) {
            return false;
        }
        return true;
    }
    
    /**
     * @param \DateTime $bulletTime
     * @param Tank $tank
     *
     * @return bool
     */
    protected function checkTimeIntersection(\DateTime $bulletTime, Tank $tank): bool
    {
        $bTimestamp = (float)$bulletTime->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
        $tankRoute  = $tank->getRoute();
        $index      = $tank->getTankCenterX() . TankMoveRoute::COORDINATES_DIVIDER . $tank->getTankCenterY();
        if ($tankRoute->checkMove($index)) {
            $tankCurrentMove = $tankRoute->getMove($index);
            $tCMTimestamp    = (float)$tankCurrentMove->getTime()->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
            $tankMoves       = $tankRoute->getTankMoves();
            while (current($tankMoves) !== $tankCurrentMove) {
                next($tankMoves); // rewind to current position
            }
            $nextTankMove = next($tankMoves); // if tank moved from the shoot time
            if ($nextTankMove) {
                $tNMTimestamp = (float)$nextTankMove->getTime()->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
                if ($bTimestamp > $tCMTimestamp && $bTimestamp < $tNMTimestamp) {
                    $tank->setStatus(Tank::DEAD);
                }
            }
            if ($bTimestamp > $tCMTimestamp) {
                $tank->setStatus(Tank::DEAD);
                return true;
            }
        }
        return false;
    }
}
