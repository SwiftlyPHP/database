<?php

namespace Swiftly\Database\Adapter;

use Swiftly\Database\AdapterInterface;
use Swiftly\Database\Connection;
use mysqli;
use mysqli_result;

use const MYSQLI_ASSOC;

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
     * @var mysqli|null $handle Database handle
     */
    protected $handle = null;

    /**
     * Result of latest query
     *
     * @var mysqli_result|null $result Result set
     */
    private $result = null;

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
        // Free memory
        $this->setResult( null );
        $this->handle->close();

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function query( string $sql ) : bool
    {
        $result = $this->handle->query( $sql );

        // Produces results?
        if ( $result instanceof mysqli_result ) {
            $this->setResult( $result );
        } else {
            $this->setResult( null );
        }

        // Query successful
        return ( $result !== false );
    }

    /**
     * {@inheritdoc}
     */
    public function getResult() : array
    {
        if ( $this->result === null ) {
            return [];
        }

        return $this->result->fetch_assoc() ?: [];
    }

    /**
     * {@inheritdoc}
     */
    public function getResults() : array
    {
        if ( $this->result === null ) {
            return [];
        }

        /** @var array[] */
        return $this->result->fetch_all( MYSQLI_ASSOC );
    }

    /**
     * {@inheritdoc}
     */
    public function getLastInsertId() : int
    {
        return $this->handle->insert_id;
    }

    /**
     * Sets the current result set
     *
     * @internal
     * @param mysqli_result|null $result (Optional) Result set
     * @return void                      N/a
     */
    private function setResult( mysqli_result $result = null ) : void
    {
        if ( $this->result !== null ) {
            $this->result->free();
        }

        $this->result = $result;

        return;
    }
}
