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
     * @var ShipId
     */
    private $shipId;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @param PlayerId $playerId
     * @param ShipId $shipId
     * @param int $quantity
     */
    public function __construct(
        PlayerId $playerId,
        ShipId $shipId,
        int $quantity
    ) {
        $this->playerId = $playerId;
        $this->shipId = $shipId;
        $this->quantity = $quantity;
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
