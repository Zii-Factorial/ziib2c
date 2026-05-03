<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class RequestParameterParser
{
    /**
     * Delimiter for separating multiple relations or fields.
     */
    private const SEPARATOR = ';';

    /**
     * Delimiter for separating relation and its fields.
     */
    private const RELATION_FIELD_DELIMITER = '||';

    /**
     * Delimiter for separating multiple fields.
     */
    private const FIELD_DELIMITER = ',';

    /**
     * Get the combined list of selected fields and relations for output filtering.
     */
    public function getOnly(Request $request): array
    {
        $selectFields = $this->getSelect($request);
        $withRelations = $this->getWith($request);

        $withFields = array_map(function ($relation) {
            if (isset($relation['fields'])) {
                return "{$relation['relation']}.{".implode(self::FIELD_DELIMITER, $relation['fields']).'}';
            }

            return $relation['relation'];
        }, $withRelations);

        if (empty($selectFields) && count($withFields)) {
            return [];
        }

        return array_merge($selectFields, $withFields);
    }

    /**
     * Get selected fields from the request.
     */
    public function getSelect(Request $request): array
    {
        $select = $request->get($this->getConfigParam('select'));

        if (! is_string($select) || trim($select) === '') {
            return [];
        }

        return $this->splitByDelimiter($select);
    }

    /**
     * Get relations (with optional fields) from the request.
     */
    public function getWith(Request $request): array
    {
        $with = $request->get($this->getConfigParam('with'));
        if (! is_string($with) || trim($with) === '') {
            return [];
        }
        $relations = strpos($with, self::SEPARATOR) !== false
            ? $this->splitBySeparator($with)
            : [$with];

        return array_map([$this, 'parseWithRelation'], $relations);
    }

    /**
     * Get withCount relations from the request.
     */
    public function getWithCount(Request $request): array
    {
        $withCount = $request->get($this->getConfigParam('withCount'));
        if (! is_string($withCount) || trim($withCount) === '') {
            return [];
        }

        return $this->splitBySeparator($withCount);
    }

    /**
     * Get has relations from the request.
     */
    public function getHas(Request $request): array
    {
        $has = $request->get($this->getConfigParam('has'));
        if (! is_string($has) || trim($has) === '') {
            return [];
        }

        return $this->splitBySeparator($has);
    }

    /**
     * Get doesntHave relations from the request.
     */
    public function getDoesntHave(Request $request): array
    {
        $doesntHave = $request->get($this->getConfigParam('doesntHave'));
        if (! is_string($doesntHave) || trim($doesntHave) === '') {
            return [];
        }

        return $this->splitBySeparator($doesntHave);
    }

    /**
     * Check if withTrashed is requested.
     */
    public function getWithTrashed(Request $request): bool
    {
        return $request->boolean($this->getConfigParam('withTrashed'));
    }

    /**
     * Check if onlyTrashed is requested.
     */
    public function getOnlyTrashed(Request $request): bool
    {
        return $request->boolean($this->getConfigParam('onlyTrashed'));
    }

    /**
     * Get filter conditions from the request.
     */
    public function getFilter(Request $request, string $type = 'and'): array
    {
        if (! in_array($type, ['and', 'or'], true)) {
            throw new \InvalidArgumentException('Type must be "and" or "or".');
        }
        $typeKey = $type === 'and' ? 'where' : 'orWhere';
        $where = $request->get($this->getConfigParam($typeKey));

        if (! is_string($where) || trim($where) === '') {
            return [];
        }

        $conditions = strpos($where, self::SEPARATOR) !== false
            ? $this->splitBySeparator($where)
            : [$where];

        return array_map([$this, 'parseCondition'], $conditions);
    }

    /**
     * Parse a relation string, possibly with fields.
     */
    private function parseWithRelation(string $relation): array
    {
        if (strpos($relation, self::RELATION_FIELD_DELIMITER) !== false) {
            [$relationName, $fields] = $this->splitByFieldDelimiter($relation, 2);

            return [
                'relation' => trim($relationName),
                'fields' => $this->splitByDelimiter($fields),
            ];
        }

        return [
            'relation' => trim($relation),
        ];
    }

    /**
     * Get the limit value from the request for pagination.
     */
    public function getSort(Request $request): array
    {
        $sort = (string) $request->string($this->getConfigParam('sort'));

        $sorts = $this->splitBySeparator($sort);

        $sorts = array_map(function ($sort) {
            if (strpos($sort, self::FIELD_DELIMITER) !== false) {
                [$field, $direction] = $this->splitByDelimiter($sort);

                return [
                    'field' => trim($field),
                    'direction' => strtolower(trim($direction)) === 'desc' ? 'desc' : 'asc',
                ];
            }

            return [
                'field' => trim($sort),
                'direction' => 'asc',
            ];
        }, $sorts);

        return $sorts;
    }

    /**
     * Parse a filter condition string.
     */
    private function parseCondition(string $condition): array
    {
        if (strpos($condition, self::RELATION_FIELD_DELIMITER) !== false) {
            [$field, $operator, $value] = $this->splitByFieldDelimiter($condition) + [null, '=', null];

            $field = strpos($field, '.') !== false
                ? array_map('trim', explode('.', $field))
                : trim($field);

            return [
                'field' => $field,
                'operator' => $operator ?? '=',
                'value' => $value,
            ];
        }

        return [
            'field' => trim($condition),
            'operator' => '=',
            'value' => null,
        ];
    }

    /**
     * Split a string by RELATION_FIELD_DELIMITER and trim each part.
     */
    public function splitByFieldDelimiter(string $value, int $limit = PHP_INT_MAX): array
    {
        return $this->splitBy($value, self::RELATION_FIELD_DELIMITER, $limit);
    }

    /**
     * Split a string by SEPARATOR and trim each part.
     */
    public function splitBySeparator(string $value, int $limit = PHP_INT_MAX): array
    {
        return $this->splitBy($value, self::SEPARATOR);
    }

    /**
     * Split a string by FIELD_DELIMITER and trim each part.
     */
    public function splitByDelimiter(string $value, int $limit = PHP_INT_MAX): array
    {
        return $this->splitBy($value, self::FIELD_DELIMITER, $limit);
    }

    /**
     * Split a string value by the given delimiter and trim each part.
     */
    private function splitBy(string $value, string $delimiter, int $limit = PHP_INT_MAX): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        return array_map('trim', explode($delimiter, $value, $limit));
    }

    /**
     * Get a configuration parameter for request criteria.
     */
    private function getConfigParam(string $field): string
    {
        return config("repository.criteria.params.{$field}", $field);
    }
}
