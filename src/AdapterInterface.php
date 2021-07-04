<?php

namespace Swiftly\Database;

use Swiftly\Database\Connection;

/**
 * Interface all database adapters have to implement
 *
 * @author clvarley
 */
Interface AdapterInterface
{

    /**
     * Connect to the database
     *
     * @return bool Connected successfully
     */
    public function connect() : bool;

    /**
     * Disconnect from the database
     *
     * @return void N/a
     */
    public function disconnect() : void;

    /**
     * Execute the given SQL query
     *
     * @param string $sql SQL query
     * @return bool       Query successful
     */
    public function query( string $sql ) : bool;

    /**
     * Gets the first result from the last query
     *
     * @return array Query result
     */
    public function getResult() : array;

    /**
     * Gets all the results from the last query
     *
     * @return array[] Query results
     */
    public function getResults() : array;

    /**
     * Gets the auto ID of the last 'INSERT' or 'UPDATE' query
     *
     * @return int Insert ID
     */
    public function getLastInsertId() : int;

}
