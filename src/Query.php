<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\DatabaseAwareInterface;
use Swiftly\Database\DatabaseAwareTrait;
use Swiftly\Database\AbstractParameter;
use Swiftly\Database\ParameterProcessor;
use Swiftly\Database\Exception\AdapterException;
use Swiftly\Database\Collection;

/**
 * Stores information regarding a single SQL query.
 *
 * @package Query
 *
 * @readonly
 */
class Query implements DatabaseAwareInterface
{
    use DatabaseAwareTrait;

    /** @var non-empty-string $query */
    private string $query;

    /** @var array<non-empty-string,AbstractParameter> $parameters */
    private array $parameters;

    /**
     * Create a new wrapper around the given SQL.
     *
     * @psalm-external-mutation-free
     *
     * @param non-empty-string $query SQL statement
     */
    public function __construct(string $query)
    {
        $this->query = $query;
        $this->parameters = [];
        $this->setDatabase(null);
    }

    /**
     * Set the value of a placeholder parameter.
     *
     * @psalm-external-mutation-free
     *
     * @param non-empty-string $name     Parameter name
     * @param scalar|list<scalar> $value Value to be escaped
     * @return self                      Fluent interface
     */
    public function setParameter(string $name, $value): self
    {
        $this->parameters[$name] = ParameterProcessor::infer($name, $value);

        return $this;
    }

    /**
     * Return all the parameters that have been provided for this query.
     *
     * @psalm-mutation-free
     *
     * @return array<non-empty-string,AbstractParameter> Query parameter values
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Determine if any parameters have been provided for this query.
     *
     * @psalm-mutation-free
     *
     * @return bool Query has parameter values
     */
    public function hasParameters(): bool
    {
        return !empty($this->parameters);
    }

    /**
     * Return the raw (unprepared) SQL query.
     *
     * @psalm-mutation-free
     *
     * @return non-empty-string SQL statement
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Attempt to execute this query and return its result.
     *
     * Provides a convenient wrapper for calling `Database::execute($query)`.
     *
     * @see \Swiftly\Database\Database::query
     *
     * @return Collection|null Collection containing query results
     *
     * @throws AdapterException
     *      If executing a query that is not associated with a database
     */
    public function execute(): ?Collection
    {
        $database = $this->getDatabase();

        if (null === $database) {
            throw AdapterException::createForOrphanedQuery();
        }

        return $database->execute($this);
    }
}
