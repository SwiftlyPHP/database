<?php declare(strict_types=1);

namespace Swiftly\Database\Parameter;

use Swiftly\Database\AbstractParameter;

/**
 * Represents a whole number parameter to be used within the query.
 *
 * @package Query
 *
 * @psalm-immutable
 * @extends AbstractParameter<int>
 */
class IntegerParameter extends AbstractParameter
{
}
