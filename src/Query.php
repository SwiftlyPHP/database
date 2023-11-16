<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\Parameter;
use Swiftly\Database\DatabaseAwareInterface;
use Swiftly\Database\DatabaseAwareTrait;
use Swiftly\Database\Exception\OrphanedQueryException;
use Swiftly\Database\Collection;

/**
 * Stores information regarding a single SQL query.
 *
 * @package Query
 *
 * @psalm-external-mutation-free
 */
class Query implements DatabaseAwareInterface
{
    use DatabaseAwareTrait;

    const TYPE_SELECT = 'SELECT';
    const TYPE_UPDATE = 'UPDATE';
    const TYPE_INSERT = 'INSERT';
    const TYPE_DELETE = 'DELETE';

    const STATUS_OK = true;
    const STATUS_ERROR = false;

    /** @var non-empty-string $query */
    private string $query;

    /** @var array<non-empty-string,Parameter> $parameters */
    private array $parameters;

    /**
     * Create a new wrapper around the given SQL.
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
     * @param non-empty-string $name     Parameter name
     * @param scalar|list<scalar> $value Value to be escaped
     * @return self                      Fluent interface
     */
    public function setParameter(string $name, $value): self
    {
        $this->parameters[$name] = new Parameter($name, $value);

        return $this;
    }

    /**
     * Return all the parameters that have been provided for this query.
     *
     * @return array<non-empty-string,Parameter> Query parameter values
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Determine if any parameters have been provided for this query.
     *
     * @return bool Query has parameter values.
     */
    public function hasParameters(): bool
    {
        return !empty($this->parameters);
    }

    /**
     * Return the raw (unescaped) SQL query.
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
     * @throws OrphanedQueryException
     *      If executing a query that is not associated with a database.
     */
    public function execute(): ?Collection
    {
        $database = $this->getDatabase();

        if ($database === null) {
            throw OrphanedQueryException::create();
        }

        return $database->execute($this);
    }
}