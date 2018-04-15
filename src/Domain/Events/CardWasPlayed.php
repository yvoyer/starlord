<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

final class CardWasPlayed implements StarLordEvent
{
    /**
     * @var int
     */
    private $cardId;

    /**
     * @var int
     */
    private $playerId;

    /**
     * @param int $cardId
     * @param int $playerId
     */
    public function __construct(int $cardId, int $playerId)
    {
        $this->cardId = $cardId;
        $this->playerId = $playerId;
    }

    /**
     * @return int
     */
    public function cardId(): int
    {
        return $this->cardId;
    }

    /**
     * @return int
     */
    public function playerId(): int
    {
        return $this->playerId;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return json_encode([
            'name' => 'card_was_played',
            'player_id' => $this->playerId(),
            'card_id' => $this->cardId(),
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
