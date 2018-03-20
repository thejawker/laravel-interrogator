<?php

namespace TheJawker\Interrogator\Filter;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
    implements FilterInterface
{
    /**
     * @var Builder
     */
    protected $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }
}