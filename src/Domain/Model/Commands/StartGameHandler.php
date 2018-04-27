<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\GameWasStarted;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class StartGameHandler
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
     * @param StartGame $command
     */
    public function __invoke(StartGame $command)
    {
        foreach ($command->players() as $playerId) {
            $player = $this->players->getPlayerWithId($playerId);
            $player->startGame();

            $this->players->savePlayer($playerId, $player);
        }

        $this->publisher->publish(new GameWasStarted($command->players()));
    }
}
