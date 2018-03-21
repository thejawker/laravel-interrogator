<?php

namespace TheJawker\Interrogator\Filter;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
    implements FilterInterface
{
    /**
     * The Builder to filter on.
     *
     * @var Builder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $boolean = 'or';

    /**
     * Instantiates the Filter.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function prepare(string $column, string $expression)
    {
        if (preg_match("/\[(or|and)\](.*)/", $expression, $values)) {
            $this->boolean = $values[1];
            $expression = $values[2];
        }

        $this->apply($column, $expression);
    }

    protected function where($column, $operator = null, $value = null)
    {
        $this->builder->where($column, $operator, $value, $this->boolean);
    }

    protected function whereIn($column, $values)
    {
        $this->builder->whereIn($column, $values, $this->boolean);
    }
}