<?php
declare(strict_types=1);

namespace Tanks;

/**
 * Class TankMoveRoute
 * @package Tanks
 */
class TankMoveRoute
{
    public const COORDINATES_DIVIDER = ':';
    /**
     *  @param array $storage
     */
    private $storage = [];
    
    /**
     * TankMoveRoute constructor.
     *
     * @param \Tanks\Tank $tank
     */
    public function __construct(Tank $tank)
    {
        $this->storage[$tank->getTankCenterX(). self::COORDINATES_DIVIDER .$tank->getTankCenterY()] = new TankMove($tank);
    }
    
    /**
     * @param Tank $tank
     */
    public function addMove(Tank $tank): void
    {
        $firstMove = $this->getFirst();
        $firstMoveTime = (float)$firstMove->getTime()->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
        $currentMoveTime = (float)$tank->getTime()->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
        $diff = $currentMoveTime - $firstMoveTime;
        if ($diff > Bullet::BULLET_FLY_TIME_SECONDS) {
            reset($this->storage);
            unset($this->storage[key($this->storage)]);
        }
        $this->storage[$tank->getTankCenterX(). self::COORDINATES_DIVIDER .$tank->getTankCenterY()] = new TankMove($tank);
    }
    
    /**
     * @return TankMove
     */
    public function getFirst(): TankMove
    {
        return reset($this->storage);
    }
    
    /**
     * @param $id
     *
     * @return TankMove
     */
    public function getMove(string $id): TankMove
    {
        return $this->storage[$id];
    }
    
    /**
     * @param $id
     *
     * @return bool
     */
    public function checkMove(string $id): bool
    {
        return isset($this->storage[$id]);
    }
    
    /**
     * @param $id
     */
    public function removeMove(string $id): void
    {
        unset($this->storage[$id]);
    }
    
    /**
     * @return TankMove[]
     */
    public function getTankMoves(): array
    {
        return $this->storage;
    }
    
    /**
     *
     */
    public function unsetStorage(): void
    {
        $this->storage = [];
    }
}
