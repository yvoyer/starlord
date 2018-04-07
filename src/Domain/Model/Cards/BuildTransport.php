<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Cost;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\WriteOnlyPlayer;

final class BuildTransport extends Spaceship
{
    /**
     * Performed after the card is paid.
     *
     * @param int $quantity
     * @param WriteOnlyPlayer $player
     */
    protected function onPlay(int $quantity, WriteOnlyPlayer $player)
    {
        $player->addTransports($quantity);
    }

    /**
     * @return Cost
     */
    protected function getCost(): Cost
    {
        return new Credit(2);
    }
}
