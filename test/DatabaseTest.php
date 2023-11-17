<?php declare(strict_types=1);

namespace Swiftly\Database\Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Swiftly\Database\Database;
use Swiftly\Database\BackendInterface;
use Swiftly\Database\Parameter;
use Swiftly\Database\Query;
use Swiftly\Database\Collection;

/**
 * @covers \Swiftly\Database\Database
 * @uses \Swiftly\Database\Query
 */
class DatabaseTest extends TestCase
{
    /** @var MockObject&BackendInterface $backend */
    private MockObject $backend;
    private Database $database;

    const EXAMPLE_SELECT = 'SELECT * FROM test';
    const EXAMPLE_SELECT_WHERE = 'SELECT * FROM test WHERE name = :name';

    public function setUp(): void
    {
        $this->database = new Database(
            $this->backend = self::createMock(BackendInterface::class)
        );
    }

    public function testCanCreateQuery(): void
    {
        $query = $this->database->query(self::EXAMPLE_SELECT);

        self::assertSame(self::EXAMPLE_SELECT, $query->getQuery());
    }

    public function testWillAttachSelfToQuery(): void
    {
        $query = $this->database->query(self::EXAMPLE_SELECT);

        self::assertSame($this->database, $query->getDatabase());
    }

    public function testCanExecuteQuery(): void
    {
        $query = self::createMock(Query::class);
        $query->expects(self::once())
            ->method('getQuery')
            ->willReturn(self::EXAMPLE_SELECT);
        $query->expects(self::once())
            ->method('hasParameters')
            ->willReturn(false);

        $this->backend->expects(self::once())
            ->method('execute')
            ->with(self::EXAMPLE_SELECT, []);

        $this->database->execute($query);
    }

    public function testCanExecuteQueryWithParameter(): void
    {
        $parameter = self::createMock(Parameter::class);
        $parameter->name = 'name';
        $parameter->value = 'John';

        $query = self::createMock(Query::class);
        $query->expects(self::once())
            ->method('getQuery')
            ->willReturn(self::EXAMPLE_SELECT_WHERE);
        $query->expects(self::once())
            ->method('hasParameters')
            ->willReturn(true);
        $query->expects(self::once())
            ->method('getParameters')
            ->willReturn(['name' => $parameter]);

        $this->backend->expects(self::once())
            ->method('escape')
            ->with($parameter)
            ->willReturn('John');
        $this->backend->expects(self::once())
            ->method('execute')
            ->with(self::EXAMPLE_SELECT_WHERE, ['name' => 'John']);

        $this->database->execute($query);
    }

    public function testWillReturnResultsFromQuery(): void
    {
        $query = self::createMock(Query::class);
        $query->expects(self::once())
            ->method('getQuery')
            ->willReturn(self::EXAMPLE_SELECT);
        $query->expects(self::once())
            ->method('hasParameters')
            ->willReturn(false);

        $collection = self::createMock(Collection::class);

        $this->backend->expects(self::once())
            ->method('execute')
            ->with(self::EXAMPLE_SELECT, [])
            ->willReturn($collection);

        $result = $this->database->execute($query);

        self::assertSame($collection, $result);
    }
}