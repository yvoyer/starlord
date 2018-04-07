<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Webmozart\Assert\Assert;

final class Deuterium implements Cost
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
     * @param Deuterium $deuterium
     *
     * @return Deuterium
     */
    public function add(Deuterium $deuterium): Deuterium
    {
        return new self($this->quantity + $deuterium->toInt());
    }

    /**
     * @param Deuterium $deuterium
     *
     * @return Deuterium
     */
    public function remove(Deuterium $deuterium): Deuterium
    {
        return new self($this->quantity - $deuterium->toInt());
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->quantity . 'DEU';
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->quantity;
    }

    /**
     * @return Credit
     */
    public function credit(): Credit
    {
        return new Credit();
    }

    /**
     * @return Deuterium
     */
    public function deuterium(): Deuterium
    {
        return $this;
    }
}
