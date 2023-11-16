<?php declare(strict_types=1);

namespace Swiftly\Database\Builder;

use Swiftly\Database\BuilerInterface;
use PDO;
use Swiftly\Database\Backend\PdoAdapter;

use function sprintf;
use function implode;

/**
 * Utility for configuring and initialising PDO adapters.
 *
 * @see \Swiftly\Database\Backend\PdoAdapter
 *
 * @package Builder
 */
class PdoAdapterBuilder implements BuilerInterface
{
    private string $type;
    private ?string $hostname = null;
    private ?string $socket = null;
    private ?int $port = null;
    private ?string $database = null;
    private ?string $charset = null;
    private ?string $username = null;
    private ?string $password = null;
    /** @var array<string,scalar> $options */
    private array $options = [];
    /** @var array<int,mixed> $attributes */
    private array $attributes = [];

    /**
     * Create a new PDO builder for the given database type.
     *
     * @param non-empty-string $type Database type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Creates a new PDO builder for MySQL/MariaDB databases.
     *
     * @return self Configurable builder
     */
    public static function Mysql(): self
    {
        return new self('mysql');
    }

    /**
     * Creates a new PDO builder for PostgreSQL databases.
     *
     * @return self Configurable builder
     */
    public static function Postgres(): self
    {
        return new self('pgsql');
    }

    /**
     * Configure the hostname for this PDO connection.
     *
     * @param string|null $hostname Database host
     * @return self
     */
    public function setHostname(?string $hostname): self
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Configure the UNIX socket to use for this PDO connection.
     *
     * @param string|null $socket UNIX socket
     * @return self
     */
    public function setSocket(?string $socket): self
    {
        $this->socket = $socket;

        return $this;
    }

    /**
     * Set the port number to use for this PDO connection.
     *
     * @param int|null $port Port number
     * @return self
     */
    public function setPort(?int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Set the name of the database to use for this PDO connection.
     *
     * @param string|null $database Database name
     * @return self
     */
    public function setDatabase(?string $database): self
    {
        $this->database = $database;

        return $this;
    }

    /**
     * Set the charset to use for this PDO connection.
     *
     * @param string|null $charset Character set to use
     */
    public function setCharset(?string $charset): self
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Set the username to use when connecting to the database.
     *
     * @param string|null $username Database user
     * @return self
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set the password to use when connecting to the database.
     *
     * @param string|null $password Database password
     * @return self
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set an option to be passed to the PDO constructor.
     *
     * @param string $option Option name
     * @param scalar $value  Option value
     * @return self
     */
    public function setOption(string $option, $value): self
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Set a PDO attribute.
     *
     * For the first argument `$attribute` one of the `PDO::ATTR_` constants
     * should be provided. See the PDO documentation for more information.
     *
     * @see \PDO::setAttribute
     *
     * @psalm-param PDO::ATTR_* $attribute
     *
     * @param int $attribute Attribute to set
     * @param mixed $value   Attribute value
     * @return self
     */
    public function setAttribute(int $attribute, $value): self
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Creates a new PDO adapter using the configuration values specified.
     *
     * @return PdoAdapter PDO adapter object
     */
    public function create(): PdoAdapter
    {
        $pdo = new PDO(
            $this->createDsn(),
            $this->username,
            $this->password,
            $this->options
        );

        foreach ($this->attributes as $name => $value) {
            $pdo->setAttribute($name, $value);
        }

        return new PdoAdapter($pdo);
    }

    /**
     * Create a DSN string using the provided configuration.
     *
     * @return string DSN string
     */
    private function createDsn(): string
    {
        $dsn = [];

        if (null !== $this->hostname) {
            $dsn[] = sprintf('%s:host=%s', $this->type, $this->hostname);
        } elseif (null !== $this->socket) {
            $dsn[] = sprintf('%s:unix_socket=%s', $this->type, $this->socket);
        } else {
            
        }

        if (null !== $this->port) {
            $dsn[] = sprintf('port=%d', $this->port);
        }

        if (null !== $this->database) {
            $dsn[] = sprintf('dbname=%s', $this->database);
        }

        if (null !== $this->charset) {
            $dsn[] = sprintf('charset=%s', $this->charset);
        }

        return implode(';', $dsn);
    }
}