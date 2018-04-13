<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface Card
{
    /**
     * @param PlayerId $playerId
     * @param WriteOnlyPlayer $player
     */
    public function play(PlayerId $playerId, WriteOnlyPlayer $player);

    /**
     * @param WriteOnlyPlayer $player
     */
    public function draw(WriteOnlyPlayer $player);
}
