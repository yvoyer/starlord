<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

final class UnloadColonsHandler
{
    /**
     * @param UnloadColons $command
     */
    public function __invoke(UnloadColons $command)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
