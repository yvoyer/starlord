<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ShipId;

final class ColonsWereLoaded implements StarLordEvent
{
    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var Colons
     */
    private $colons;

    /**
     * @var ShipId
     */
    private $shipId;

    /**
     * @param PlayerId $playerId
     * @param Colons $colons
     * @param ShipId $shipId
     */
    public function __construct(PlayerId $playerId, Colons $colons, ShipId $shipId)
    {
        $this->playerId = $playerId;
        $this->colons = $colons;
        $this->shipId = $shipId;
    }

    /**
     * @return PlayerId
     */
    public function playerId(): PlayerId
    {
        return $this->playerId;
    }

    /**
     * @return Colons
     */
    public function colons(): Colons
    {
        return $this->colons;
    }

    /**
     * @return ShipId
     */
    public function shipId(): ShipId
    {
        return $this->shipId;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
