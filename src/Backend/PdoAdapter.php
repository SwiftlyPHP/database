<?php declare(strict_types=1);

namespace Swiftly\Database\Backend;

use Swiftly\Database\BackendInterface;
use PDO;
use Swiftly\Database\Collection;
use PDOStatement;
use PDOException;
use Swiftly\Database\Exception\QueryException;
use Swiftly\Database\AbstractParameter;
use Swiftly\Database\Parameter\SetParameter;
use Swiftly\Database\Parameter\BooleanParameter;
use Swiftly\Database\Parameter\IntegerParameter;

use function preg_quote;
use function preg_replace_callback;
use function is_bool;
use function is_int;
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
        $statement = $this->prepare($sql, $parameters);

        foreach ($parameters as $value) {
            self::bindValue($statement, $value);
        }

        $status = $this->doQuery($statement);

        $result = $status
            ? self::prepareResult($statement)
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
    private static function prepareResult(PDOStatement $statement): Collection
    {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        return new Collection($result);
    }

    /**
     * Prepare the given SQL query and return a ready-to-execute PDO statement.
     *
     * Additionally, this method also handles inserting sets/lists into the SQL
     * as PDO does not currently support binding arrays of values.
     *
     * @param non-empty-string $sql                       SQL query to prepare
     * @param array<string,AbstractParameter> $parameters Query parameters
     */
    private function prepare(string $sql, array $parameters): PDOStatement
    {
        $parameters_to_replace = self::filterSets($parameters);

        foreach ($parameters_to_replace as $name => $replace) {
            $sql = $this->replaceParameter($sql, $name, $replace->value);
        }

        return $this->pdo->prepare($sql);
    }

    /**
     * Filter an array of parameters keeping only those who have array values.
     *
     * @param array<string,AbstractParameter> $parameters Parameters to filter
     * @return array<string,SetParameter>                 Filtered parameters
     */
    private static function filterSets(array $parameters): array
    {
        $sets = [];

        foreach ($parameters as $name => $parameter) {
            if ($parameter instanceof SetParameter) {
                $sets[$name] = $parameter;
            }
        }

        return $sets;
    }

    /**
     * Replace a placeholder name in a query with its list of values.
     *
     * @param non-empty-string $sql SQL query to update
     * @param string $name          Parameter to replace
     * @param array<scalar> $values Values to insert
     * @return non-empty-string     Updated SQL query
     */
    private function replaceParameter(
        string $sql,
        string $name,
        array $values
    ): string {
        $regex = '/\:' . preg_quote($name, '/') . '/';

        /** @var non-empty-string */
        return preg_replace_callback($regex, function () use ($values) {
            return $this->setToString($values);
        }, $sql);
    }

    /**
     * Convert a list/set of values into a safe string for SQL insertion.
     *
     * @param array<scalar> $values Parameter list
     * @return string
     */
    private function setToString(array $values): string
    {
        $escaped = [];

        foreach ($values as $value) {
            if (is_string($value)) {
                $escaped[] = $this->pdo->quote($value, PDO::PARAM_STR);
            } else {
                $escaped[] = (string)$value;
            }
        }

        return '(' . implode(',', $escaped) . ')';
    }

    /**
     * Return the appropriate `PDO::PARAM_*` type to use for a given parameter.
     *
     * @upgrade Swap to match exp at php 8
     * @psalm-return PDO::PARAM_*
     *
     * @param AbstractParameter $parameter Query parameter to insert
     * @return int                         PDO parameter type
     */
    private static function getParamType(AbstractParameter $parameter): int
    {
        switch (true) {
            case $parameter instanceof BooleanParameter:
                return PDO::PARAM_BOOL;
            case $parameter instanceof IntegerParameter:
                return PDO::PARAM_INT;
            default:
                return PDO::PARAM_STR;
        }
    }

    /**
     * Bind a parameter to the query, choosing the most appropriate data type.
     *
     * @upgrade Swap to match at php 8
     *
     * @param PDOStatement $statement      PDO statement to be executed
     * @param AbstractParameter $parameter Parameter to be bound
     */
    private static function bindValue(
        PDOStatement $statement,
        AbstractParameter $parameter
    ): void {
        $statement->bindValue(
            $parameter->name,
            $parameter->value,
            self::getParamType($parameter)
        );
    }
}
