<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Webmozart\Assert\Assert;

final class Armada
{
    /**
     * @var int
     */
    private $transports;

    /**
     * @var int
     */
    private $fighters;

    /**
     * @var int
     */
    private $cruisers;

    /**
     * @param int $transports
     * @param int $fighters
     * @param int $cruisers
     */
    public function __construct(int $transports = 0, int $fighters = 0, int $cruisers = 0)
    {
        Assert::greaterThanEq($transports, 0);
        Assert::greaterThanEq($fighters, 0);
        Assert::greaterThanEq($cruisers, 0);
        $this->transports = $transports;
        $this->fighters = $fighters;
        $this->cruisers = $cruisers;
    }

    /**
     * @return int
     */
    public function transports(): int
    {
        return $this->transports;
    }

    /**
     * @return int
     */
    public function fighters(): int
    {
        return $this->fighters;
    }

    /**
     * @return int
     */
    public function cruisers(): int
    {
        return $this->cruisers;
    }

    /**
     * @param int $number
     *
     * @return Armada
     */
    public function addTransports(int $number): Armada
    {
        return new self(
            $this->transports + $number,
            $this->fighters,
            $this->cruisers
        );
    }

    /**
     * @param int $number
     *
     * @return Armada
     */
    public function addFighters(int $number): Armada
    {
        return new self(
            $this->transports,
            $this->fighters + $number,
            $this->cruisers
        );
    }

    /**
     * @param int $number
     *
     * @return Armada
     */
    public function addCruisers(int $number): Armada
    {
        return new self(
            $this->transports,
            $this->fighters,
            $this->cruisers + $number
        );
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return "{$this->transports} (Transport); {$this->fighters} (Fighter); {$this->cruisers} (Cruiser)";
    }
}
