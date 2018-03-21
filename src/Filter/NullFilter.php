<?php

namespace TheJawker\Interrogator\Filter;

class NullFilter
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
        return $expression === '[null]';
    }

    /**
     * Applies the Filter on the Query Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression)
    {
        $this->builder->whereNull($column, $this->boolean);
    }
}