<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Model\Bonus\Crystal;

/**
 * The crystal information owned by a player
 */
final class Stash
{
    /**
     * Number of crystal foreach types and sizes
     *
     * @var int[][]
     */
    private $crystals = [];

    /**
     * @param array $crystals
     */
    private function __construct(array $crystals)
    {
        $this->crystals = $crystals;
    }

    /**
     * @param Crystal $crystal
     *
     * @return Stash
     */
    public function addCrystal(Crystal $crystal): self
    {
        $collection = $this->crystals;
        if (! isset($collection[$crystal->color()][$crystal->size()])) {
            $collection[$crystal->color()][$crystal->size()] = 0;
        }

        $collection[$crystal->color()][$crystal->size()] ++;

        return new self($collection);
    }

    /**
     * @param string $color
     *
     * @return int
     */
    public function ofColor(string $color): int
    {
        if (! isset($this->crystals[$color])) {
            return 0;
        }

        $count = 0;
        foreach ($this->crystals[$color] as $colorQuantity) {
            $count += $colorQuantity;
        }

        return $count;
    }

    /**
     * @param string $size
     *
     * @return int
     */
    public function ofSize(string $size): int
    {
        $count = 0;
        foreach ($this->crystals as $color => $crystals) {
            if (! isset($crystals[$size])) {
                continue;
            }

            $count += $crystals[$size];
        }

        return $count;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return json_encode($this->crystals);
    }

    /**
     * @return Crystal[]
     */
    public function crystals(): array
    {
        $crystals = [];
        foreach ($this->crystals as $color => $sizes) {
            foreach ($sizes as $size => $quantity) {
                $crystals = array_merge(
                    $crystals,
                    array_fill(0, $quantity, Crystal::fromString($color, $size))
                );
            }
        }

        return $crystals;
    }

    /**
     * @return Stash
     */
    public static function emptyStash(): Stash
    {
        return new self([]);
    }
}
