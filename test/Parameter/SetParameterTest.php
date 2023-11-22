<?php declare(strict_types=1);

namespace Swiftly\Database\Test\Parameter;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\Parameter\SetParameter;

/**
 * @covers \Swiftly\Database\Parameter\SetParameter
 * @covers \Swiftly\Database\AbstractParameter
 */
class SetParameterTest extends TestCase
{
    private SetParameter $parameter;

    public function setUp(): void
    {
        $this->parameter = new SetParameter('ids', [1, 2, 3]);
    }

    public function testPropertiesArePubliclyAccessible(): void
    {
        self::assertSame('ids', $this->parameter->name);
        self::assertSame([1, 2, 3], $this->parameter->value);
    }

    public function testWillCastScalarValuesToArray(): void
    {
        $parameter = new SetParameter('ids', 42);

        self::assertSame([42], $parameter->value);
    }

    public function testWillStripArrayKeys(): void
    {
        $parameter = new SetParameter('users', ['John' => 1, 'Jill' => 2]);

        self::assertSame([1, 2], $parameter->value);
    }
}