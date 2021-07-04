<?php

namespace Swiftly\Database;

/**
 * Simple data object used to store connection parameters
 *
 * @author clvarley
 */
Class Connection
{

    /**
     * Database host name or IP address
     *
     * @var string $host Database host
     */
    public $host = '';

    /**
     * The username for this database
     *
     * @var string $username Database username
     */
    public $username = '';

    /**
     * The password for this database
     *
     * @var string $password Database password
     */
    public $password = '';

    /**
     * Named database against which queries are made
     *
     * @var string $name Database name
     */
    public $name = '';

    /**
     * The port number to connect to
     *
     * @var int $port Port number
     */
    public $port = 0;

    /**
     * Socket or named pipe to use
     *
     * @var string $socket Connection socket
     */
    public $socket = '';

    /**
     * Convert an array into a connection object
     *
     * @psalm-param array{
     *  host:string,
     *  username?:string,
     *  password?:string,
     *  name?:string,
     *  post?:int
     * } $credentials
     *
     * @param array $credentials Connection credentials
     * @return Connection        Connection object
     */
    public static function fromArray( array $credentials ) : Connection
    {
        $connection = new Connection;
        $connection->host     = $credentials['host'];
        $connection->username = $credentials['username'] ?? '';
        $connection->password = $credentials['password'] ?? '';
        $connection->name     = $credentials['name'] ?? '';
        $connection->port     = $credentials['post'] ?? 0;

        return $connection;
    }
}
