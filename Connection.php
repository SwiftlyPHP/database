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

}
