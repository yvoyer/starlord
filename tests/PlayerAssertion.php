<?php declare(strict_types=1);

namespace StarLord;

use PHPUnit\Framework\Assert;
use StarLord\Domain\Model\ReadOnlyPlayer;

final class PlayerAssertion
{
    /**
     * @var ReadOnlyPlayer
     */
    private $player;

    public function __construct(ReadOnlyPlayer $player)
    {
        $this->player = $player;
    }

    /**
     * @param int $expected
     *
     * @return PlayerAssertion
     */
    public function hasPopulation(int $expected): PlayerAssertion
    {
        Assert::assertSame(
            $expected,
            $this->player->getPopulation()->toInt(),
            sprintf('Population of player "%s" is not as expected.', $this->player->getIdentity()->toInt())
        );

        return $this;
    }

    /**
     * @param int $expected
     *
     * @return PlayerAssertion
     */
    public function hasCredit(int $expected): PlayerAssertion
    {
        Assert::assertSame(
            $expected,
            $this->player->getCredit()->toInt(),
            sprintf('Credit of player "%s" is not as expected.', $this->player->getIdentity()->toInt())
        );

        return $this;
    }
}
