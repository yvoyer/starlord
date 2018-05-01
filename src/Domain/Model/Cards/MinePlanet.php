<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\InProgressGame;
use StarLord\Domain\Model\UserActionStore;
use StarLord\Domain\Model\WriteOnlyPlayer;
use Webmozart\Assert\Assert;

/**
 * Pay 5 credit
 * Place a small crystal of the planet color on planet of choice
 */
final class MinePlanet implements Card
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
     * @param WriteOnlyPlayer $player
     */
    public function whenPlayedBy(WriteOnlyPlayer $player)
    {
        // todo make cost configurable
        $player->pay(new Credit(5));
        // todo should add actions based on quantity
        $player->startAction(new InProgressGame(), [UserActionStore::MINE_PLANET]);
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function whenDraw(WriteOnlyPlayer $player)
    {
        // nothing
    }
}
