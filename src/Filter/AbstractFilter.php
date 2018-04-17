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
     * The boolean for database selection.
     *
     * @var string
     */
    protected $boolean = 'or';

    /*
     * The Expression.
     *
     * @var string
     */
    public $expression;

    /**
     * Instantiates the Filter.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Prepares and applies the Expression.
     *
     * @param string $column
     */
    public function prepareAndApply(string $column)
    {
        $this->apply($column, $this->expression);
    }

    /**
     * Prepares the Expression. Checks for boolean types.
     *
     * @param string $expression
     */
    public function prepareExpression(string $expression)
    {
        if (preg_match("/\[(or|and)\](.*)/", $expression, $values)) {
            $this->boolean = $values[1];
            $this->expression = $values[2];
        } else {
            $this->expression = $expression;
        }
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