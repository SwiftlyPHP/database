<?php declare(strict_types=1);

namespace Swiftly\Database\Test\Parameter;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\Parameter\StringParameter;

/**
 * @covers \Swiftly\Database\Parameter\StringParameter
 * @covers \Swiftly\Database\AbstractParameter
 */
class StringParameterTest extends TestCase
{
    private StringParameter $parameter;

    public function setUp(): void
    {
        $this->parameter = new StringParameter('name', 'John');
    }

    public function testPropertiesArePubliclyAccessible(): void
    {
        self::assertSame('name', $this->parameter->name);
        self::assertSame('John', $this->parameter->value);
    }
}