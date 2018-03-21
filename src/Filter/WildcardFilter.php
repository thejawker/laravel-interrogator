<?php

namespace TheJawker\Interrogator\Filter;

class WildcardFilter
    extends AbstractFilter
{
    /**
     * Tests if the Wildcard Filter is Applicable.
     *
     * @param string $expression
     * @return bool
     */
    public function isApplicable(string $expression): bool
    {
        return str_contains($expression, '*');
    }

    /**
     * Applies the Filter on the Query Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression)
    {
        $this->where($column, 'like', str_replace('*', '%', $expression));
    }
}