<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface Card
{
    /**
     * @param int $playerId
     * @param WriteOnlyPlayer $player
     */
    public function play(int $playerId, WriteOnlyPlayer $player);

    /**
     * @param WriteOnlyPlayer $player
     */
    public function draw(WriteOnlyPlayer $player);
}
