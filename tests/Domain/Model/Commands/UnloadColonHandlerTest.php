<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\TestCase;

final class UnloadColonHandlerTest extends TestCase
{
    /**
     * @var UnloadColonHandler
     */
    private $handler;

    public function setUp()
    {
        $this->handler = new UnloadColonHandler();
    }

    public function test_it_should_do_something()
    {
        $this->fail('test someting');
    }
}
