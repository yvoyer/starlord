<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\TestCase;

final class UnloadColonsHandlerTest extends TestCase
{
    /**
     * @var UnloadColonsHandler
     */
    private $handler;

    public function setUp()
    {
        $this->handler = new UnloadColonsHandler();
    }

    public function test_it_should_do_something()
    {
        $this->fail('test someting');
    }
}
