<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\Identity\Exception\EntityNotFoundException;

interface ReadOnlyPlayers
{
    /**
     * @return bool
     */
    public function allPlayersOfGameHavePlayed(): bool;

    /**
     * @param PlayerId $id
     *
     * @return Colons
     * @throws EntityNotFoundException
     */
    public function availableColons(PlayerId $id): Colons;
}
