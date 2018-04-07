<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Webmozart\Assert\Assert;

final class Credit implements Cost
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
     * @param Credit $credit
     *
     * @return Credit
     */
    public function add(Credit $credit): Credit
    {
        return new Credit($this->quantity + $credit->toInt());
    }

    /**
     * @param Credit $credit
     *
     * @return Credit
     */
    public function subtract(Credit $credit): Credit
    {
        return new Credit($this->quantity - $credit->toInt());
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->quantity . 'CRD';
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
        return $this;
    }

    /**
     * @return Deuterium
     */
    public function deuterium(): Deuterium
    {
        return new Deuterium();
    }
}
