<?php declare(strict_types=1);

namespace Swiftly\Database\Test\Parameter;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\Parameter\IntegerParameter;

/**
 * @covers \Swiftly\Database\Parameter\IntegerParameter
 * @covers \Swiftly\Database\AbstractParameter
 */
class IntegerParameterTest extends TestCase
{
    private IntegerParameter $parameter;

    public function setUp(): void
    {
        $this->parameter = new IntegerParameter('answer', 42);
    }

    public function testPropertiesArePubliclyAccessible(): void
    {
        self::assertSame('answer', $this->parameter->name);
        self::assertSame(42, $this->parameter->value);
    }
}