<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Webmozart\Assert\Assert;

final class MultipleCost implements Cost
{
    /**
     * @var Cost[]
     */
    private $costs = [];

    /**
     * @param Cost[] $costs
     */
    public function __construct(array $costs)
    {
        Assert::allIsInstanceOf($costs, Cost::class);
        $this->costs = $costs;
    }

    /**
     * @return Credit
     */
    public function credit(): Credit
    {
        $total = new Credit();
        foreach ($this->costs as $cost) {
            $total = $total->add($cost->credit());
        }

        return $total;
    }

    /**
     * @return Deuterium
     */
    public function deuterium(): Deuterium
    {
        $total = new Deuterium();
        foreach ($this->costs as $cost) {
            $total = $total->add($cost->deuterium());
        }

        return $total;
    }
}
