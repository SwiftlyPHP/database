<?php

namespace Swiftly\Database\Adapter;

use Swiftly\Database\AdapterInterface;
use SQLite3;
use SQLite3Result;

use const SQLITE3_OPEN_READWRITE;
use const SQLITE3_OPEN_CREATE;

/**
 * Adapter used to work with SQLite databases
 *
 * @author clvarley
 */
Class SqliteAdapter Implements AdapterInterface
{

    /**
     * Absolute path to SQLite db file
     *
     * @var string $filepath SQLite filepath
     */
    private $filepath;

    /**
     * SQLite database handle
     *
     * @var SQLite3|null $handle Database handle
     */
    private $handle = null;

    /**
     * Result set from the most recent query
     *
     * @var SQLite3Result|null $result SQLite result
     */
    private $result = null;

    /**
     * Creates an adapter using the given SQLite database info
     *
     * @param string $filepath SQLite filepath
     */
    public function __construct( string $filepath )
    {
        $this->filepath = $filepath;
    }

    /**
     * {@inheritdoc}
     */
    public function connect() : bool
    {
        $this->handle = new SQLite3(
            $this->filepath,
            SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE
        );

        // Connection failed?
        return ( $this->handle->lastErrorCode() === 0 );
    }

    /**
     * {@inheritdoc}
     */
    public function disconnect() : void
    {
        // TODO

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function query( string $sql ) : bool
    {
        // TODO

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult() : array
    {
        // TODO

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getResults() : array
    {
        // TODO

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLastInsertId() : int
    {
        // TODO

        return 0;
    }
}
