<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Cost;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\WriteOnlyPlayer;

abstract class Spaceship implements Card
{
    /**
     * @var int
     */
    private $quantity;

    /**
     * @param int $quantity
     */
    final public function __construct(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @param PlayerId $playerId
     * @param WriteOnlyPlayer $player
     */
    final public function play(PlayerId $playerId, WriteOnlyPlayer $player)
    {
        $player->pay($this->getCost());
        $this->onPlay($this->quantity, $player);
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    final public function draw(WriteOnlyPlayer $player)
    {
        // do nothing
    }

    /**
     * Performed after the card is paid.
     *
     * @param int $quantity
     * @param WriteOnlyPlayer $player
     */
    abstract protected function onPlay(int $quantity, WriteOnlyPlayer $player);

    /**
     * @return Cost
     */
    abstract protected function getCost(): Cost;

    /**
     * @param int $quantity
     *
     * @return static
     */
    public static function fromInt(int $quantity): self
    {
        return new static($quantity);
    }
}
