<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\Identity\Identity;
use Webmozart\Assert\Assert;

final class ActionName implements Identity
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::notEmpty($name);
        $this->value = $name;
    }

    /**
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass(): string
    {
        return UserAction::class;
    }

    /**
     * Returns the string value of the identity.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }
}
