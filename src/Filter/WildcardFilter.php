<?php

namespace TheJawker\Interrogator\Filter;

class WildcardFilter
    extends AbstractFilter
{
    /**
     * Tests if the Wildcard Filter is Applicable.
     *
     * @return bool
     */
    public function isApplicable(): bool
    {
        return str_contains($this->expression, '*');
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