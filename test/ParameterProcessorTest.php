<?php declare(strict_types=1);

namespace Swiftly\Database\Test;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\ParameterProcessor;
use Swiftly\Database\Parameter\BooleanParameter;
use Swiftly\Database\Parameter\FloatParameter;
use Swiftly\Database\Parameter\IntegerParameter;
use Swiftly\Database\Parameter\SetParameter;
use Swiftly\Database\Parameter\StringParameter;
use Swiftly\Database\Exception\ParameterException;

/**
 * @covers \Swiftly\Database\ParameterProcessor
 * @uses \Swiftly\Database\AbstractParameter
 * @uses \Swiftly\Database\Parameter\BooleanParameter
 * @uses \Swiftly\Database\Parameter\FloatParameter
 * @uses \Swiftly\Database\Parameter\IntegerParameter
 * @uses \Swiftly\Database\Parameter\SetParameter
 * @uses \Swiftly\Database\Parameter\StringParameter
 */
class ParameterProcessorTest extends TestCase
{
    public function provideExampleParameters(): array
    {
        return [
            'boolean (true)' => ['verbose', true, BooleanParameter::class],
            'boolean (false)' => ['verbose', false, BooleanParameter::class],
            'float' => ['pi', 3.14, FloatParameter::class],
            'int' => ['age', 42, IntegerParameter::class],
            'array' => ['ids', [1, 2, 3], SetParameter::class],
            'string' => ['name', 'John', StringParameter::class],
            'numeric string (int)' => ['year', '2023', IntegerParameter::class, 2023],
            'numeric string (float)' => ['height', '5.11', FloatParameter::class, 5.11]
        ];
    }

    /**
     * @dataProvider provideExampleParameters
     * @param string $name                Parameter name
     * @param mixed $value                Parameter value
     * @param class-string $expected_type Expected parameter class
     * @param mixed $expected_value       Expected parameter value
     */
    public function testCanInferTypeFromValue(string $name, $value, string $expected_type, $expected_value = null): void
    {
        $parameter = ParameterProcessor::infer($name, $value);

        self::assertInstanceOf($expected_type, $parameter);
        self::assertSame($name, $parameter->name);
        self::assertSame($expected_value ?? $value, $parameter->value);
    }

    /** @covers \Swiftly\Database\Exception\ParameterException */
    public function testWillThrowOnNullValue(): void
    {
        self::expectException(ParameterException::class);

        ParameterProcessor::infer('param', null);
    }

    /** @covers \Swiftly\Database\Exception\ParameterException */
    public function testWillThrowOnObject(): void
    {
        self::expectException(ParameterException::class);

        ParameterProcessor::infer('param', new \stdClass);
    }
}