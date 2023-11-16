<?php declare(strict_types=1);

namespace Swiftly\Database;

use IteratorAggregate;
use Countable;

use function count;

/**
 * Stores the returned results of an executed SQL query.
 *
 * @package Query
 *
 * @psalm-immutable
 *
 * @template TKey of array-key
 * @template TVal
 * @implements IteratorAggregate<TKey,TVal>
 */
class Collection implements IteratorAggregate, Countable
{
    /** @var array<TKey,TVal> $rows */
    private array $rows;

    /**
     * Create a new collection around the given rows.
     *
     * @param array<TKey,TVal> $rows Query result rows
     */
    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    /** {@inheritDoc} */
    public function getIterator(): iterable
    {
        yield from $this->rows;
    }

    /**
     * Return the number of items in this collection.
     *
     * @param int Row count
     */
    public function count(): int
    {
        return count($this->rows);
    }

    /**
     * Filter the items in this collection using the supplied predicate.
     *
     * @psalm-param pure-callable(TVal):bool $predicate
     *
     * @param callable $predicate Filter function
     * @return self<int,TVal>     Filtered collection
     */
    public function filter(callable $predicate): self
    {
        $filtered = [];

        foreach ($this->rows as $row) {
            if ($predicate($row)) {
                $filtered[] = $row;
            }
        }

        return new self($filtered);
    }

    /**
     * Create a new collection with the result of calling the supplied function.
     *
     * @template TResult
     *
     * @psalm-param pure-callable(TVal):TResult $callback
     *
     * @param callable $callback Map function
     * @return self<int,TResult> New collection
     */
    public function map(callable $callback): self
    {
        $mapped = [];

        foreach ($this->rows as $row) {
            $mapped[] = $callback($row);
        }

        return new self($mapped);
    }
}