<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface Card
{
    /**
     * @param WriteOnlyPlayer $player // todo Replace with CardPlayer
     */
    public function whenPlayedBy(WriteOnlyPlayer $player);

    /**
     * @param WriteOnlyPlayer $player // todo replace with CardPlayer
     */
    public function whenDraw(WriteOnlyPlayer $player);
}
