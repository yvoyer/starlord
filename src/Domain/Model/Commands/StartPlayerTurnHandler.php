<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\GameWasStarted;
use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class StartPlayerTurnHandler
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @param WriteOnlyPlayers $players
     */
    public function __construct(WriteOnlyPlayers $players)
    {
        $this->players = $players;
    }

    public function __invoke(StartPlayerTurn $command)
    {
        $player = $this->players->getPlayerWithId($command->playerId());
        $player->endTurn();

        $this->players->savePlayer($command->playerId(), $player);
//       new TurnWasStarted()
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function onGameWasStarted(GameWasStarted $event)
    {
        foreach ($event->players() as $playerId) {
            $this->__invoke(new StartPlayerTurn($playerId));
        }
    }
}
