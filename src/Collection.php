<?php declare(strict_types=1);

namespace Swiftly\Database;

use IteratorAggregate;
use Countable;
use Iterator;

use function count;
use function reset;

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
    public function getIterator(): Iterator
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
     * Determine if this collection is empty.
     *
     * @return bool Collection has items
     */
    public function isEmpty(): bool
    {
        return empty($this->rows);
    }

    /**
     * Returns the first item of the collection.
     *
     * Interally uses PHP's array {@see reset} function so this SHOULD NOT be
     * called from within a loop.
     *
     * @return TVal Collection item
     */
    public function first()
    {
        return reset($this->rows);
    }

    /**
     * Filter the items in this collection using the supplied predicate.
     *
     * @psalm-param pure-callable(TVal):bool $predicate
     * @psalm-return self<non-negative-int,TVal>
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
     * @psalm-return self<non-negative-int,TResult>
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
