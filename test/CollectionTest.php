<?php declare(strict_types=1);

namespace Swiftly\Database\Test;

use PHPUnit\Framework\TestCase;
use Swiftly\Database\Collection;

/**
 * @covers \Swiftly\Database\Collection
 */
final class CollectionTest extends TestCase
{
    private Collection $collection;

    public function setUp(): void
    {
        $this->collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    }

    public function testCanGetNumberOfItemsInCollection(): void
    {
        self::assertEquals(10, $this->collection->count());
        self::assertCount(10, $this->collection);
    }

    public function testCanTellIfCollectionEmpty(): void
    {
        self::assertFalse($this->collection->isEmpty());
        self::assertTrue((new Collection([]))->isEmpty());
    }

    public function testCanGetFirstItemInCollection(): void
    {
        self::assertSame(1, $this->collection->first());
    }

    public function testCanIterateOverItemsInCollection(): void
    {
        $expected_index = 0;

        self::assertIsIterable($this->collection);

        foreach ($this->collection as $index => $item) {
            self::assertEquals($expected_index++, $index);
            self::assertEquals($expected_index, $item);
        }
    }

    public function testCanFilterItemsInCollection(): void
    {
        $even_numbers = $this->collection->filter(static function (int $item) {
            return ($item % 2) === 0;
        });

        self::assertCount(5, $even_numbers);

        foreach ($even_numbers as $number) {
            self::assertTrue($number % 2 === 0);
        }
    }

    public function testCanMapItemsInCollection(): void
    {
        $squared_numbers = $this->collection->map(static function (int $item) {
            return $item * $item;
        });

        self::assertCount(10, $squared_numbers);

        foreach ($squared_numbers as $index => $number) {
            self::assertTrue(($number / ($index + 1)) === $index + 1);
        }
    }
}