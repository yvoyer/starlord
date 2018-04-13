<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

use StarLord\Domain\Model\PlayerId;

final class CardWasPlayed implements StarLordEvent
{
    /**
     * @var int
     */
    private $cardId;

    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @param int $cardId
     * @param PlayerId $playerId
     */
    public function __construct(int $cardId, PlayerId $playerId)
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
     * @return PlayerId
     */
    public function playerId(): PlayerId
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
            'player_id' => $this->playerId->toString(),
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
