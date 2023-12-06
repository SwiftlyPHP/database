<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\AdapterInterface;

/**
 * Builders are utility classes used to help configure and create adapters.
 *
 * @package Builder
 */
interface BuilderInterface
{
    /**
     * Create a new database adapter instance.
     *
     * @return AdapterInterface Configured adapter object
     */
    public function create(): AdapterInterface;
}
