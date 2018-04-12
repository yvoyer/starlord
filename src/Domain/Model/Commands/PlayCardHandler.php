<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\CardWasPlayed;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class PlayCardHandler
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

    public function __invoke(PlayCard $command)
    {
        $player = $this->players->getPlayerWithId($command->playerId());
        $player->playCard($command->cardId());

        $this->players->savePlayer($command->playerId(), $player);
        $this->publisher->publish(new CardWasPlayed($command->cardId(), $command->playerId()));
    }
}
