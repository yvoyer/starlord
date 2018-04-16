<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\PlayerId;
use Webmozart\Assert\Assert;

final class StartGame
{
    /**
     * @var PlayerId[]
     */
    private $players;

    /**
     * @param PlayerId[] $players
     */
    public function __construct(array $players)
    {
        Assert::allIsInstanceOf($players, PlayerId::class);
        $this->players = $players;
    }

    /**
     * @return PlayerId[]
     */
    public function players(): array
    {
        return $this->players;
    }
}
