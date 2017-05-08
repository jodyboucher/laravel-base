<?php

namespace JodyBoucher\Laravel\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface RepositoryInterface
{
    /**
     * Apply options to a query builder.
     *
     * @param  Builder $query   The query to apply the options to.
     * @param  array   $options The options to apply.
     *
     * @return Builder The modified query builder.
     */
    public function applyQueryOptions(Builder $query, array $options = []);

    /**
     * Return a new query builder for the model.
     *
     * @return Builder
     */
    public function createQuery();

    /**
     * Get all items.
     *
     * @param  array $options
     *
     * @return Collection
     */
    public function get(array $options = []);

    /**
     * Get an item by its primary key.
     *
     * @param  mixed $id
     * @param  array $options
     *
     * @return Collection
     */
    public function getById($id, array $options = []);

    /**
     * Get query results.
     *
     * @param  Builder $query
     *
     * @return Collection
     */
    public function getQuery(Builder $query);

    /**
     * Get items by a where clause.
     *
     * @param  string $column
     * @param  string $operator
     * @param  mixed  $value
     * @param  array  $options
     *
     * @return Collection
     */
    public function getWhere($column, $operator, $value, array $options = []);

    /**
     * Get items by multiple where clauses.
     *
     * @param  array $clauses
     * @param  array $options
     *
     * @deprecated
     * @return Collection
     */
    public function getWhereArray(array $clauses, array $options = []);

    /**
     * Get items where a column value exists in array.
     *
     * @param  string $column
     * @param  array  $values
     * @param  array  $options
     *
     * @return Collection
     */
    public function getWhereIn($column, array $values, array $options = []);

    /**
     * Delete an item by its primary key.
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function delete($id);

    /**
     * Delete items by a where clause.
     *
     * @param  string $column
     * @param  mixed  $value
     *
     * @return void
     */
    public function deleteWhere($column, $value);

    /**
     * Delete items by multiple where clauses.
     *
     * @param  array $clauses
     *
     * @return void
     */
    public function deleteWhereArray(array $clauses);
}
