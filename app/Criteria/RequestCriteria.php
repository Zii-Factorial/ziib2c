<?php

namespace App\Criteria;

use App\Enums\RequestOperator;
use App\Helpers\RequestParameterParser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Prettus\Repository\Contracts\Criteria;
use Prettus\Repository\Contracts\Repository;

class RequestCriteria implements Criteria
{
    protected Request $request;

    protected RequestParameterParser $parser;

    public function __construct(RequestParameterParser $parser, Request $request)
    {
        $this->parser = $parser;
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository.
     *
     * @param  Builder|Model  $model
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $select = $this->parser->getSelect($this->request);
        $wheres = $this->parser->getFilter($this->request, 'and');
        $orWheres = $this->parser->getFilter($this->request, 'or');
        $has = $this->parser->getHas($this->request);
        $doesntHave = $this->parser->getDoesntHave($this->request);
        $withTrashed = $this->parser->getWithTrashed($this->request);
        $onlyTrashed = $this->parser->getOnlyTrashed($this->request);
        $with = $this->parser->getWith($this->request);
        $withCount = $this->parser->getWithCount($this->request);
        $sorts = $this->parser->getSort($this->request);

        if (! empty($wheres)) {
            $model = $this->applyConditions($model, $wheres, 'and');
        }

        if (! empty($orWheres)) {
            $model = $this->applyConditions($model, $orWheres, 'or');
        }

        foreach ($has as $relation) {
            $model = $model->whereHas($relation);
        }

        foreach ($doesntHave as $relation) {
            $model = $model->doesntHave($relation);
        }

        if ($withTrashed) {
            $model = $model->withTrashed();
        }

        if ($onlyTrashed) {
            $model = $model->onlyTrashed();
        }

        if (! empty($select)) {
            $model = $model->select($select);
        }

        foreach ($with as $relation) {
            if (isset($relation['fields'])) {
                $model = $model->with([
                    $relation['relation'] => function ($query) use ($relation) {
                        $query->select($relation['fields']);
                    },
                ]);
            } else {
                $model = $model->with($relation['relation']);
            }
        }

        if (! empty($withCount)) {
            $model = $model->withCount($withCount);
        }

        if (! empty($sorts)) {
            foreach ($sorts as $sort) {
                $direction = $sort['direction'] ?? 'asc';
                $model = $model->orderBy($sort['field'], $direction);
            }
        }

        return $model;
    }

    /**
     * Apply where/orWhere conditions to the query.
     */
    private function applyConditions($model, array $filters, string $type = 'and')
    {
        $conditionPrefix = $type === 'and' ? '' : 'or';

        return $model->{$conditionPrefix . 'where'}(function ($query) use ($filters, $conditionPrefix) {
            foreach ($filters as $filter) {
                $field = $filter['field'];
                $operatorKey = $filter['operator'] ?? 'eq';
                $value = $filter['value'] ?? null;

                if (is_array($field)) {
                    $this->applyNestedRelationCondition($query, $filter, $conditionPrefix);

                    continue;
                }
                $this->applyOperatorCondition($query, $field, $operatorKey, $value, $conditionPrefix);
            }
        });
    }

    /**
     * Apply nested relation where/orWhereHas conditions.
     */
    private function applyNestedRelationCondition($query, array $filter, string $conditionPrefix): void
    {
        $fields = $filter['field'];
        $relations = array_slice($fields, 0, -1);
        $field = end($fields);

        $query->{$conditionPrefix . 'WhereHas'}(implode('.', $relations), function ($q) use ($field, $filter) {
            $operatorKey = $filter['operator'] ?? 'eq';
            $value = $filter['value'] ?? null;
            $this->applyOperatorCondition($q, $field, $operatorKey, $value, '');
        });
    }

    /**
     * Compute the Eloquent operator and value from the API operator.
     */
    private function getOperatorAndValue(string $operatorKey, $value): array
    {
        $acceptedConditions = config('repository.criteria.acceptedConditions');
        $operator = RequestOperator::tryFrom($operatorKey);

        throw_if(! $operator || ! in_array($operator->value, $acceptedConditions), new InvalidArgumentException("Invalid operator: {$operatorKey}"));

        return match ($operator) {
            RequestOperator::IN,
            RequestOperator::NOT_IN,
            RequestOperator::BETWEEN => [$operator, is_array($value) ? $value : $this->parser->splitByDelimiter($value)],
            RequestOperator::IS_NULL,
            RequestOperator::NOT_NULL => [$operator, null],
            RequestOperator::STARTS,
            RequestOperator::STARTL => [$operator, $value . '%'],
            RequestOperator::ENDS,
            RequestOperator::ENDL => [$operator, '%' . $value],
            RequestOperator::CONTS,
            RequestOperator::CONTL => [$operator, '%' . $value . '%'],
            RequestOperator::EXCL,
            RequestOperator::EXCLL => [$operator, '%' . $value . '%'],
            RequestOperator::EQ_DATE => [$operator, $value],
            RequestOperator::EQ => ['=', $value],
            RequestOperator::NE_DATE => [$operator, $value],
            RequestOperator::NE => ['!=', $value],
            RequestOperator::GT => ['>', $value],
            RequestOperator::GTE => ['>=', $value],
            RequestOperator::LT => ['<', $value],
            RequestOperator::LTE => ['<=', $value],
            default => ['=', $value],
        };
    }

    /**
     * Apply a single operator condition to the query.
     */
    private function applyOperatorCondition($query, string $field, string $operatorKey, $value, string $conditionPrefix): void
    {
        [$op, $val] = $this->getOperatorAndValue($operatorKey, $value);

        switch ($op) {
            case RequestOperator::IN:
                $query->{$conditionPrefix . 'WhereIn'}($field, $val);
                break;
            case RequestOperator::NOT_IN:
                $query->{$conditionPrefix . 'WhereNotIn'}($field, $val);
                break;
            case RequestOperator::IS_NULL:
                $query->{$conditionPrefix . 'WhereNull'}($field);
                break;
            case RequestOperator::NOT_NULL:
                $query->{$conditionPrefix . 'WhereNotNull'}($field);
                break;
            case RequestOperator::STARTS:
            case RequestOperator::ENDS:
            case RequestOperator::CONTS:
                $query->{$conditionPrefix . 'Where'}($field, 'LIKE', $val);
                break;
            case RequestOperator::EXCL:
                $query->{$conditionPrefix . 'WhereNot'}(fn ($q) => $q->where($field, 'LIKE', $val));
                break;
            case RequestOperator::STARTL:
            case RequestOperator::ENDL:
            case RequestOperator::CONTL:
                $query->{$conditionPrefix . 'Where'}($field, 'ILIKE', $val);
                break;
            case RequestOperator::EXCLL:
                $query->{$conditionPrefix . 'WhereNot'}(fn ($q) => $q->where($field, 'ILIKE', $val));
                break;
            case RequestOperator::BETWEEN:
                $query->{$conditionPrefix . 'WhereBetween'}($field, $val);
                break;
            case RequestOperator::EQ_DATE:
                $query->{$conditionPrefix . 'WhereDate'}($field, $val);
                break;
            case RequestOperator::NE_DATE:
                $query->{$conditionPrefix . 'WhereDate'}($field, '!=', $val);
                break;
            default:
                $query->{$conditionPrefix . 'Where'}($field, $op, $val);
        }
    }
}
