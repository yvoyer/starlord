<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ShipId;

final class MoveShip
{
    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var ShipId
     */
    private $shipId;

    /**
     * @var PlanetId
     */
    private $destination;

    /**
     * @param PlayerId $playerId
     * @param ShipId $shipId
     * @param PlanetId $destination
     */
    public function __construct(PlayerId $playerId, ShipId $shipId, PlanetId $destination)
    {
        $this->playerId = $playerId;
        $this->shipId = $shipId;
        $this->destination = $destination;
    }

    /**
     * @return PlayerId
     */
    public function playerId(): PlayerId
    {
        return $this->playerId;
    }

    /**
     * @return ShipId
     */
    public function shipId(): ShipId
    {
        return $this->shipId;
    }

    /**
     * @return PlanetId
     */
    public function destination(): PlanetId
    {
        return $this->destination;
    }
}
