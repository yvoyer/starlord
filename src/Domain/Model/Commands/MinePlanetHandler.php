<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\PlanetWasMined;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\World;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class MinePlanetHandler
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var World
     */
    private $world;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param WriteOnlyPlayers $players
     * @param World $world
     * @param Publisher $publisher
     */
    public function __construct(WriteOnlyPlayers $players, World $world, Publisher $publisher)
    {
        $this->players = $players;
        $this->world = $world;
        $this->publisher = $publisher;
    }

    /**
     * @param MinePlanet $command
     */
    public function __invoke(MinePlanet $command)
    {
        $playerId = $command->playerId();
        $planetId = $command->planetId();
        $planet = $this->world->planetWithId($planetId);
        $planet->mine($playerId);
        $player = $this->players->getPlayerWithId($playerId);
        $player->performAction($command);

        $this->world->savePlanet($planetId, $planet);
        $this->publisher->publish(new PlanetWasMined($playerId, $planetId));
    }
}
