<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\PlayerId;

final class PlayCard
{
    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var int
     */
    private $cardId;

    /**
     * @param PlayerId $playerId
     * @param int $cardId
     */
    public function __construct(PlayerId $playerId, int $cardId)
    {
        $this->playerId = $playerId;
        $this->cardId = $cardId;
    }

    /**
     * @return PlayerId
     */
    public function playerId(): PlayerId
    {
        return $this->playerId;
    }

    /**
     * @return int
     */
    public function cardId(): int
    {
        return $this->cardId;
    }
}
