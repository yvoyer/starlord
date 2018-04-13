<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\WriteOnlyPlayer;

final class NotFoundCard implements Card
{
    /**
     * @var int
     */
    private $cardId;

    /**
     * @param int $cardId
     */
    public function __construct(int $cardId)
    {
        $this->cardId = $cardId;
    }

    /**
     * @param PlayerId $playerId
     * @param WriteOnlyPlayer $player
     */
    public function play(PlayerId $playerId, WriteOnlyPlayer $player)
    {
        throw new \LogicException(
            sprintf(
                'Card with id "%s" is not in hand of player "%s".',
                $this->cardId,
                $playerId
            )
        );
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function draw(WriteOnlyPlayer $player)
    {
        throw new \LogicException(
            sprintf(
                'Card with id "%s" was not found in deck.',
                $this->cardId
            )
        );
    }
}
