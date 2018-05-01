<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

/**
 * User action that uses Command pattern
 */
interface UserAction
{
    /**
     * @return string
     */
    public function actionName(): string;

    /**
     * Whether the action needs to be performed
     *
     * @return bool
     */
    public function requiresPerform(): bool;
}
