<?php declare(strict_types=1);

namespace Swiftly\Database\Backend;

use Swiftly\Database\BackendInterface;

/**
 * Adapter that utilises PDO to perform database operations.
 *
 * The preferred adapter to use in most cases. PDO provides a uniform,
 * battle-tested way of interfacing with pretty much all standard SQL database
 * backends.
 *
 * Unless you have non-standard requirements we strongly encourage you to use
 * this adapter.
 *
 * @package Adapter
 */
class PdoAdapter implements BackendInterface
{
    
}