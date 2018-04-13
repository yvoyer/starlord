<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\Identity\Identity;

final class ShipId implements Identity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass(): string
    {
        return WriteOnlyShip::class;
    }

    /**
     * Returns the string value of the identity.
     *
     * @return string
     */
    public function toString(): string
    {
        return (string) $this->id;
    }
}
