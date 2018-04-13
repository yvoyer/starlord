<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ShipId;

final class UnloadColons
{
    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var ShipId
     */
    private $shipId;

    /**
     * @param PlayerId $playerId
     * @param int $quantity
     * @param ShipId $shipId
     */
    public function __construct(PlayerId $playerId, int $quantity, ShipId $shipId)
    {
        $this->playerId = $playerId;
        $this->quantity = $quantity;
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
     * @return int
     */
    public function quantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return ShipId
     */
    public function shipId(): ShipId
    {
        return $this->shipId;
    }
}
