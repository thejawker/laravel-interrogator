<?php

namespace TheJawker\Interrogator\Filter;

use Illuminate\Support\Str;

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
        return Str::contains($this->expression, '*');
    }

    /**
     * Applies the Filter on the Query Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression)
    {
        $this->where($column, 'like', str_replace('*', '%', $this->expression));
    }
}