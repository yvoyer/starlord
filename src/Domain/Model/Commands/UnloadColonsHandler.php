<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\ColonsWereUnloaded;
use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\PlayerArmada;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\World;

final class UnloadColonsHandler
{
    /**
     * @var World
     */
    private $world;

    /**
     * @var PlayerArmada
     */
    private $armada;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param World $world
     * @param PlayerArmada $armada
     * @param Publisher $publisher
     */
    public function __construct(
        World $world,
        PlayerArmada $armada,
        Publisher $publisher
    ) {
        $this->world = $world;
        $this->armada = $armada;
        $this->publisher = $publisher;
    }

    /**
     * @param UnloadColons $command
     */
    public function __invoke(UnloadColons $command)
    {
        $playerId = $command->playerId();
        $shipId = $command->shipId();
        $quantity = new Colons($command->quantity());

        $ship = $this->armada->shipWithId($shipId, $playerId);
        $planetId = $ship->locationId();

        $destination = $this->world->planetWithId($planetId);
        $ship->unloadColons($quantity);
        $destination->colonize($playerId, $quantity);

        $this->world->savePlanet($planetId, $destination);
        $this->armada->saveShip($ship);

        $this->publisher->publish(
            new ColonsWereUnloaded(
                $playerId,
                $planetId,
                $quantity,
                $shipId
            )
        );
    }
}
