<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

final class PlayCard
{
    /**
     * @var int
     */
    private $playerId;

    /**
     * @var int
     */
    private $cardId;

    /**
     * @param int $playerId
     * @param int $cardId
     */
    public function __construct(int $playerId, int $cardId)
    {
        $this->playerId = $playerId;
        $this->cardId = $cardId;
    }

    /**
     * @return int
     */
    public function playerId(): int
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
