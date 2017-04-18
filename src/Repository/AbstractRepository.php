<?php

namespace JodyBoucher\Laravel\Repository;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;
use JodyBoucher\Laravel\Eloquent\FilteringTrait;
use JodyBoucher\Laravel\Eloquent\SortingTrait;

abstract class AbstractRepository implements RepositoryInterface
{
    use FilteringTrait, SortingTrait;

    protected $database;

    protected $model;

    protected $sortProperty = null;

    // 0 = ASC, 1 = DESC
    protected $sortDirection = 0;

    abstract protected function getModel();

    final public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
        $this->model = $this->getModel();
    }

    /**
     * Get all items.
     *
     * @param  array $options
     *
     * @return Collection
     */
    public function get(array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        return $query->get();
    }

    /**
     * Get an item by its primary key.
     *
     * @param  mixed $id
     * @param  array $options
     *
     * @return Collection
     */
    public function getById($id, array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        return $query->find($id);
    }

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
    public function getWhere($column, $operator, $value, array $options = [])
    {
        $query = $this->createBaseBuilder($options);
        $query->where($column, $operator, $value);

        return $query->get();
    }

    /**
     * Get items by multiple where clauses.
     *
     * @param  array $clauses
     * @param  array $options
     *
     * @deprecated
     * @return Collection
     */
    public function getWhereArray(array $clauses, array $options = [])
    {
        $query = $this->createBaseBuilder($options);
        $query->where($clauses);

        return $query->get();
    }

    /**
     * Get items where a column value exists in array.
     *
     * @param  string $column
     * @param  array  $values
     * @param  array  $options
     *
     * @return Collection
     */
    public function getWhereIn($column, array $values, array $options = [])
    {
        $query = $this->createBaseBuilder($options);
        $query->whereIn($column, $values);

        return $query->get();
    }

    /**
     * Delete an item by its primary key.
     *
     * @param  mixed $id
     *
     * @return void
     */
    public function delete($id)
    {
        $query = $this->createQueryBuilder();
        $query->where($this->getPrimaryKey($query), $id);

        $query->delete();
    }

    /**
     * Delete items by a where clause.
     *
     * @param  string $column
     * @param  mixed  $value
     *
     * @return void
     */
    public function deleteWhere($column, $value)
    {
        $query = $this->createQueryBuilder();
        $query->where($column, $value);

        $query->delete();
    }

    /**
     * Delete items by multiple where clauses.
     *
     * @param  array $clauses
     *
     * @return void
     */
    public function deleteWhereArray(array $clauses)
    {
        $query = $this->createQueryBuilder();
        $query->whereArray($clauses);

        $query->delete();
    }

    /**
     * Apply options to a query builder.
     *
     * @param  Builder $query   The query to apply the options to.
     * @param  array   $options The options to apply.
     *
     * @return Builder The modified query builder.
     */
    protected function applyQueryOptions(Builder $query, array $options = [])
    {
        if (!empty($options)) {
            if (array_has($options, 'with')) {
                $withes = $options['with'];
                if (!is_array($withes)) {
                    throw new InvalidArgumentException('\'with\' must be an array.');
                }
                $query->with($withes);
            }

            if (array_has($options, 'filter')) {
                $filters = $options['filter'];
                if (!is_array($filters)) {
                    throw new InvalidArgumentException('\'filter\' must be an array.');
                }

                $this->applyFilters($query, $options['filter']);
            }

            if (array_has($options, 'sort')) {
                $sorts = $options['sort'];
                if (!is_array($sorts)) {
                    throw new InvalidArgumentException('\'sort\' must be an array.');
                }

                $this->applySorting($query, $sorts);
            }

            $limit = 10;
            if (array_has($options, 'limit')) {
                $limit = $options['limit'];
                $query->limit($limit);
            }

            if (array_has($options, 'page')) {
                $query->offset($options['page'] * $limit);
            }
        }

        return $query;
    }

    /**
     * Create a new query builder with options applied.
     *
     * @param  array $options
     *
     * @return Builder
     */
    protected function createBaseBuilder(array $options = [])
    {
        $query = $this->createQueryBuilder();

        $this->applyQueryOptions($query, $options);

        if (empty($options['sort'])) {
            $this->defaultSort($query, $options);
        }

        return $query;
    }

    /**
     * Create a new query builder for the associated model.
     *
     * @return Builder
     */
    protected function createQueryBuilder()
    {
        return $this->model->newQuery();
    }

    /**
     * Order query by the specified sorting property.
     *
     * @param  Builder $query
     * @param  array   $options
     *
     * @return void
     */
    protected function defaultSort(Builder $query, array $options = [])
    {
        if (isset($this->sortProperty)) {
            $direction = $this->sortDirection === 1 ? 'DESC' : 'ASC';
            $query->orderBy($this->sortProperty, $direction);
        }
    }

    /**
     * Get primary key name of the underlying model.
     *
     * @param  Builder $query
     *
     * @return string
     */
    protected function getPrimaryKey(Builder $query)
    {
        return $query->getModel()->getKeyName();
    }
}
