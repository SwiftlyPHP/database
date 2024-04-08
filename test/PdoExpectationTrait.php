<?php

namespace Swiftly\Database\Test;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\RegularExpression;

/**
 * Trait used to provide ability to inspect PDO creation.
 */
trait PdoExpectationTrait
{
    protected ?Constraint $expectedDsn = null;
    protected ?Constraint $expectedHost = null;
    protected ?Constraint $expectedPort = null;
    protected ?Constraint $expectedSocket = null;
    protected ?Constraint $expectedUsername = null;
    protected ?Constraint $expectedPassword = null;
    protected ?Constraint $expectedDatabase = null;
    protected ?Constraint $expectedCharset = null;
    protected ?Constraint $expectedOptions = null;

    /**
     * Assert that the DSN matches the given string
     *
     * @param string $dsn Expected DSN
     */
    public function expectDsn(string $dsn): void
    {
        $this->expectedDsn = new IsEqual($dsn);
    }

    /**
     * Assert that the hostname matches the given string
     *
     * @param string $host Expected hostname
     */
    public function expectHost(string $host): void
    {
        $this->expectedHost = new IsEqual($host);
    }

    /**
     * Assert that the server port matches the given value
     *
     * @param int $port Port number
     */
    public function expectPort(int $port): void
    {
        $this->expectedPort = new IsEqual($port);
    }

    /**
     * Assert that the unix socket matches the given value
     *
     * @param int $socket Socket name
     */
    public function expectSocket(string $socket): void
    {
        $this->expectedSocket = new IsEqual($socket);
    }

    /**
     * Assert that the database username matches the given string
     *
     * @param string $username Expected username
     */
    public function expectUsername(string $username): void
    {
        $this->expectedUsername = new IsEqual($username);
    }

    /**
     * Assert that the database password matches the given string
     *
     * @param string $password Expected password
     */
    public function expectPassword(string $password): void
    {
        $this->expectedPassword = new IsEqual($password);
    }

    /**
     * Assert that the database matches the given string
     *
     * @param string $database Database name
     */
    public function expectDatabase(string $database): void
    {
        $this->expectedDatabase = new IsEqual($database);
    }

    /**
     * Assert that the charset matches the given string
     *
     * @param string $charset Charset name
     */
    public function expectCharset(string $charset): void
    {
        $this->expectedCharset = new IsEqual($charset);
    }

    /**
     * Assert that the database options match the given array
     *
     * @param array $options Expected options
     */
    public function expectOptions(array $options): void
    {
        $this->expectedOptions = new IsEqual($options);
    }
}