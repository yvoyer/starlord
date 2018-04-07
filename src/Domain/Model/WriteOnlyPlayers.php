<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface WriteOnlyPlayers
{
    /**
     * @param int $id
     * @param WriteOnlyPlayer $player
     */
    public function savePlayer(int $id, WriteOnlyPlayer $player);

    /**
     * @param int $id
     *
     * @return WriteOnlyPlayer
     */
    public function getPlayerWithId(int $id): WriteOnlyPlayer;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function playerExists(int $id): bool;

    /**
     * todo inject game id
     * @return WriteOnlyPlayer[]
     */
    public function playersOfGame(): array;
}
