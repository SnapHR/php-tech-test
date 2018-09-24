<?php

namespace App\Http\Controllers\GraphQL\Scalars;

use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Carbon\Carbon;
use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;

class Date extends ScalarType
{
    public $name = 'Date';

    public $description = 'A date string with format d.m.Y. Example: "10.12.2014."';

    public function serialize($value)
    {
        return $value->toAtomString();
    }

    public function parseValue($value)
    {
        try {
            $date = Carbon::createFromFormat('d.m.Y.', $value);
        } catch (\Exception $e) {
            throw new Error(Utils::printSafeJson($e->getMessage()));
        }

        return $date;
    }

    public function parseLiteral($valueNode, array $variables = null)
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        try {
            $date = Carbon::createFromFormat('m.d.Y.', $valueNode->value);
        } catch (\Exception $e) {
            throw new Error(Utils::printSafeJson($e->getMessage()));
        }

        return $date;
    }
}
