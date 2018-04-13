<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

use StarLord\Domain\Model\PlayerId;

final class PlayerJoinedGame implements StarLordEvent
{
    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @param PlayerId $playerId
     */
    public function __construct(PlayerId $playerId)
    {
        $this->playerId = $playerId;
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
            'name' => 'player_joined_game',
            'player' => $this->playerId->toString(),
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
