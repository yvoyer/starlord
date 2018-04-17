<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\Identity\Exception\EntityNotFoundException;

interface World
{
    /**
     * @param int $playerId
     *
     * @return WriteOnlyPlanet[]
     */
    public function allColonizedPlanetsOfPlayer(int $playerId): array;

    /**
     * @return WriteOnlyPlanet[]
     */
    public function allPlanets(): array;

    /**
     * @param PlanetId $id
     *
     * @return WriteOnlyPlanet
     * @throws EntityNotFoundException
     */
    public function planetWithId(PlanetId $id): WriteOnlyPlanet;

    /**
     * @param PlanetId $id
     * @param WriteOnlyPlanet $planet
     */
    public function savePlanet(PlanetId $id, WriteOnlyPlanet $planet);
}
