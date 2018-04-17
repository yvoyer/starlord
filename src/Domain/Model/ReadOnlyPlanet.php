<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Model\Exception\InvalidUsageException;

interface ReadOnlyPlanet
{
    /**
     * @return Colons
     */
    public function population(): Colons;

    /**
     * @return PlayerId
     * @throws InvalidUsageException
     */
    public function ownerId(): PlayerId;
}
