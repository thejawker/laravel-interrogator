<?php

namespace TheJawker\Interrogator\Filter;

class EqualsFilter
    extends AbstractFilter
{
    /**
     * Tests if the Equals Filter is Applicable.
     *
     * @return bool
     */
    public function isApplicable(): bool
    {
        return true;
    }

    /**
     * Applies the Filter on the Equals Builder.
     *
     * @param string $column
     * @param string $expression
     */
    public function apply(string $column, string $expression)
    {
        $this->where($column, $expression);
    }
}