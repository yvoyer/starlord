<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\UserActionStore;
use StarLord\Domain\Model\WriteOnlyPlayer;

final class ColonizePlanet implements Card
{
    /**
     * @var Credit
     */
    private $cost;

    /**
     * @param Credit $cost
     */
    public function __construct(Credit $cost)
    {
        $this->cost = $cost;
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function whenPlayedBy(WriteOnlyPlayer $player)
    {
        $player->pay($this->cost);
        $player->startAction([UserActionStore::MOVE_SHIP]);
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function whenDraw(WriteOnlyPlayer $player)
    {
    }
}
