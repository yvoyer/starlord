<?php declare(strict_types=1);

namespace StarLord;

use StarLord\Domain\Model\ReadOnlyPlayer;

final class AssertThat
{
    /**
     * @param ReadOnlyPlayer $player
     *
     * @return PlayerAssertion
     */
    public static function player(ReadOnlyPlayer $player): PlayerAssertion
    {
        return new PlayerAssertion($player);
    }
}
