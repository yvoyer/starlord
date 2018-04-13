<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Actions;

use StarLord\Domain\Model\ActionName;
use StarLord\Domain\Model\UserAction;
use StarLord\Domain\Model\WriteOnlyPlayer;

final class TestAction implements UserAction
{
    /**
     * @var ActionName
     */
    private $name;

    /**
     * @var WriteOnlyPlayer|null
     */
    private $performedBy;

    /**
     * @param ActionName $name
     */
    public function __construct(ActionName $name)
    {
        $this->name = $name;
    }

    /**
     * @return ActionName
     */
    public function name(): ActionName
    {
        return $this->name;
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function perform(WriteOnlyPlayer $player)
    {
        $this->performedBy = $player;
    }

    public function wasPerformed(): bool
    {
        return $this->performedBy instanceof WriteOnlyPlayer;
    }

    /**
     * @param string $name
     *
     * @return TestAction
     */
    public static function fromString(string $name): self
    {
        return new self(new ActionName($name));
    }
}
