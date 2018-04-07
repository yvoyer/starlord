<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Webmozart\Assert\Assert;

final class Population
{
    /**
     * @var int
     */
    private $quantity;

    /**
     * @param int $quantity
     */
    public function __construct(int $quantity = 0)
    {
        Assert::greaterThanEq($quantity, 0);
        $this->quantity = $quantity;
    }

    /**
     * @param int $quantity
     *
     * @return Population
     */
    public function addColons(int $quantity): Population
    {
        return new self($this->quantity + $quantity);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'Population: ' . $this->quantity;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->quantity;
    }
}
