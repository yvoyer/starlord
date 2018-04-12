<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Events\StarLordEvent;

interface Publisher
{
    /**
     * @param StarLordEvent $event
     */
    public function publish(StarLordEvent $event);
}
