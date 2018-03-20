<?php

namespace TheJawker\Interrogator\Filter;

class ListFilter
    extends AbstractFilter
{
    public function isApplicable(string $expression): bool
    {
        return str_contains($expression, ',') && !str_contains($expression, '\,');
    }

    public function apply(string $column, string $expression)
    {
        $values = explode(',', $expression);
        return $this->builder->orWhereIn($column, $values);
    }
}