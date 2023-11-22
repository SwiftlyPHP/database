<?php declare(strict_types=1);

namespace Swiftly\Database\Test\Parameter;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\Parameter\BooleanParameter;

/**
 * @covers \Swiftly\Database\Parameter\BooleanParameter
 * @covers \Swiftly\Database\AbstractParameter
 */
class BooleanParameterTest extends TestCase
{
    private BooleanParameter $parameter;

    public function setUp(): void
    {
        $this->parameter = new BooleanParameter('verbose', true);
    }

    public function testPropertiesArePubliclyAccessible(): void
    {
        self::assertSame('verbose', $this->parameter->name);
        self::assertSame(true, $this->parameter->value);
    }
}