<?php declare(strict_types=1);

namespace Swiftly\Database\Parameter;

use Swiftly\Database\AbstractParameter;

/**
 * Represents a boolean true/false parameter to be used within the query.
 *
 * @package Query
 *
 * @psalm-immutable
 * @extends AbstractParameter<bool>
 */
class BooleanParameter extends AbstractParameter
{
}
