<?php

namespace TheJawker\Interrogator\Filter;

class WildcardFilter
    extends AbstractFilter
{
    public function isApplicable(string $expression): bool
    {
        return str_contains($expression, '*');
    }

    public function apply(string $column, string $expression)
    {
        return $this->builder->orWhere($column, 'like', str_replace('*', '%', $expression));
    }
}