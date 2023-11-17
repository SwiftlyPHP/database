<?php declare(strict_types=1);

namespace Swiftly\Database;

use Swiftly\Database\BackendInterface;

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
     * @return BackendInterface Configured adapter object
     */
    public function create(): BackendInterface;
}
