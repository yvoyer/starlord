<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\PlayerTurnHasEnded;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class EndPlayerTurnHandler
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param WriteOnlyPlayers $players
     * @param Publisher $publisher
     */
    public function __construct(WriteOnlyPlayers $players, Publisher $publisher)
    {
        $this->players = $players;
        $this->publisher = $publisher;
    }

    /**
     * @param EndPlayerTurn $command
     */
    public function __invoke(EndPlayerTurn $command)
    {
        $player = $this->players->getPlayerWithId($command->playerId());
        $player->endTurn();

        $this->players->savePlayer($command->playerId(), $player);
        $this->publisher->publish(new PlayerTurnHasEnded($command->playerId()));
    }
}
