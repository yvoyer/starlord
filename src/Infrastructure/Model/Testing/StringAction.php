<?php declare(strict_types=1);

namespace StarLord\Infrastructure\Model\Testing;

use StarLord\Domain\Model\UserAction;

final class StringAction implements UserAction
{
    /**
     * @var string
     */
    private $action;

    /**
     * @param string $action
     */
    public function __construct(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function actionName(): string
    {
        return $this->action;
    }
}
