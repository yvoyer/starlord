<?php declare(strict_types=1);

namespace StarLord\Infrastructure\Persistence\InMemory;

use Star\Component\Identity\Exception\EntityNotFoundException;
use StarLord\Domain\Model\PlayerArmada;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ShipId;
use StarLord\Domain\Model\WriteOnlyShip;

final class ShipCollection implements PlayerArmada
{
    /**
     * @var WriteOnlyShip[]
     */
    private $ships = [];

    /**
     * @param WriteOnlyShip[] $ships
     */
    public function __construct(array $ships = [])
    {
        array_map(
            function (WriteOnlyShip $ship) {
                $this->ships[$ship->getIdentity()->toString()] = $ship;
            },
            $ships
        );
    }

    /**
     * @param ShipId $shipId
     * @param PlayerId $playerId
     *
     * @return WriteOnlyShip
     * @throws EntityNotFoundException
     */
    public function shipWithId(ShipId $shipId, PlayerId $playerId): WriteOnlyShip
    {
        if (! isset($this->ships[$shipId->toString()])) {
            throw EntityNotFoundException::objectWithIdentity($shipId);
        }

        return $this->ships[$shipId->toString()];
    }

    /**
     * @param WriteOnlyShip $ship
     */
    public function saveShip(WriteOnlyShip $ship)
    {
        $this->ships[$ship->getIdentity()->toString()] = $ship;
    }
}
