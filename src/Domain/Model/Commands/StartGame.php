<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use Webmozart\Assert\Assert;

final class StartGame
{
    /**
     * @var int[]
     */
    private $players;

    /**
     * @param int[] $players
     */
    public function __construct(array $players)
    {
        Assert::allInteger($players);
        $this->players = $players;
    }

    /**
     * @return int[]
     */
    public function players(): array
    {
        return $this->players;
    }
}
