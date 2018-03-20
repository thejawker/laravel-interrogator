<?php

namespace TheJawker\Interrogator\Filter;

class ListFilter
    extends AbstractFilter
{
    /**
     * Tests if the List Filter is Applicable.
     *
     * @param string $expression
     * @return bool
     */
    public function isApplicable(string $expression): bool
    {
        return str_contains($expression, ',') && !str_contains($expression, '\,');
    }

    /**
     * Applies the Filter on the Query Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression)
    {
        $values = explode(',', $expression);
        $this->builder->orWhereIn($column, $values);
    }
}