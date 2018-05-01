<?php declare(strict_types=1);

namespace StarLord;

use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\LogicalAnd;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\TestPlayer;

final class PlayerAssertion
{
    /**
     * @var Constraint[]
     */
    private $constraints = [];

    private function __construct(PlayerId $playerId)
    {
        $this->constraints[] = new IsEqual($playerId);
    }

    /**
     * @return Constraint
     */
    public function build(): Constraint
    {
        $constraint = new LogicalAnd();
        $constraint->setConstraints($this->constraints);

        return $constraint;
    }

    /**
     * @param int $expected
     *
     * @return PlayerAssertion
     */
    public function hasPopulation(int $expected): self
    {
        $this->constraints[] = new Callback(
            function (TestPlayer $player) use ($expected) {
                return $player->getPopulation()->toInt() === $expected;
            }
        );

        return $this;
    }

    /**
     * @param int $expected
     *
     * @return PlayerAssertion
     */
    public function hasCredit(int $expected): self
    {
        $this->constraints[] = new Callback(
            function (TestPlayer $player) use ($expected) {
                return $player->getCredit()->toInt() === $expected;
            }
        );

        return $this;
    }

    /**
     * @param PlayerId $playerId
     *
     * @return PlayerAssertion
     */
    public static function create(PlayerId $playerId): self
    {
        return new self($playerId);
    }
}
