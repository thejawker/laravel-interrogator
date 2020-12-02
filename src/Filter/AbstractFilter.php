<?php

namespace TheJawker\Interrogator\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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
        if (Str::contains($column, '.')) {
            $relations = explode('.', $column);
            $where = array_pop($relations);

            $this->builder->orWhereHas(join('.', $relations), function (Builder $builder) use ($where, $operator, $value) {
                // @todo: check the 'and', 'or' operators in the nested ones.
                $builder->where($where, $operator, $value);
            });
        } else {
            $this->builder->where($column, $operator, $value, $this->boolean);
        }
    }

    protected function whereIn($column, $values)
    {
        $this->builder->whereIn($column, $values, $this->boolean);
    }
}