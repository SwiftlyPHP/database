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
    protected ?Constraint $expectedUsername = null;
    protected ?Constraint $expectedPassword = null;
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
     * Assert that the DSN matches the given regular expression
     *
     * @param string $pattern Regular expression
     */
    public function expectDsnMatches(string $pattern): void
    {
        $this->expectedDsn = new RegularExpression($pattern);
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
     * Assert that the database username matches the given regular expression
     *
     * @param string $pattern Regular expression
     */
    public function expectUsernameMatches(string $pattern): void
    {
        $this->expectedUsername = new RegularExpression($pattern);
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
     * Assert that the database password matches the given regular expression
     *
     * @param string $pattern Regular expression
     */
    public function expectPasswordMatches(string $pattern): void
    {
        $this->expectedPassword = new RegularExpression($pattern);
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