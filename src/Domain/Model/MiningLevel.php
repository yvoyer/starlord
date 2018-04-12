<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Webmozart\Assert\Assert;

final class MiningLevel
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
     * @param int $level
     *
     * @return MiningLevel
     */
    public function increase(int $level): MiningLevel
    {
        return new self($this->score + $level);
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->score;
    }
}
