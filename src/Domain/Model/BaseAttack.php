<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Webmozart\Assert\Assert;

final class BaseAttack
{
    /**
     * @var int
     */
    private $score;

    /**
     * @param int $level
     */
    public function __construct(int $level = 0)
    {
        Assert::greaterThanEq($level, 0);
        $this->score = $level;
    }

    /**
     * @param int $bonus
     *
     * @return BaseAttack
     */
    public function addBonus(int $bonus): BaseAttack
    {
        return new self($this->score + $bonus);
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->score;
    }
}
