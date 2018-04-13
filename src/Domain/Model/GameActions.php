<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\Identity\Exception\EntityNotFoundException;

interface GameActions
{
    /**
     * @param ActionName $name
     *
     * @return UserAction
     * @throws EntityNotFoundException
     */
    public function getAction(ActionName $name): UserAction;
}
