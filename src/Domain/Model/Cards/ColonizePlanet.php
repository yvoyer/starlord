<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\PlayerId;
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
     * @param PlayerId $playerId
     * @param WriteOnlyPlayer $player
     */
    public function play(PlayerId $playerId, WriteOnlyPlayer $player)
    {
        // pay credit
        $player->pay($this->cost);
        $player->startAction([UserActionStore::moveShip()]);
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function draw(WriteOnlyPlayer $player)
    {
    }
}
