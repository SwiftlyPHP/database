<?php declare(strict_types=1);

namespace Swiftly\Database\Test;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\Parameter;

/**
 * @covers \Swiftly\Database\Parameter
 */
class ParameterTest extends TestCase
{
    private Parameter $parameter;

    public function setUp(): void
    {
        $this->parameter = new Parameter('title', 'doctor');
    }

    public function testCanAccessParameterName(): void
    {
        self::assertSame('title', $this->parameter->name);
    }

    public function testCanAccessParameterValue(): void
    {
        self::assertSame('doctor', $this->parameter->value);
    }
}