<?php declare(strict_types=1);

namespace Swiftly\Database\Backend;

use Swiftly\Database\BackendInterface;
use PDO;
use Swiftly\Database\Collection;
use PDOStatement;
use PDOException;
use Swiftly\Database\Exception\QueryException;
use Swiftly\Database\Parameter;

use function is_array;
use function implode;

/**
 * Adapter that utilises PDO to perform database operations.
 *
 * The preferred adapter to use in most cases. PDO provides a uniform,
 * battle-tested way of interfacing with almost all standard SQL database
 * backends.
 *
 * Unless you have non-standard requirements we strongly encourage you to use
 * this adapter.
 *
 * @see \Swiftly\Database\Builder\PdoAdapterBuilder
 *
 * @package Adapter
 */
class PdoAdapter implements BackendInterface
{
    private PDO $pdo;

    /**
     * Create a new adapter around the given PDO instance.
     *
     * @param PDO $pdo Database connection object
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** {@inheritDoc} */
    public function execute(string $sql, array $parameters = []): ?Collection
    {
        $statement = $this->pdo->prepare($sql);

        foreach ($parameters as $name => $value) {
            $statement->bindValue($name, $value, PDO::PARAM_STR);
        }

        $status = $this->doQuery($statement);

        $result = $status
            ? $this->prepareResult($statement)
            : null;

        return $result;
    }

    /**
     * Execute the provided PDO statment and prepare the result set.
     *
     * This method is respectfull and restores the PDO error mode after each
     * query. That way, should a user also be using the PDO object directly
     * outside of this adapter, they do not encounter any unexpected exceptions.
     *
     * @param PDOStatement $statement Statement to execute
     * @return bool                   Query execution status
     *
     * @throws QueryException
     *      If a more specific exception reason cannot be found
     */
    private function doQuery(PDOStatement $statement): bool
    {
        $error_mode = $this->pdo->getAttribute(PDO::ATTR_ERRMODE);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $status = $statement->execute();
        } catch (PDOException $exception) {
            $this->parseException($exception);
        } finally {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, $error_mode);
        }

        return $status;
    }

    /**
     * Parse the error returned by PDO and throw the appropriate exception.
     *
     * @param PDOException $exception PDO query exception
     * @return never
     *
     * @throws QueryException
     *      If a more specific exception reason cannot be found
     */
    private function parseException(PDOException $exception): void
    {
        throw QueryException::createFromException($exception);
    }

    /**
     * Parse and return the result of an executed PDO statement.
     *
     * Additionally closes the current cursor (if open), clearing the connection
     * for future queries.
     *
     * @param PDOStatement $statement Executed PDO statement
     * @return Collection             Query results
     */
    private function prepareResult(PDOStatement $statement): Collection
    {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        return new Collection($result);
    }

    /**
     * {@inheritDoc}
     *
     * This adapter relies on {@see PDO::bindValue} to escape values, so apart
     * from handling array values in the case of `WHERE-IN` statements we do no
     * further processing.
     */
    public function escape(Parameter $parameter): string
    {
        $value = $parameter->value;

        if (is_array($value)) {
            $value = implode(',', $value);
        }

        return (string)$value;
    }
}
