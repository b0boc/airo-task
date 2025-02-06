<?php

namespace Tests\Unit;

use App\Http\Controllers\QuotationsController;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class AgeLoadTest extends TestCase
{
    /** @test */
    public function it_returns_correct_age_load()
    {
        $controller = new QuotationsController();

        // Using Reflection to test private method
        $method = new ReflectionMethod(QuotationsController::class, 'getAgeLoad');
        $method->setAccessible(true);

        // 18-30 should be 0.6
        $this->assertEquals(0.6, $method->invoke($controller, 18));
        $this->assertEquals(0.6, $method->invoke($controller, 25));
        // 67 should be 1
        $this->assertEquals(1, $method->invoke($controller, 67));
    }
}
