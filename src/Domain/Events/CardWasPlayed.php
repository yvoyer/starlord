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
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            'name' => 'card_was_played',
            'player_id' => $this->playerId(),
            'card_id' => $this->cardId(),
        ]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
