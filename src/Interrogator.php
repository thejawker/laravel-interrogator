<?php

namespace TheJawker\Interrogator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Interrogator
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $allowSortBy;

    /**
     * @var array
     */
    private $allowFilters;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
        $this->request = request();
    }

    public function request(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function query()
    {
        $this->interrogate();

        return $this->builder;
    }

    public function paginate()
    {
        return $this->query()->paginate();
    }

    public function get()
    {
        $builder = $this->query();

        return $builder->get();
    }

    private function interrogate()
    {
        $this->filter();
        $this->sort();
    }

    public function allowSortBy($values)
    {
        $this->allowSortBy = $values;

        return $this;
    }

    public function allowFilters($filters)
    {
        $this->allowFilters = $filters;

        return $this;
    }

    private function sort()
    {
        $sortRaw = $this->request->get('sort');

        $ascending = !Str::startsWith($sortRaw, '-');
        $sortBy = Str::after($sortRaw, '-');

        if ($this->allowSortBy) {
            abort_unless(in_array($sortBy, $this->allowSortBy), 400);
        }

        return $ascending ?
            $this->builder->orderBy($sortBy) :
            $this->builder->orderByDesc($sortBy);
    }

    private function filter()
    {
        collect($this->request->get('filter', []))->each(function ($value, $column) {
            if ($this->allowFilters && !in_array($column, $this->allowFilters)) {
                abort(400);
            }

            $this->filterColumn($column, $value);
        });
    }

    private function filterColumn($column, $value)
    {
        if (str_contains($value, '*')) {
            return $this->builder->orWhere($column, 'like', str_replace('*', '%', $value));
        }

        return $this->builder->orWhere($column, $value);
    }
}