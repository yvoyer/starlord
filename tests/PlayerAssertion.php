<?php declare(strict_types=1);

namespace StarLord;

use PHPUnit\Framework\Assert;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ReadOnlyPlayer;

final class PlayerAssertion
{
    /**
     * @var ReadOnlyPlayer
     */
    private $player;

    /**
     * @var PlayerId
     */
    private $playerId;

    public function __construct(ReadOnlyPlayer $player)
    {
        $this->player = $player;
        $this->playerId = $player->getIdentity();
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
            sprintf('Population of player "%s" is not as expected.', $this->playerId->toInt())
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
            sprintf('Credit of player "%s" is not as expected.', $this->playerId->toInt())
        );

        return $this;
    }

    /**
     * @param int $expected
     *
     * @return PlayerAssertion
     */
    public function hasDeuterium(int $expected): PlayerAssertion
    {
        Assert::assertSame(
            $expected,
            $this->player->getDeuterium()->toInt(),
            sprintf('Deuterium of player "%s" is not as expected.', $this->playerId->toInt())
        );

        return $this;
    }

    /**
     * @param int $expected
     *
     * @return PlayerAssertion
     */
    public function hasCrystalCount(int $expected): PlayerAssertion
    {
        Assert::assertCount(
            $expected,
            $this->player->getHoard()->crystals(),
            sprintf('Crystal count of player "%s" is not as expected.', $this->playerId->toInt())
        );

        return $this;
    }

    /**
     * @param int $expected
     *
     * @return PlayerAssertion
     */
    public function hasTransportCount(int $expected): PlayerAssertion
    {
        Assert::assertSame(
            $expected,
            $this->player->getArmada()->transports(),
            sprintf('Transport count of player "%s" is not as expected.', $this->playerId->toInt())
        );

        return $this;
    }

    /**
     * @param int $expected
     *
     * @return PlayerAssertion
     */
    public function hasFighterCount(int $expected): PlayerAssertion
    {
        Assert::assertSame(
            $expected,
            $this->player->getArmada()->fighters(),
            sprintf('Fighter count of player "%s" is not as expected.', $this->playerId->toInt())
        );

        return $this;
    }

    /**
     * @param int $expected
     *
     * @return PlayerAssertion
     */
    public function hasCruiserCount(int $expected): PlayerAssertion
    {
        Assert::assertSame(
            $expected,
            $this->player->getArmada()->cruisers(),
            sprintf('Cruiser count of player "%s" is not as expected.', $this->playerId->toInt())
        );

        return $this;
    }

    /**
     * @param int[] $expected
     *
     * @return PlayerAssertion
     */
    public function hasCardsInHand(array $expected): PlayerAssertion
    {
        Assert::assertEquals(
            $expected,
            $this->player->cards(),
            sprintf('Cards in hand of player "%s" is not as expected.', $this->playerId->toInt())
        );

        return $this;
    }
}
