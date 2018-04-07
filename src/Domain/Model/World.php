<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

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
}
