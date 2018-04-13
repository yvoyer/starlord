<?php declare(strict_types=1);

namespace StarLord\Infrastructure\Persistence\InMemory;

use Star\Component\Identity\Exception\EntityNotFoundException;
use StarLord\Domain\Model\ActionName;
use StarLord\Domain\Model\GameActions;
use StarLord\Domain\Model\UserAction;
use Webmozart\Assert\Assert;

final class ActionRegistry implements GameActions
{
    /**
     * @var UserAction[]
     */
    private $actions = [];

    /**
     * @param UserAction[] $actions
     */
    public function __construct(array $actions)
    {
        Assert::allIsInstanceOf($actions, UserAction::class);
        array_map(
            function (UserAction $action) {
                $this->actions[$action->name()->toString()] = $action;
            },
            $actions
        );
    }

    /**
     * @param ActionName $name
     *
     * @return UserAction
     * @throws EntityNotFoundException
     */
    public function getAction(ActionName $name): UserAction
    {
        if (! array_key_exists($name->toString(), $this->actions)) {
            throw EntityNotFoundException::objectWithIdentity($name);
        }

        return $this->actions[$name->toString()];
    }
}
