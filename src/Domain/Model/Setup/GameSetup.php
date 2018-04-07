<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use StarLord\Domain\Events\GameWasStarted;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\Commands\StartGame;
use StarLord\Domain\Model\Publisher;

final class GameSetup
{
    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param Publisher $publisher
     */
    public function __construct(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @param StartGame $command
     */
    public function __invoke(StartGame $command)
    {
        foreach ($command->players() as $player) {
            $this->publisher->publish(new PlayerJoinedGame($player));
        }

        $this->publisher->publish(new GameWasStarted());
    }
}
