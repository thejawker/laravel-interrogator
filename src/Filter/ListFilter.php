<?php

namespace TheJawker\Interrogator\Filter;

class ListFilter
    extends AbstractFilter
{
    /**
     * Tests if the List Filter is Applicable.
     *
     * @return bool
     */
    public function isApplicable(): bool
    {
        return str_contains($this->expression, ',') && !str_contains($this->expression, '\,');
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
        $this->whereIn($column, $values);
    }
}