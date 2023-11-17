<?php declare(strict_types=1);

namespace Swiftly\Database\Test\Backend;

use PDO;
use PDOStatement;
use PDOException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Swiftly\Database\Backend\PdoAdapter;
use Swiftly\Database\Parameter;
use Swiftly\Database\Collection;
use Swiftly\Database\Exception\QueryException;

/**
 * @covers \Swiftly\Database\Backend\PdoAdapter
 * @uses \Swiftly\Database\Collection
 */
class PdoAdapterTest extends TestCase
{
    /** @var MockObject&PDO $pdo */
    private MockObject $pdo;
    private PdoAdapter $adapter;

    const EXAMPLE_SELECT = 'SELECT * FROM test';
    const EXAMPLE_SELECT_WHERE = 'SELECT * FROM test WHERE name = :name';

    public function setUp(): void
    {
        $this->adapter = new PdoAdapter(
            $this->pdo = self::createMock(PDO::class)
        );
    }

    /** @return MockObject&Parameter */
    private function createMockParameter(string $name, $value): MockObject
    {
        $parameter = self::createMock(Parameter::class);
        $parameter->name = $name;
        $parameter->value = $value;

        return $parameter;
    }

    public function provideExampleParameters(): array
    {
        return [
            'string parameter' => [
                $this->createMockParameter('test', 'active'),
                'active'
            ],
            'int parameter' => [
                $this->createMockParameter('test', 42),
                '42'
            ],
            'float parameter' => [
                $this->createMockParameter('test', 3.14),
                '3.14'
            ],
            'array parameter' => [
                $this->createMockParameter('test', [1, 2, 3]),
                '1,2,3'
            ]
        ];
    }

    /** @dataProvider provideExampleParameters */
    public function testCanEscapeParameterValue(Parameter $parameter, string $expected): void
    {
        $value = $this->adapter->escape($parameter);

        self::assertSame($expected, $value);
    }

    public function testCanExecuteQuery(): void
    {
        $statement = self::createMock(PDOStatement::class);
        $statement->expects(self::once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects(self::once())
            ->method('prepare')
            ->willReturn($statement);

        $this->adapter->execute(self::EXAMPLE_SELECT);
    }

    public function testCanExecuteQueryWithParameter(): void
    {
        $statement = self::createMock(PDOStatement::class);
        $statement->expects(self::once())
            ->method('bindValue')
            ->with('name', 'John');
        $statement->expects(self::once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects(self::once())
            ->method('prepare')
            ->willReturn($statement);

        $this->adapter->execute(self::EXAMPLE_SELECT_WHERE, [
            'name' => 'John'
        ]);
    }

    public function testWillReturnResultsFromQuery(): void
    {
        $statement = self::createMock(PDOStatement::class);
        $statement->expects(self::once())
            ->method('execute')
            ->willReturn(true);
        $statement->expects(self::once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([
                ['name' => 'John', 'title' => 'Doctor']
            ]);

        $this->pdo->expects(self::once())
            ->method('prepare')
            ->willReturn($statement);

        $result = $this->adapter->execute(self::EXAMPLE_SELECT_WHERE);

        self::assertInstanceOf(Collection::class, $result);
        self::assertCount(1, $result);

        foreach ($result as $row) {
            self::assertArrayHasKey('name', $row);
            self::assertSame('John', $row['name']);
            self::assertArrayHasKey('title', $row);
            self::assertSame('Doctor', $row['title']);
        }
    }

    public function testWillCloseCursorAfterQuery(): void
    {
        $statement = self::createMock(PDOStatement::class);
        $statement->expects(self::once())
            ->method('execute')
            ->willReturn(true);
        $statement->expects(self::once())
            ->method('closeCursor');

        $this->pdo->expects(self::once())
            ->method('prepare')
            ->willReturn($statement);

        $this->adapter->execute(self::EXAMPLE_SELECT);
    }

    public function testWillReturnNullIfQueryFails(): void
    {
        $statement = self::createMock(PDOStatement::class);
        $statement->expects(self::once())
            ->method('execute')
            ->willReturn(false);

        $this->pdo->expects(self::once())
            ->method('prepare')
            ->willReturn($statement);

        $result = $this->adapter->execute(self::EXAMPLE_SELECT);

        self::assertNull($result);
    }

    /** @covers \Swiftly\Database\Exception\QueryException */
    public function testWillThrowIfQueryCausesError(): void
    {
        $pdo_exception = self::getMockBuilder(PDOException::class)
            ->setConstructorArgs(['PDO encountered an error'])
            ->getMock();

        $statement = self::createMock(PDOStatement::class);
        $statement->expects(self::once())
            ->method('execute')
            ->willThrowException($pdo_exception);

        $this->pdo->expects(self::once())
            ->method('prepare')
            ->willReturn($statement);

        self::expectException(QueryException::class);
        self::expectExceptionMessage('PDO encountered an error');

        $this->adapter->execute(self::EXAMPLE_SELECT);
    }
}