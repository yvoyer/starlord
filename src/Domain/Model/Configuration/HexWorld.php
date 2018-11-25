<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Configuration;

use StarLord\Domain\Model\World;
use StarLord\Domain\Model\WorldBuilder;
use StarLord\Domain\Model\WorldFactory;

final class HexWorld implements WorldFactory
{
    /**
     * @param WorldBuilder $builder
     *
     * @return World
     */
    public function createWorld(WorldBuilder $builder): World
    {
        $rows = 7;
        $columns = 5;
        for ($y = 0; $y < $rows; $y ++) {
            if ($y % 2 == 0) {
                $columns = $columns - 1;
            }

            for ($x = 0; $x < $columns; $x ++) {
                $builder->createPlanetSlot($x, $y);
            }
        }

        return $builder->createWorld();
    }
}
