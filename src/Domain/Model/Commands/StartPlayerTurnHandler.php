<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

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
     * todo remove command trigger by events only???
     */
    public function __invoke(StartPlayerTurn $command)
    {
        $player = $this->players->getPlayerWithId($command->playerId());
        $player->startTurn();

        $this->players->savePlayer($command->playerId(), $player);
    }

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
