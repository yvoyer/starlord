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

    /**
     * @param StartPlayerTurn $command
     */
    public function __invoke(StartPlayerTurn $command)
    {
        $player = $this->players->getPlayerWithId($command->playerId());
        $player->startTurn();

        $this->players->savePlayer($command->playerId(), $player);
    }

//    /**
//     * @param GameWasStarted $event
//     */
//    public function onGameWasStarted(GameWasStarted $event)
//    {
//        foreach ($event->players() as $playerId) {
//            $this->__invoke(new StartPlayerTurn($playerId));
//        }
//    }

    /**
     * @param TurnWasStarted $event
     */
    public function onTurnWasStarted(TurnWasStarted $event)
    {
        $players = $this->players->playersOfGame();
        foreach ($players as $player) {
            $this->__invoke(new StartPlayerTurn($player->getIdentity()));
        }
    }
}
