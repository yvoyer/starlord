<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

final class SkipAction implements UserAction
{
    /**
     * @return string
     */
    public function actionName(): string
    {
        return 'skip-action';
    }

    /**
     * Whether the action needs to be performed
     *
     * @return bool
     */
    public function requiresPerform(): bool
    {
        return false;
    }
}
