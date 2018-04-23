<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\GameWasStarted;
use StarLord\Domain\Model\Exception\NotCompletedActionException;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\ReadOnlyPlayers;

final class StartGameHandler
{
    /**
     * @var ReadOnlyPlayers
     */
    private $players;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param ReadOnlyPlayers $players
     * @param Publisher $publisher
     */
    public function __construct(ReadOnlyPlayers $players, Publisher $publisher)
    {
        $this->players = $players;
        $this->publisher = $publisher;
    }

    /**
     * @param StartGame $command
     */
    public function __invoke(StartGame $command)
    {
        if (! $this->players->allPlayersOfGameHavePlayed()) {
            throw new NotCompletedActionException(
                'Game cannot be started when some players have not completed their actions.'
            );
        }

        $this->publisher->publish(new GameWasStarted());
    }
}
