<?php

namespace JodyBoucher\Laravel\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use JodyBoucher\Laravel\Utility\ArrayUtility;

/**
 * Applies filtering options to specified query builder instance.
 */
trait FilteringTrait
{
    /**
     * Apply filtering clauses to query.
     *
     * @param Builder $query   The query builder instance to apply the filtering clauses to.
     * @param array   $filters Array of [ key, value, operator, not ] arrays specifying the filtering clauses to add.
     */
    protected function applyFilters(Builder $query, array $filters = [])
    {
        foreach ($filters as $filterRule) {
            $this->applyFilter($query, $filterRule);
        }
    }

    /**
     * Apply filtering clause to query.
     *
     * @param Builder $query  The query builder instance to apply the filtering clause to.
     * @param array   $filter Array [ key, value, operator, not ] specifying the filtering clause to apply.
     */
    protected function applyFilter(Builder $query, array $filter)
    {
        $key = ArrayUtility::getValueOrDefault($filter, 'key', '');
        if ($key === null || $key === '') {
            return;
        }

        $value = ArrayUtility::getValueOrDefault($filter, 'value', '');
        $operator = ArrayUtility::getValueOrDefault($filter, 'operator', 'eq');
        $not = ArrayUtility::getValueOrDefault($filter, 'not', false);

        $table = $query->getModel()->getTable();
        $databaseField = $table . '.' . $key;

        $clauseMethod = 'where';
        $clauseOperator = null;

        switch ($operator) {
            case 'contain':
            case 'start':
            case 'end':
                $valuePre = ($operator === 'contain' || $operator === 'end' ? '%' : '');
                $valuePost = ($operator === 'contain' || $operator === 'start' ? '%' : '');
                $value = $valuePre . $value . $valuePost;

                $clauseOperator = $not ? 'NOT ILIKE' : 'ILIKE';
                $databaseField = 'CAST(' . $databaseField . ' AS TEXT)';
                break;
            case 'eq':
            default:
                $clauseOperator = $not ? '!=' : '=';
                break;
            case 'bt':
                $clauseMethod = ($not === true ? 'whereNotBetween' : 'whereBetween');
                break;
            case 'gt':
                $clauseOperator = $not ? '<=' : '>';
                break;
            case 'gte':
                $clauseOperator = $not ? '<' : '>=';
                break;
            case 'lt':
                $clauseOperator = $not ? '>=' : '<';
                break;
            case 'lte':
                $clauseOperator = $not ? '>' : '<=';
                break;
            case 'in':
                $clauseMethod = ($not === true ? 'whereNotIn' : 'whereIn');
                break;
            case 'null':
                $clauseMethod = ($not === true ? 'whereNotNull' : 'whereNull');
                break;
        }

        switch ($operator) {
            case 'null':
                $parameters = [$databaseField];
                break;
            case 'in':
            case 'bt':
                $parameters = [$databaseField, $value];
                break;
            default:
                $parameters = [$databaseField, $clauseOperator, $value];
                break;
        }

        call_user_func_array([$query, $clauseMethod], $parameters);
    }
}
