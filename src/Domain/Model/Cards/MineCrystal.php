<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Bonus\Crystal;
use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\WriteOnlyPlayer;
use Webmozart\Assert\Assert;

final class MineCrystal implements Card
{
    /**
     * @var int
     */
    private $quantity;

    /**
     * @var Crystal
     */
    private $crystal;

    /**
     * @param int $quantity
     * @param Crystal $crystal
     */
    public function __construct(int $quantity, Crystal $crystal)
    {
        Assert::greaterThan($quantity, 0);
        $this->quantity = $quantity;
        $this->crystal = $crystal;
    }

    /**
     * @param PlayerId $playerId
     * @param WriteOnlyPlayer $player
     */
    public function play(PlayerId $playerId, WriteOnlyPlayer $player)
    {
        $player->pay(new Credit(5));
        $player->mineCrystal($this->crystal);
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function draw(WriteOnlyPlayer $player)
    {
        // nothing
    }

    /**
     * @param int $quantity
     * @param string $color
     * @param string $size
     *
     * @return MineCrystal
     */
    public static function fromColor(int $quantity, string $color, string $size): self
    {
        return new self($quantity, Crystal::fromString($color, $size));
    }
}
