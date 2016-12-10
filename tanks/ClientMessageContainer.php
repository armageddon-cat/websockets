<?php
declare(strict_types=1);
namespace Tanks;

use ClassesAbstract\Canvas;
use Exceptions\EmptyPayLoadException;
use Exceptions\EmptyValueException;
use Exceptions\InvalidDateTimeFormatException;
use Exceptions\InvalidGuidException;
use Exceptions\TankNotExistsException;
use Exceptions\JsonDecodingException;
use Validators\UnixTimeStampFloatValidator;
use Validators\GuidValidator;

/**
 * Class ClientMessageContainer
 * @package Tanks
 */
class ClientMessageContainer
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $type = '';
    /**
     * @var int
     */
    private $newDirection = 0;
    /**
     * @var \DateTime
     */
    private $time;
    /**
     * @var string
     */
    private $payLoad;
    
    const TYPE_BULLET = 'bullet';

    /**
     * ClientMessageContainer constructor.
     *
     * @param array $decodedMessage
     *
     * @throws \Exceptions\EmptyValueException
     * @throws \Exceptions\InvalidGuidException
     * @throws \Exceptions\TankNotExistsException
     * @throws \Exceptions\JsonDecodingException
     * @throws \Exceptions\EmptyPayLoadException
     * @throws \Exceptions\InvalidDateTimeFormatException
     */
    public function __construct(array $decodedMessage)
    {
        $this->setPayLoad($decodedMessage);
        $payLoadObject = $this->jsonDecodePayLoad($this->getPayLoad());
        $this->setId($payLoadObject);
        $this->setType($payLoadObject);
        $this->setNewDirection($payLoadObject);
        $this->setTime($payLoadObject);
    }
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return (string)$this->id;
    }
    
    /**
     * @param \stdClass $payLoadObject
     *
     * @throws \Exceptions\EmptyValueException
     * @throws \Exceptions\InvalidGuidException
     * @throws \Exceptions\TankNotExistsException
     */
    protected function setId(\stdClass $payLoadObject): void
    {
        if (empty($payLoadObject->id)) {
            throw new EmptyValueException('tank id');
        }
        if (!GuidValidator::validate($payLoadObject->id)) {
            throw new InvalidGuidException();
        }
        if (!TankRegistry::exists($payLoadObject->id)) { // todo bullet maybe not exists
            throw new TankNotExistsException();
        }
        $this->id = (string)$payLoadObject->id;
    }
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return (string)$this->type;
    }
    
    /**
     * @param \stdClass $payLoadObject
     *
     * @throws \Exceptions\EmptyValueException
     * @return void
     */
    protected function setType(\stdClass $payLoadObject): void
    {
        $this->type = '';
        if (isset($payLoadObject->type) && $payLoadObject->type === self::TYPE_BULLET) { // todo maybe throw exception
            $this->type = $payLoadObject->type;
        }
    }
    
    /**
     * @return int
     */
    public function getNewDirection(): int
    {
        return $this->newDirection;
    }
    
    /**
     * @return bool
     */
    public function isNewDirection(): bool
    {
        return (bool)$this->getNewDirection();
    }
    
    /**
     * @param \stdClass $payLoadObject
     *
     * @return void
     */
    protected function setNewDirection(\stdClass $payLoadObject): void
    {
        if (!isset($payLoadObject->type) // type is set only for bullet
            && in_array((int)$payLoadObject->newd, Canvas::CODE_ALL_ARROWS, true)) {
            $this->newDirection = (int)$payLoadObject->newd;
        }
    }
    
    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * maybe fail when milliseconds is null
     * @param \stdClass $payLoadObject
     *
     * @throws \Exceptions\InvalidDateTimeFormatException
     */
    protected function setTime(\stdClass $payLoadObject): void
    {
        if (!isset($payLoadObject->time) || !UnixTimeStampFloatValidator::validate($payLoadObject->time)) {
            throw new InvalidDateTimeFormatException();
        }
        $this->time = \DateTime::createFromFormat(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS, (string)$payLoadObject->time); // todo check this
    }
    
    /**
     * @return string
     */
    protected function getPayLoad(): string
    {
        return $this->payLoad;
    }
    
    /**
     * @param array $decodedMessage
     *
     * @throws \Exceptions\EmptyPayLoadException
     */
    protected function setPayLoad(array $decodedMessage): void
    {
        if (empty($decodedMessage['payload'])) {
            throw new EmptyPayLoadException();
        }
        $this->payLoad = $decodedMessage['payload'];
    }
    
    /**
     * @param string $getPayLoad
     *
     * @return \stdClass
     * @throws \Exceptions\JsonDecodingException
     */
    protected function jsonDecodePayLoad(string $getPayLoad): \stdClass
    {
        $decodedPayLoad = json_decode($getPayLoad);
        if ($decodedPayLoad === null) {
            throw new JsonDecodingException(json_last_error());
        }
        return $decodedPayLoad;
    }
}
