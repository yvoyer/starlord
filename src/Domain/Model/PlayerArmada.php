<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface PlayerArmada
{
    /**
     * @param ShipId $shipId
     * @param PlayerId $playerId
     *
     * @return WriteOnlyShip
     */
    public function shipWithId(ShipId $shipId, PlayerId $playerId): WriteOnlyShip;

    /**
     * @param WriteOnlyShip $ship
     */
    public function saveShip(WriteOnlyShip $ship);
}
