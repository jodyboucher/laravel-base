<?php

namespace JodyBoucher\Laravel\Eloquent;

use Illuminate\Database\Eloquent\Builder;

/**
 * Applies sorting rules to specified query builder instance.
 */
trait SortingTrait
{
    /**
     * Apply sorting clauses to query.
     *
     * @param Builder $query The query builder instance to apply the sorting clauses to.
     * @param array   $sorts Array of [ column, direction ] arrays specifying the sorting clauses to apply.
     */
    protected function applySorting(Builder $query, array $sorts)
    {
        foreach ($sorts as $sortRule) {
            // determine attribute to sort by and sort direction
            if (is_array($sortRule)) {
                $sortColumn = $sortRule['column'];
                $sortDirection = mb_strtolower($sortRule['direction']) === 'asc' ? 'ASC' : 'DESC';
            } else {
                $sortColumn = $sortRule;
                $sortDirection = 'ASC';
            }

            $query->orderBy($sortColumn, $sortDirection);
        }
    }
}
