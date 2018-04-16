<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface WriteOnlyPlayers extends ReadOnlyPlayers
{
    /**
     * @param PlayerId $id
     * @param WriteOnlyPlayer $player
     */
    public function savePlayer(PlayerId $id, WriteOnlyPlayer $player);

    /**
     * @param PlayerId $id
     *
     * @return WriteOnlyPlayer
     */
    public function getPlayerWithId(PlayerId $id): WriteOnlyPlayer;

    /**
     * @param PlayerId $id
     *
     * @return bool
     */
    public function playerExists(PlayerId $id): bool;

    /**
     * todo inject game id
     * @return WriteOnlyPlayer[]
     */
    public function playersOfGame(): array;
}
