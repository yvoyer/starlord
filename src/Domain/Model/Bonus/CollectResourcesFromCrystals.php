<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Bonus;

use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class CollectResourcesFromCrystals
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

    public function onTurnWasStarted(TurnWasStarted $event)
    {
        $players = $this->players->playersOfGame();
        foreach ($players as $playerId => $player) {
            $player->collectResourcesFromCrystals();
            $this->players->savePlayer($playerId, $player);
        }
    }
}
