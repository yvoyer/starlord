<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\Actions\MoveShipAction;
use StarLord\Domain\Model\PlayerArmada;
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
     * @param WriteOnlyPlayers $players
     * @param PlayerArmada $armada
     * @param World $world
     */
    public function __construct(
        WriteOnlyPlayers $players,
        PlayerArmada $armada,
        World $world
    ) {
        $this->players = $players;
        $this->armada = $armada;
        $this->world = $world;
    }

    /**
     * @param MoveShip $command
     */
    public function __invoke(MoveShip $command)
    {
        $playerId = $command->playerId()->toInt();
        $player = $this->players->getPlayerWithId($playerId);
        $player->performAction(
            new MoveShipAction(
                $this->armada->shipWithId($command->shipId(), $command->playerId()),
                $command->destination()
            )
        );

        $this->players->savePlayer($playerId, $player);

    }
}
