<?php

namespace Swiftly\Database\Adapter;

use Swiftly\Database\{
    AdapterInterface,
    Connection
};

use mysqli;

/**
 * Adapter used to work with MySQL databases
 *
 * @author clvarley
 */
Class MysqlAdapter Implements AdapterInterface
{

    /**
     * Database connection parameters
     *
     * @var Connection $connection Connection info
     */
    protected $connection;

    /**
     * Raw MySqli database handle
     *
     * @var mysqli $handle Database handle
     */
    protected $handle;

    /**
     * Creates an adapter using the given MySQL database info
     *
     * @param Connection $connection Connection info
     */
    public function __construct( Connection $connection )
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function connect() : bool
    {
        $this->handle = new mysqli(
            $this->connection->host,
            $this->connection->username,
            $this->connection->password,
            $this->connection->name,
            $this->connection->port
        );

        // Connection failed?
        return ( $this->handle->connect_errno === 0 );
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
