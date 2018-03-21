<?php

namespace TheJawker\Interrogator;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use TheJawker\Interrogator\Filter\Delegator;
use TheJawker\Interrogator\Filter\ListFilter;
use TheJawker\Interrogator\Filter\EqualsFilter;
use TheJawker\Interrogator\Filter\NullFilter;
use TheJawker\Interrogator\Filter\WildcardFilter;
use TheJawker\Interrogator\Filter\ComparisonFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Interrogator
{
    /**
     * The Builder to Interrogate.
     *
     * @var Builder
     */
    public $builder;

    /**
     * The request to use.
     *
     * @var Request
     */
    private $request;

    /**
     * Values that are allowed for sorting.
     *
     * Allows everything by default.
     *
     * @var array
     */
    private $allowSortBy;

    /**
     * Values that are allowed for filtering.
     *
     * Allows everything by default.
     *
     * @var array
     */
    private $allowFilters;

    /**
     * Instantiates a new Interrogator.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
        $this->request = request();
    }

    /**
     * Sets an alternative Request.
     *
     * @param Request $request
     * @return $this
     */
    public function request(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Gets the instance of the Query Builder.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        $this->interrogate();

        return $this->builder;
    }

    /**
     * Helper method to directly get Laravel's Paginator.
     *
     * @return LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator
    {
        return $this->query()->paginate();
    }

    /**
     * Helper function to get the Eloquent Collection on the QueryBuilder.
     *
     * @return Collection|static[]
     */
    public function get(): Collection
    {
        $builder = $this->query();

        return $builder->get();
    }

    /**
     * Interrogates the different sections.
     */
    private function interrogate()
    {
        if ($this->request->exists('filter')) {
            $this->filter();
        }

        if ($this->request->exists('sort')) {
            $this->sort();
        }

        if ($this->request->exists('fields')) {
            $this->selectFields();
        }
    }

    /**
     * These columns are allowed for Sorting.
     *
     * @param $values
     * @return $this
     */
    public function allowSortBy($values): self
    {
        $this->allowSortBy = $values;

        return $this;
    }

    /**
     * These columns are allowed for Filtering.
     *
     * @param $filters
     * @return $this
     */
    public function allowFilters($filters)
    {
        $this->allowFilters = $filters;

        return $this;
    }

    /**
     * Sorts the Query Builder.
     */
    private function sort()
    {
        $sortRaw = $this->request->get('sort');

        $direction = starts_with($sortRaw, '-') ? 'DESC' : 'ASC';
        $sortBy = str_after($sortRaw, '-');

        $this->guardSorting($sortBy);

        $this->builder->orderBy($sortBy, $direction);
    }

    /**
     * Sets a default initial Sorting.
     *
     * @param string $column
     * @return Interrogator
     */
    public function defaultSortBy(string $column): self
    {
        $this->request->query->add([
            'sort' => $column
        ]);

        return $this;
    }

    /**
     * Filters the Query Builder.
     */
    private function filter()
    {
        $this->builder->where(function ($builder) {
            collect($this->request->get('filter', []))->each(function ($value, $column) use ($builder) {
                $this->guardFilter($column);
                $this->filterColumn($builder, $column, $value);
            });
        });
    }

    /**
     * Applies one of the matching Filters.
     *
     * @param Builder $builder
     * @param string $column
     * @param string $value
     */
    private function filterColumn(Builder $builder, string $column, string $value)
    {
        Delegator::make([
            new ListFilter($builder),
            new WildcardFilter($builder),
            new ComparisonFilter($builder),
            new NullFilter($builder),
            new EqualsFilter($builder),
        ])->execute($column, $value);
    }

    /**
     * A list of default Filters can be added.
     *
     * @param array $filters
     * @return Interrogator
     */
    public function defaultFilters(array $filters): self
    {
        $this->request->query->add([
            'filter' => $filters
        ]);

        return $this;
    }

    /**
     * Guards non-allowed filters.
     *
     * @param string $column
     */
    private function guardFilter(string $column)
    {
        if ($this->allowFilters && !in_array($column, $this->allowFilters)) {
            abort(400);
        }
    }

    /**
     * Guards non-allowed sorting.
     *
     * @param string $sortBy
     */
    private function guardSorting(string $sortBy)
    {
        if (!$this->allowSortBy) {
            return;
        }
        abort_unless(in_array($sortBy, $this->allowSortBy), 400);
    }

    /**
     * Selects specific fields on the Retrieving query.
     */
    private function selectFields()
    {
        collect($this->request->get('fields', []))->each(function($expression, $key) {
            $fields = explode(',', $expression);
            $this->builder->select($fields);
        });
    }
}