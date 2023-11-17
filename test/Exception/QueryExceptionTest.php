<?php declare(strict_types=1);

namespace Swiftly\Database\Test\Exception;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\Exception\QueryException;
use Swiftly\Database\ExceptionInterface;
use Throwable;
use Exception;

/**
 * @covers \Swiftly\Database\Exception\QueryException
 */
class QueryExceptionTest extends TestCase
{
    private QueryException $exception;

    public function setUp(): void
    {
        $this->exception = new QueryException("test");
    }

    public function testIsInstanceOfSharedInterface(): void
    {
        self::assertInstanceOf(ExceptionInterface::class, $this->exception);
    }

    public function testIsThrowable(): void
    {
        self::assertInstanceOf(Throwable::class, $this->exception);
    }

    public function testCanCreateUsingStaticMethod(): void
    {
        self::expectException(QueryException::class);
        self::expectExceptionCode(42);
        self::expectExceptionMessage('Some runtime error');

        $exception = new Exception('Some runtime error', 42);

        throw QueryException::createFromException($exception);
    }
}