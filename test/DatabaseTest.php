<?php declare(strict_types=1);

namespace Swiftly\Database\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Swiftly\Database\Database;
use Swiftly\Database\BackendInterface;
use Swiftly\Database\TransactionInterface;
use Swiftly\Database\AbstractParameter;
use Swiftly\Database\Query;
use Swiftly\Database\Collection;
use Swiftly\Database\Exception\TransactionException;
use Swiftly\Database\Exception\UnsupportedOperationException;

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

    /**
     * @see self::testCanExecuteQueriesInTransaction
     * @see self::testWillCommitTransactionOnSuccess
     * @see self::testWillAbortTransactionOnFailure
     * @see self::testWillAbortTransactionOnError
     */
    public function setUpIntersection(): void
    {
        $this->database = new Database(
            $this->backend = self::createMock(BackendTransactionInterface::class)
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
            ->method('getParameters')
            ->willReturn([]);

        $this->backend->expects(self::once())
            ->method('execute')
            ->with(self::EXAMPLE_SELECT, []);

        $this->database->execute($query);
    }

    public function testCanExecuteQueryWithParameter(): void
    {
        $parameter = self::createMock(AbstractParameter::class);
        $parameter->name = 'name';
        $parameter->value = 'John';

        $query = self::createMock(Query::class);
        $query->expects(self::once())
            ->method('getQuery')
            ->willReturn(self::EXAMPLE_SELECT_WHERE);
        $query->expects(self::once())
            ->method('getParameters')
            ->willReturn(['name' => $parameter]);

        $this->backend->expects(self::once())
            ->method('execute')
            ->with(self::EXAMPLE_SELECT_WHERE, ['name' => $parameter]);

        $this->database->execute($query);
    }

    public function testWillReturnResultsFromQuery(): void
    {
        $query = self::createMock(Query::class);
        $query->expects(self::once())
            ->method('getQuery')
            ->willReturn(self::EXAMPLE_SELECT);
        $query->expects(self::once())
            ->method('getParameters')
            ->willReturn([]);

        $collection = self::createMock(Collection::class);

        $this->backend->expects(self::once())
            ->method('execute')
            ->with(self::EXAMPLE_SELECT, [])
            ->willReturn($collection);

        $result = $this->database->execute($query);

        self::assertSame($collection, $result);
    }

    public function testCanExecuteQueriesInTransaction(): void
    {
        $this->setUpIntersection();

        $this->backend->expects(self::once())
            ->method('startTransaction');
        $this->backend->expects(self::once())
            ->method('execute')
            ->with(SELF::EXAMPLE_SELECT);

        $this->database->withTransaction(function (Database $database) {
            self::assertSame($this->database, $database);

            $database->query(self::EXAMPLE_SELECT)->execute();
        });
    }

    public function testWillReturnTransactionResult(): void
    {
        $this->setUpIntersection();

        $collection = self::createMock(Collection::class);

        $this->backend->expects(self::once())
            ->method('execute')
            ->with(self::EXAMPLE_SELECT)
            ->willReturn($collection);

        $result = $this->database->withTransaction(function (Database $database) {
            return $database->query(self::EXAMPLE_SELECT)->execute();
        });

        self::assertSame($collection, $result);
    }

    public function testWillCommitTransactionOnSuccess(): void
    {
        $this->setUpIntersection();

        $this->backend->expects(self::once())
            ->method('commitTransaction');
        $this->backend->expects(self::never())
            ->method('abortTransaction');

        $this->database->withTransaction(function (Database $database) {
            return;
        });
    }

    public function testWillAbortTransactionOnFailure(): void
    {
        $this->setUpIntersection();

        $this->backend->expects(self::once())
            ->method('abortTransaction');
        $this->backend->expects(self::never())
            ->method('commitTransaction');

        $this->database->withTransaction(function (Database $database) {
            return false;
        });
    }

    /** @covers \Swiftly\Database\Exception\TransactionException */
    public function testWillAbortTransactionOnError(): void
    {
        $this->setUpIntersection();

        $this->backend->expects(self::once())
            ->method('abortTransaction');
        $this->backend->expects(self::never())
            ->method('commitTransaction');

        self::expectException(TransactionException::class);
        self::expectExceptionMessageMatches('/nested error/');

        $this->database->withTransaction(function (Database $database) {
            throw new Exception('nested error');
        });
    }

    /** @covers \Swiftly\Database\Exception\TransactionException */
    public function testWillThrowOnNestedTransactions(): void
    {
        $this->setUpIntersection();

        $this->backend->expects(self::once())
            ->method('startTransaction');
        $this->backend->expects(self::once())
            ->method('abortTransaction');

        self::expectException(TransactionException::class);

        $this->database->withTransaction(function (Database $database) {
            $database->withTransaction(function () {});
        });
    }

    /** @covers \Swiftly\Database\Exception\UnsupportedOperationException */
    public function testWillThrowIfAdapterDoesNotSupportTransactions(): void
    {
        self::expectException(UnsupportedOperationException::class);

        $this->database->withTransaction(function () {});
    }
}

/**
 * Allows the mocking of intersection BackendInterface & TransactionInterface
 *
 * When we reach PHPUnit 10 we can use `createMockForIntersectionOfInterfaces()`
 *
 * @internal
 */
interface BackendTransactionInterface extends
    BackendInterface,
    TransactionInterface
{}