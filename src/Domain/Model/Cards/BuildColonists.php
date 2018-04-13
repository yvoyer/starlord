<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\WriteOnlyPlayer;
use Webmozart\Assert\Assert;

final class BuildColonists implements Card
{
    /**
     * @var int
     */
    private $quantity;

    /**
     * @param int $quantity
     */
    public function __construct(int $quantity)
    {
        Assert::greaterThan($quantity, 0);
        $this->quantity = $quantity;
    }

    /**
     * @param PlayerId $playerId
     * @param WriteOnlyPlayer $player
     */
    public function play(PlayerId $playerId, WriteOnlyPlayer $player)
    {
        $player->pay(new Credit(2));
        $player->addColons($this->quantity);
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function draw(WriteOnlyPlayer $player)
    {
        // nothing
    }
}
