<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Assert\Assertion;

final class Colons
{
    /**
     * @var int
     */
    private $quantity;

    /**
     * @param int $quantity
     */
    public function __construct(int $quantity)
    {
        Assertion::greaterOrEqualThan($quantity, 0);
        $this->quantity = $quantity;
    }

    /**
     * @param int $quantity
     *
     * @return bool
     */
    public function lowerThan(int $quantity): bool
    {
        return $this->quantity < $quantity;
    }

    /**
     * @param int $quantity
     *
     * @return Colons
     */
    public function addColons(int $quantity): Colons
    {
        return new self($this->quantity + $quantity);
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->quantity;
    }
}
