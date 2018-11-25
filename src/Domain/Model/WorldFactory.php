<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface WorldFactory
{
    public function createWorld(WorldBuilder $builder): World;
}
