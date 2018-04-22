<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\HomeWorldWasSelected;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class SelectHomeWorldHandler
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

    public function __invoke(SelectHomeWorld $command)
    {
        $playerId = $command->playerId();
        $player = $this->players->getPlayerWithId($playerId);
        $player->performAction($command);

        $this->players->savePlayer($playerId, $player);
        $this->publisher->publish(new HomeWorldWasSelected($playerId, $command->homeWorldId()));
    }
}
