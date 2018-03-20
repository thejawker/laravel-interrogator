<?php

namespace TheJawker\Interrogator\Filter;

class EqualsFilter
    extends AbstractFilter
{
    /**
     * Tests if the Equals Filter is Applicable.
     *
     * @param string $expression
     * @return bool
     */
    public function isApplicable(string $expression): bool
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
        $this->builder->orWhere($column, $expression);
    }
}