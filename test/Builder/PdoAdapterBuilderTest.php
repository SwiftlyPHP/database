<?php declare(strict_types=1);

namespace Swiftly\Database\Test\Builder;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Test\PdoExpectationTrait;
use Swiftly\Database\Backend\PdoAdapter;
use Exception;

/**
 * @covers \Swiftly\Database\Builder\PdoAdapterBuilder
 * @uses \Swiftly\Database\Backend\PdoAdapter
 */
class PdoAdapterBuilderTest extends TestCase
{
    use PdoExpectationTrait;

    /** @var MockObject&PDO $pdo */
    private MockObject $pdo;
    private PdoAdapterBuilder $builder;

    public function setUp(): void
    {
        $this->builder = new PdoAdapterBuilder('test');
        $this->pdo = self::createMock(PDO::class);

        PdoAdapterBuilder::setFactory([$this, 'validateExceptations']);
    }

    public function validateExceptations(string $dsn, ?string $username, ?string $password, array $options): PDO
    {
        if (null !== $this->expectedDsn) {
            self::assertThat($dsn, $this->expectedDsn);
        }

        if (null !== $this->expectedUsername) {
            self::assertThat($username, $this->expectedUsername);
        }

        if (null !== $this->expectedPassword) {
            self::assertThat($password, $this->expectedPassword);
        }

        if (null !== $this->expectedOptions) {
            self::assertThat($options, $this->expectedOptions);
        }

        return $this->pdo;
    }

    public function testCanConfigureHostnameAndPort(): void
    {
        $this->builder
            ->setHostname('127.0.0.1')
            ->setPort(4242);

        self::expectDsn('test:host=127.0.0.1;port=4242');

        $this->builder->create();
    }

    public function testCanConfigureUnixSocket(): void
    {
        $this->builder
            ->setSocket('/var/run/database.sock');

        self::expectDsn('test:unix_socket=/var/run/database.sock');

        $this->builder->create();
    }

    public function testCanConfigureUsernameAndPassword(): void
    {
        $this->builder
            ->setHostname('localhost')
            ->setUsername('root')
            ->setPassword('123');

        self::expectDsn('test:host=localhost;user=root;password=123');
        self::expectUsername('root');
        self::expectPassword('123');

        $this->builder->create();
    }

    public function testCanConfigureDatabase(): void
    {
        $this->builder
            ->setHostname('localhost')
            ->setDatabase('sales');

        self::expectDsn('test:host=localhost;dbname=sales');

        $this->builder->create();
    }

    public function testCanConfigureCharset(): void
    {
        $this->builder
            ->setHostname('localhost')
            ->setCharset('utf8');

        self::expectDsn('test:host=localhost;charset=utf8');

        $this->builder->create();
    }

    public function testCanConfigurePdoAttribute(): void
    {
        $this->pdo->expects(self::once())
            ->method('setAttribute')
            ->with(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->builder
            ->setHostname('localhost')
            ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
            ->create();
    }

    public function testCanConfigureDatabaseOption(): void
    {
        $this->builder
            ->setHostname('localhost')
            ->setOption('timeout', 1000)
            ->setOption('verbose', false);

        self::expectOptions(['timeout' => 1000, 'verbose' => false]);

        $this->builder->create();
    }

    public function testCanCreateAdapterFromConfiguration(): void
    {
        $this->builder
            ->setHostname('localhost')
            ->setUsername('root')
            ->setPassword('123')
            ->setDatabase('sales')
            ->setCharset('utf8')
            ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
            ->setOption('verbose', false);

        self::expectDsnMatches('/^test:host=localhost;user=root;password=123/');
        self::expectUsername('root');
        self::expectPassword('123');
        self::expectOptions(['verbose' => false]);

        $adapter = $this->builder->create();

        self::assertInstanceOf(PdoAdapter::class, $adapter);
    }

    public function testWillReturnNewInstanceOnRepeatedCalls(): void
    {
        $this->builder->setHostname('localhost');

        $instance1 = $this->builder->create();
        $instance2 = $this->builder->create();

        self::assertInstanceOf(PdoAdapter::class, $instance1);
        self::assertInstanceOf(PdoAdapter::class, $instance2);
        self::assertNotSame($instance1, $instance2);
    }

    public function testWillUseProvidedFactory(): void
    {
        PdoAdapterBuilder::setFactory(
            function ($dsn, $username, $password, $options) {
                self::assertSame('test:host=localhost;user=root;password=123', $dsn);
                self::assertSame('root', $username);
                self::assertSame('123', $password);
                self::assertSame(['verbose' => true], $options);

                return self::createMock(PDO::class);
            }
        );

        $this->builder
            ->setHostname('localhost')
            ->setUsername('root')
            ->setPassword('123')
            ->setOption('verbose', true)
            ->create();
    }

    public function testCanResetFactory(): void
    {
        $this->builder->setHostname('localhost');

        PdoAdapterBuilder::setFactory(function () use (&$count) {
            $count++;
            
            return self::createMock(PDO::class);
        });

        $this->builder->create();

        self::assertSame(1, $count);

        PdoAdapterBuilder::setFactory(null);

        // Will throw "PDOException: could not find driver"
        try {
            $this->builder->create();
        } catch (Exception $e) {}

        self::assertSame(1, $count);
    }

    public function testCanCreateAdapterForMysql(): void
    {
        self::expectDsnMatches('/^mysql:host=/');

        PdoAdapterBuilder::Mysql()
            ->setHostname('localhost')
            ->create();
    }

    public function testCanCreateAdapterForPostgres(): void
    {
        self::expectDsnMatches('/^pgsql:host=/');

        PdoAdapterBuilder::Postgres()
            ->setHostname('localhost')
            ->create();
    }
}