<?php

namespace TheJawker\Interrogator\Filter;

class EqualsFilter
    extends AbstractFilter
{
    public function isApplicable(string $expression): bool
    {
        return true;
    }

    public function apply(string $column, string $expression)
    {
        $this->builder->orWhere($column, $expression);
    }
}