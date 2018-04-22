<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\HomeWorldWasSelected;
use StarLord\Domain\Events\PlanetWasColonized;
use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\World;

final class ColonizePlanetHandler
{
    /**
     * @var World
     */
    private $planets;

    /**
     * @var int
     */
    private $startingColons;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param World $planets
     * @param int $startingColons
     * @param Publisher $publisher
     */
    public function __construct(World $planets, int $startingColons, Publisher $publisher)
    {
        $this->planets = $planets;
        $this->startingColons = new Colons($startingColons);
        $this->publisher = $publisher;
    }

    public function __invoke(ColonizePlanet $command)
    {
        $planetId = $command->planetId();
        $playerId = $command->playerId();
        $colons = $command->colons();

        $planet = $this->planets->planetWithId($planetId);
        $planet->colonize($playerId, $colons);

        $this->planets->savePlanet($planetId, $planet);
        $this->publisher->publish(new PlanetWasColonized($playerId, $planetId, $colons));
    }

    public function onHomeWorldWasSelected(HomeWorldWasSelected $event)
    {
        $this->__invoke(new ColonizePlanet($event->playerId(), $event->planetId(), $this->startingColons));
    }
}
