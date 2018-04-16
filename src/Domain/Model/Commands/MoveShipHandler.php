<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\ShipWasMoved;
use StarLord\Domain\Model\PlayerArmada;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\World;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class MoveShipHandler
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var PlayerArmada
     */
    private $armada;

    /**
     * @var World
     */
    private $world;

    /**
     * @var Publisher
     */
    private $publish;

    /**
     * @param WriteOnlyPlayers $players
     * @param PlayerArmada $armada
     * @param World $world
     * @param Publisher $publish
     */
    public function __construct(
        WriteOnlyPlayers $players,
        PlayerArmada $armada,
        World $world,
        Publisher $publish
    ) {
        $this->players = $players;
        $this->armada = $armada;
        $this->world = $world;
        $this->publish = $publish;
    }

    /**
     * @param MoveShip $command
     */
    public function __invoke(MoveShip $command)
    {
        $playerId = $command->playerId();
        $shipId = $command->shipId();
        $destinationId = $command->destination();
        $player = $this->players->getPlayerWithId($playerId);
        $ship = $this->armada->shipWithId($shipId, $playerId);

        $ship->moveTo($destinationId);

        $this->players->savePlayer($playerId, $player);
        $this->armada->saveShip($ship);

        $this->publish->publish(
            new ShipWasMoved(
                $playerId,
                $shipId,
                $ship->locationId(),
                $destinationId
            )
        );
    }
}
