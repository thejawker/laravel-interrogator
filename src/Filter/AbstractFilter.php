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
     * Instantiates the Filter.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }
}