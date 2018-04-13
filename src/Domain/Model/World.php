<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\Identity\Exception\EntityNotFoundException;

interface World
{
    /**
     * @param int $playerId
     *
     * @return Planet[]
     */
    public function allColonizedPlanetsOfPlayer(int $playerId): array;

    /**
     * @return Planet[]
     */
    public function allPlanets(): array;

    /**
     * @param PlanetId $id
     *
     * @return Planet
     * @throws EntityNotFoundException
     */
    public function planetWithId(PlanetId $id): Planet;
}
