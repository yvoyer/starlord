<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

final class UnloadColonHandler
{
    public function __invoke(UnloadColon $command)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
