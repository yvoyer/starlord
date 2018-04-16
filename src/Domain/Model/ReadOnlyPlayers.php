<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface ReadOnlyPlayers
{
    /**
     * @return bool
     */
    public function allPlayersOfGameHavePlayed(): bool;
}
