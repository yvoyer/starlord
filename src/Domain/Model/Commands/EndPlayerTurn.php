<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\PlayerId;

final class EndPlayerTurn
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
}
