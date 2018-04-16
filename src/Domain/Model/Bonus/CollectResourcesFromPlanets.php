<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Bonus;

use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\World;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class CollectResourcesFromPlanets
{
    /**
     * @var World
     */
    private $world;

    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @param World $world
     * @param WriteOnlyPlayers $players
     */
    public function __construct(World $world, WriteOnlyPlayers $players)
    {
        $this->world = $world;
        $this->players = $players;
    }

    public function onTurnWasStarted(TurnWasStarted $event)
    {
        $players = $this->players->playersOfGame();
        foreach ($players as $playerId => $player) {
            // produce resources from planets
            foreach ($this->world->allColonizedPlanetsOfPlayer($playerId) as $planet) {
                $player->collectResourcesFromPlanet($planet);
            }

            $this->players->savePlayer(new PlayerId($playerId), $player);
        }
    }
}
