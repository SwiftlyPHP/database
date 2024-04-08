<?php declare(strict_types=1);

namespace Swiftly\Database\Test\Builder;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Swiftly\Database\Builder\PdoAdapterBuilder;
use Swiftly\Database\Dsn;
use Swiftly\Database\Test\PdoExpectationTrait;
use Swiftly\Database\Adapter\PdoAdapter;
use PDOException;

/**
 * @covers \Swiftly\Database\Builder\PdoAdapterBuilder
 * @uses \Swiftly\Database\Adapter\PdoAdapter
 * @uses \Swiftly\Database\Dsn
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

        PdoAdapterBuilder::setFactory([$this, 'validatePdo']);
        Dsn::registerScheme('test', [$this, 'validateDsn']);
    }

    public function validatePdo(
        string $dsn,
        ?string $username,
        ?string $password,
        array $options
    ): PDO {
        if (null !== $this->expectedDsn) {
            self::assertThat($dsn, $this->expectedDsn);
        }

        if (null !== $this->expectedOptions) {
            self::assertThat($options, $this->expectedOptions);
        }

        return $this->pdo;
    }

    public function validateDsn(array $options): string
    {
        if (null !== $this->expectedHost) {
            self::assertArrayHasKey('host', $options);
            self::assertThat($options['host'], $this->expectedHost);
        }

        if (null !== $this->expectedSocket) {
            self::assertArrayHasKey('socket', $options);
            self::assertThat($options['socket'], $this->expectedSocket);
        }

        if (null !== $this->expectedPort) {
            self::assertArrayHasKey('port', $options);
            self::assertThat($options['port'], $this->expectedPort);
        }

        if (null !== $this->expectedUsername) {
            self::assertArrayHasKey('username', $options);
            self::assertThat($options['username'], $this->expectedUsername);
        }

        if (null !== $this->expectedPassword) {
            self::assertArrayHasKey('username', $options);
            self::assertThat($options['password'], $this->expectedPassword);
        }

        return 'test-dsn';
    }

    public function testCanConfigureHostnameAndPort(): void
    {
        self::expectHost('127.0.0.1');
        self::expectPort(4242);

        $this->builder
            ->setHostname('127.0.0.1')
            ->setPort(4242)
            ->create();
    }

    public function testCanConfigureUnixSocket(): void
    {
        self::expectSocket('/var/run/database.sock');

        $this->builder
            ->setSocket('/var/run/database.sock')
            ->create();
    }

    public function testCanConfigureUsernameAndPassword(): void
    {
        self::expectHost('localhost');
        self::expectUsername('root');
        self::expectPassword('123');

        $this->builder
            ->setHostname('localhost')
            ->setUsername('root')
            ->setPassword('123')
            ->create();
    }

    public function testCanConfigureDatabase(): void
    {
        self::expectHost('localhost');
        self::expectDatabase('sales');

        $this->builder
            ->setHostname('localhost')
            ->setDatabase('sales')
            ->create();
    }

    public function testCanConfigureCharset(): void
    {
        self::expectHost('localhost');
        self::expectCharset('utf8');

        $this->builder
            ->setHostname('localhost')
            ->setCharset('utf8')
            ->create();
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
        self::expectOptions(['timeout' => 1000, 'verbose' => false]);

        $this->builder
            ->setHostname('localhost')
            ->setOption('timeout', 1000)
            ->setOption('verbose', false)
            ->create();
    }

    public function testCanCreateAdapterFromConfiguration(): void
    {
        self::expectHost('localhost');
        self::expectUsername('root');
        self::expectPassword('123');
        self::expectDatabase('sales');
        self::expectCharset('utf8');
        self::expectOptions(['verbose' => false]);

        $adapter = $this->builder
            ->setHostname('localhost')
            ->setUsername('root')
            ->setPassword('123')
            ->setDatabase('sales')
            ->setCharset('utf8')
            ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
            ->setOption('verbose', false)
            ->create();

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

        $count = 0;

        PdoAdapterBuilder::setFactory(function () use (&$count) {
            $count++;
            
            return self::createMock(PDO::class);
        });

        $this->builder->create();

        self::assertSame(1, $count);

        PdoAdapterBuilder::setFactory(null);

        // Suppress thrown "PDOException: could not find driver"
        try {
            $this->builder->create();
        } catch (PDOException $e) {}

        self::assertSame(1, $count);
    }

    public function testCanCreateAdapterForMysql(): void
    {
        self::expectDsn('mysql:host=localhost');

        PdoAdapterBuilder::Mysql()
            ->setHostname('localhost')
            ->create();
    }

    public function testCanCreateAdapterForPostgres(): void
    {
        self::expectDsn('pgsql:host=localhost');

        PdoAdapterBuilder::Postgres()
            ->setHostname('localhost')
            ->create();
    }
}