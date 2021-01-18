<?php

namespace Swiftly\Database;

use Swiftly\Database\AdapterInterface;

/**
 * Provides common wrapper for working with SQL databases
 *
 * @author clvarley
 */
Class Wrapper
{

    /**
     * The underlying database adapter/implementation
     *
     * @var AdapterInterface $adapter Database adapter
     */
    private $adapter;

    /**
     * Current database connection status
     *
     * @var bool $connected Database connected
     */
    private $connected = false;

    /**
     * Creates a new wrapper around the given database adapter
     *
     * @param AdapterInterface $adapter Database adapter
     */
    public function __construct( AdapterInterface $adapter )
    {
        $this->adapter = $adapter;
    }

    /**
     * Tidy up the connection on class destruction
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Attempt to open a connection to the database
     *
     * @return bool Database connected
     */
    public function connect() : bool
    {
        if ( !$this->connected ) {
            $this->connected = $this->adapter->connect();
        }

        return $this->connected;
    }

    /**
     * Close the connection to the database
     *
     * @return void N/a
     */
    public function disconnect() : void
    {
        if ( $this->connected ) {
            $this->adapter->disconnect();
        }

        return;
    }

    /**
     * Execute the given SQL query
     *
     * @param string $sql SQL query
     * @return bool       Query successful
     */
    public function query( string $sql ) : bool
    {
        if ( !$this->connected ) {
            return false;
        }

        return $this->adapter->query( $sql );
    }

    /**
     * Execute an SQL query and return the first row/result
     *
     * @param string $sql SQL query
     * @return array      Query result
     */
    public function queryResult( string $sql ) : array
    {
        if ( !$this->connected || !$this->adapter->query( $sql ) ) {
            return [];
        }

        return $this->adapter->getResult();
    }

    /**
     * Execute an SQL query and return all rows/results
     *
     * @param string $sql SQL query
     * @return array      Query results
     */
    public function queryResults( string $sql ) : array
    {
        if ( !$this->connected || !$this->adapter->query( $sql ) ) {
            return [];
        }

        return $this->adapter->getResults();
    }

    /**
     * Gets the auto ID of the last 'INSERT' or 'UPDATE' query
     *
     * @return int Insert ID
     */
    public function getLastInsertId() : int
    {
        return ( $this->connected
            ? $this->adapter->getLastInsertId()
            : 0
        );
    }
}
